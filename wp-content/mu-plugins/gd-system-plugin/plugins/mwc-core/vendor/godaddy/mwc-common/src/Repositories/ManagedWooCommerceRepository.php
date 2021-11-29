<?php

namespace GoDaddy\WordPress\MWC\Common\Repositories;

use Exception;
use GoDaddy\WordPress\MWC\Common\Configuration\Configuration;
use GoDaddy\WordPress\MWC\Common\Helpers\ArrayHelper;
use GoDaddy\WordPress\MWC\Common\Helpers\StringHelper;
use GoDaddy\WordPress\MWC\Common\Http\Request;
use GoDaddy\WordPress\MWC\Common\Http\Response;

class ManagedWooCommerceRepository
{
    /**
     * Gets the current Managed WordPress environment.
     *
     * @since x.y.z
     *
     * @return string|null
     * @throws Exception
     */
    public static function getEnvironment()
    {
        // @TODO: Remove conditional if /web/conf/gd-wordpress.conf no longer required
        if (! Configuration::get('mwc.env')) {
            if (! $cname_link = Configuration::get('mwc.mwp_settings.cname_link')) {
                return null;
            }

            preg_match('/\.(.*?)\-/', parse_url($cname_link, PHP_URL_HOST), $matches);

            $env = ! empty(ArrayHelper::get($matches, 1)) ? ArrayHelper::get($matches, 1) : 'prod';

            Configuration::set('mwc.env', $env);
        }

        return Configuration::get('mwc.env');
    }

    /**
     * Determines if the current is a production environment.
     *
     * @since x.y.z
     *
     * @return bool
     */
    public static function isProductionEnvironment() : bool
    {
        return 'prod' === self::getEnvironment();
    }

    /**
     * Determines if the current is a testing environment.
     *
     * @since x.y.z
     *
     * @return bool
     */
    public static function isTestingEnvironment() : bool
    {
        return 'test' === self::getEnvironment();
    }

    /**
     * Determines if the site is hosted on Managed WordPress and has an eCommerce plan.
     *
     * @since x.y.z
     *
     * @return bool
     */
    public static function hasEcommercePlan() : bool
    {
        $godaddy_expected_plan = Configuration::get('godaddy.account.plan.name');

        return self::isManagedWordPress() && $godaddy_expected_plan === Configuration::get('mwc.plan_name');
    }

    /**
     * Determines if the site is hosted on Managed WordPress.
     *
     * @since x.y.z
     *
     * @return bool
     */
    public static function isManagedWordPress() : bool
    {
        return (bool) Configuration::get('godaddy.account.uid');
    }

    /**
     * Determines if the site is hosted on MWP and sold by a reseller.
     *
     * @since x.y.z
     *
     * @return bool
     */
    public static function isReseller() : bool
    {
        return self::isManagedWordPress() && (int) self::getResellerId() > 1;
    }

    /**
     * Gets the configured reseller account, if present.
     *
     * 1 means the site is not a reseller site, but sold directly by GoDaddy.
     *
     * @since x.y.z
     *
     * @return int|null
     */
    public static function getResellerId()
    {
        return Configuration::get('godaddy.reseller');
    }

    /**
     * Determines if the site is hosted on MWP and sold by a reseller with support agreement.
     *
     * @since x.y.z
     *
     * @return bool
     */
    public static function isResellerWithSupportAgreement() : bool
    {
        if (! self::isReseller()) {
            return false;
        }

        return ! ArrayHelper::get(self::getResellerSettings(), 'customerSupportOptOut', true);
    }

    /**
     * Gets settings for a reseller account.
     *
     * @since x.y.z
     *
     * @param int $resellerId ID of the reseller account
     * @param array $queryArgs additional data to send in the request to retrieve additional fields, etc.
     * @return array
     */
    private static function getResellerSettings() : array
    {
        try {
            $settings = (new Request())
                ->url(static::getStorefrontSettingsApiUrl())
                ->query([
                    'privateLabelId' => static::getResellerId(),
                    'fields'         => 'customerSupportOptOut',
                ])
                ->send()
                ->getBody();
        } catch (Exception $e) {
            $settings = [];
        }

        return ArrayHelper::wrap($settings);
    }

    /**
     * Gets the Storefront Settings API URL.
     *
     * @since x.y.z
     *
     * @return string
     */
    private static function getStorefrontSettingsApiUrl()
    {
        return StringHelper::trailingSlash(Configuration::get('godaddy.storefront.api.url', '')).'settings';
    }

    /**
     * Determines if the site is hosted on MWP and is using a temporary domain.
     *
     * @since x.y.z
     *
     * @return bool
     */
    public static function isTemporaryDomain() : bool
    {
        $domain = Configuration::get('godaddy.temporary_domain');
        $home_url = function_exists('home_url') ? parse_url(home_url(), PHP_URL_HOST) : '';

        return self::isManagedWordPress() && $domain && StringHelper::trailingSlash($domain) === StringHelper::trailingSlash($home_url);
    }

    /**
     * Determines if the site used the WPNux template on-boarding system.
     *
     * @since x.y.z
     *
     * @return bool
     */
    public static function hasCompletedWPNuxOnboarding() : bool
    {
        return WordPressRepository::hasWordPressInstance() && (bool) get_option('wpnux_imported');
    }
}
