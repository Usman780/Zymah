<?php

namespace GoDaddy\WordPress\MWC\Dashboard\Helpers;

use Exception;
use GoDaddy\WordPress\MWC\Common\Configuration\Configuration;
use GoDaddy\WordPress\MWC\Common\Exceptions\BaseExceptionHandler;
use GoDaddy\WordPress\MWC\Common\Helpers\StringHelper;
use GoDaddy\WordPress\MWC\Common\Http\GoDaddyRequest;
use GoDaddy\WordPress\MWC\Common\Repositories\ManagedWooCommerceRepository;
use GoDaddy\WordPress\MWC\Dashboard\Repositories\UserRepository;
use GoDaddy\WordPress\MWC\Dashboard\Repositories\WooCommercePluginsRepository;
use WP_Error;

class SupportHelper
{
    /**
     * Gets the \WP_User for the support user, by their login or email.
     *
     * @return \WP_User|false
     * @throws Exception
     */
    public static function getSupportUser()
    {
        if (! empty($supportUser = get_user_by('login', Configuration::get('support.support_user.login')))) {
            return $supportUser;
        }

        if (! empty($supportUser = get_user_by('email', Configuration::get('support.support_user.email')))) {
            return $supportUser;
        }

        return false;
    }

    /**
     * Gets the support bot app name.
     *
     * @return string
     */
    public static function getSupportBotAppName()
    {
        return ManagedWooCommerceRepository::isReseller() ? Configuration::get('support.support_bot.app_name_reseller') : Configuration::get('support.support_bot.app_name');
    }

    /**
     * Gets the support bot context.
     *
     * @return string `godaddy` or `reseller`
     */
    public static function getSupportBotContext()
    {
        return ManagedWooCommerceRepository::isReseller() ? 'reseller' : 'godaddy';
    }

    /**
     * Checks whether or not the site is connected to the support bot.
     *
     * @return bool
     */
    public static function isSupportBotConnected() : bool
    {
        global $wpdb;

        $appName = static::getSupportBotAppName();

        // look for WooCommerce API keys containing the support bot app name in their description
        $keys = $wpdb->get_var($wpdb->prepare("SELECT COUNT(key_id) FROM {$wpdb->prefix}woocommerce_api_keys WHERE description LIKE %s", "{$appName}%"));

        return ! empty($keys);
    }

    /**
     * Gets the URL to connect the site to the support bot.
     *
     * @return string
     * @throws Exception
     */
    public static function getSupportBotConnectUrl() : string
    {
        return add_query_arg([
            'context' => static::getSupportBotContext(),
            'url' => urlencode(site_url()),
        ], Configuration::get('support.support_bot.connect_url'));
    }

    /**
     * Creates the support user and gives it admin rights.
     *
     * @return \WP_User|false
     * @throws Exception
     */
    public static function createSupportUser()
    {
        $supportUserId = wp_create_user(Configuration::get('support.support_user.login'), wp_generate_password(), Configuration::get('support.support_user.email'));

        if (! is_wp_error($supportUserId)) {
            $supportUser = get_user_by('id', $supportUserId);

            // make sure the user is admin
            if ($supportUser) {
                $supportUser->add_role('administrator');

                return $supportUser;
            }
        }

        return false;
    }

    /**
     * Gets a WP_User, by looking for a user with the given e-mail or falling back to the current user.
     *
     * @param string $customerEmail
     * @return \WP_User|false
     */
    public static function getCustomerUser(string $customerEmail)
    {
        if (! empty($user = get_user_by('email', $customerEmail))) {
            return $user;
        }

        // fall back to the current WP user
        if (! empty($user = get_user_by('id', get_current_user_id()))) {
            return $user;
        }

        return false;
    }

    /**
     * Gets the data to be sent to the Extensions API to create a support request.
     *
     * @param string $subject
     * @param string $message
     * @param string $replyTo
     * @param string $reason
     * @param string $pluginName
     * @return array
     * @throws BaseExceptionHandler
     */
    public static function getSupportRequestData(string $subject, string $message, string $replyTo, string $reason, string $pluginName) : array
    {
        $customerUser = static::getCustomerUser($replyTo);
        $customerName = $customerUser ? UserRepository::getUserName($customerUser) : '';

        $pluginData = WooCommercePluginsRepository::getPluginDataByName($pluginName);
        $pluginVersion = ! empty($pluginData) ? WooCommercePluginsRepository::getPluginVersion($pluginData) : '';
        $supportEndDate = ! empty($pluginData) ? WooCommercePluginsRepository::getWooCommerceSubscriptionEnd($pluginData) : '';

        $data = [
            'ticket'               => [
                'subject'     => $subject,
                'description' => $message,
            ],
            'customer'             => [
                'name'  => $customerName,
                'email' => $replyTo,
            ],
            'reason' => $reason,
            'plugin'               => [
                'name'             => $pluginName,
                'version'          => $pluginVersion,
                'support_end_date' => $supportEndDate,
            ],
            'support_bot_context' => static::getSupportBotContext(),
            'system_status_report' => WC()->api->get_endpoint_data('/wc/v3/system_status'),
        ];

        if (! empty($supportUser = static::getSupportUser())) {
            $data['support_user']['user_id'] = $supportUser->ID;
            $data['support_user']['password_reset_url'] = UserRepository::getPasswordResetUrl($supportUser);
        }

        return $data;
    }

    /**
     * Sends a request to the Extensions API to create a support request.
     *
     * @param string $replyTo
     * @param array $supportRequestData
     * @throws Exception
     */
    public static function createSupportRequest(string $replyTo, array $supportRequestData)
    {
        $response = (new GoDaddyRequest)->url(StringHelper::trailingSlash(Configuration::get('mwc.extensions.api.url')) . 'support/request')
            ->headers([
                'X-Account-UID' => Configuration::get('godaddy.account.uid', ''),
                'X-Site-Token'  => Configuration::get('godaddy.site.token', 'empty')
            ])
            ->body(['data' => $supportRequestData, 'from' => $replyTo])
            ->setMethod('POST')
            ->send();

        if ($response->isError() || $response->getStatus() !== 200) {
            throw new Exception("Could not send the support request ({$response->getStatus()}): {$response->getErrorMessage()}");
        }
    }
}
