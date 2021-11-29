<?php

namespace GoDaddy\WordPress\MWC\Common\Repositories;

use GoDaddy\WordPress\MWC\Common\Configuration\Configuration;
use GoDaddy\WordPress\MWC\Common\Helpers\ArrayHelper;

class WooCommerceRepository
{
    /**
     * Retrieve the current WooCommerce access token.
     *
     * @since x.y.z
     *
     * @return string|null
     */
    public static function getWooCommerceAccessToken()
    {
        $authorization = self::getWooCommerceAuthorization();

        return ArrayHelper::get($authorization, 'access_token');
    }

    /**
     * Retrieve the current WooCommerce Authorization Object.
     *
     * @since x.y.z
     *
     * @return array|null
     */
    public static function getWooCommerceAuthorization()
    {
        if (class_exists('WC_Helper_Options')) {
            return \WC_Helper_Options::get('auth');
        }

        return null;
    }

    /**
     * Checks if the WooCommerce plugin is active.
     *
     * @since x.y.z
     *
     * @return bool
     */
    public static function isWooCommerceActive() : bool
    {
        return ! is_null(Configuration::get('woocommerce.version'));
    }

    /**
     * Checks if the site is connected to WooCommerce.com.
     *
     * @since x.y.z
     *
     * @return bool
     */
    public static function isWooCommerceConnected() : bool
    {
        return self::isWooCommerceActive() && self::getWooCommerceAccessToken();
    }
}
