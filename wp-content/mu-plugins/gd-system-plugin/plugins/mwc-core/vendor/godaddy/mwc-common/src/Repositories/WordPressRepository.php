<?php

namespace GoDaddy\WordPress\MWC\Common\Repositories;

use Exception;
use GoDaddy\WordPress\MWC\Common\Configuration\Configuration;
use GoDaddy\WordPress\MWC\Common\Helpers\ArrayHelper;
use GoDaddy\WordPress\MWC\Common\Helpers\StringHelper;

/**
 * WordPress repository handler.
 *
 * @since x.y.z
 */
class WordPressRepository
{
    /**
     * Gets the plugin's assets URL.
     *
     * @since x.y.z
     *
     * @param string $path optional path
     * @return string URL
     */
    public static function getAssetsUrl(string $path = '') : string
    {
        $config = Configuration::get('mwc.url');

        if (! $config) {
            return '';
        }

        $url = StringHelper::trailingSlash($config);

        return "{$url}assets/{$path}";
    }

    /**
     * Gets the current page.
     *
     * @return string|null
     */
    public static function getCurrentPage()
    {
        return ArrayHelper::get($GLOBALS, 'pagenow');
    }

    /**
     * Gets the WordPress Filesystem instance
     *
     * @since x.y.z
     *
     * @throws Exception
     */
    public static function getFilesystem()
    {
        if (! $wp_filesystem = ArrayHelper::get($GLOBALS, 'wp_filesystem')) {
            throw new Exception('Unable to connect to the WordPress filesystem');
        }

        if (is_a($wp_filesystem, 'WP_Filesystem_Base') && is_wp_error($wp_filesystem->errors) && $wp_filesystem->errors->has_errors()) {
            throw new Exception(sprintf('Unable to connect to the WordPress filesystem. %s', $wp_filesystem->errors->get_error_message()));
        }

        return $wp_filesystem;
    }

    /**
     * Returns the current WP_User.
     * @TODO: Add a proper test once decide on how to approach integration tests
     *
     * @return \WP_User
     */
    public static function getUser() : \WP_User
    {
        return wp_get_current_user();
    }

    /**
     * Gets the current WordPress Version.
     *
     * @since x.y.z
     *
     * @return string|null
     */
    public static function getVersion()
    {
        return Configuration::get('wordpress.version');
    }

    /**
     * Determines that a WordPress instance can be found.
     *
     * @since x.y.z
     *
     * @return bool
     */
    public static function hasWordPressInstance() : bool
    {
        return (bool) Configuration::get('wordpress.absolute_path');
    }

    /**
     * Determines if a given value or values is the current page.
     *
     * // @TODO add strict type casting when min version is PHP 8
     *
     * @param array|string $path path to check
     * @return bool
     */
    public static function isCurrentPage($path) : bool
    {
        return ArrayHelper::contains(ArrayHelper::wrap($path), self::getCurrentPage());
    }

    /**
     * Determines if the current instance is in CLI mode.
     *
     * @since x.y.z
     *
     * @return bool
     */
    public static function isCliMode() : bool
    {
        return 'cli' === Configuration::get('mwc.mode');
    }

    /**
     * Determines whether WordPress is in debug mode.
     *
     * @since x.y.z
     *
     * @return bool
     */
    public static function isDebugMode() : bool
    {
        return (bool) Configuration::get('wordpress.debug');
    }

    /**
     * Determines if the current request is for a WC REST API endpoint.
     *
     * @see \WooCommerce::is_rest_api_request()
     *
     * @since 5.9.0
     *
     * @return bool
     */
    public static function isApiRequest() : bool
    {
        if (! $_SERVER['REQUEST_URI'] || ! function_exists('rest_get_url_prefix')) {
            return false;
        }

        $is_rest_api_request = StringHelper::contains($_SERVER['REQUEST_URI'], StringHelper::trailingSlash(rest_get_url_prefix()));

        /* applies WooCommerce core filter */
        return (bool) apply_filters('woocommerce_is_rest_api_request', $is_rest_api_request);
    }

    /**
     * Requires the absolute path to the WordPress directory.
     *
     * @since x.y.z
     *
     * @throws Exception
     */
    public static function requireWordPressInstance()
    {
        if (! self::hasWordPressInstance()) {
            // @TODO setting to throw an exception for now, may have to be revisited later (or possibly with a less generic exception) {FN 2020-12-18}
            throw new Exception('Unable to find the required WordPress instance');
        }
    }

    /**
     * Initializes and connect the WordPress Filesystem instance.
     *
     * Implementation adapted from {@see delete_plugins()}.
     *
     * @since x.y.z
     *
     * @throws Exception
     */
    public static function requireWordPressFilesystem()
    {
        $base = Configuration::get('wordpress.absolute_path');

        require_once "{$base}wp-admin/includes/file.php";
        require_once "{$base}wp-admin/includes/plugin-install.php";
        require_once "{$base}wp-admin/includes/class-wp-upgrader.php";
        require_once "{$base}wp-admin/includes/plugin.php";

        // we are using an empty string as the value for the $form_post parameter because it is not relevant for our test.
        // If the function needs to show the form then the WordPress Filesystem is not currently configured for our needs.
        // We need to be able to access the filesystem without asking the user for credentials.
        ob_start();
        $credentials = request_filesystem_credentials('');
        ob_end_clean();

        if (false === $credentials || ! WP_Filesystem($credentials)) {
            static::getFilesystem();

            throw new Exception('Unable to connect to the WordPress filesystem');
        }
    }
}
