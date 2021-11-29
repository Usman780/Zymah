<?php

namespace GoDaddy\WordPress\MWC\Core\WooCommerce;

use Exception;
use GoDaddy\WordPress\MWC\Common\Configuration\Configuration;
use GoDaddy\WordPress\MWC\Common\Helpers\ArrayHelper;
use GoDaddy\WordPress\MWC\Common\Helpers\StringHelper;
use GoDaddy\WordPress\MWC\Common\Register\Register;
use GoDaddy\WordPress\MWC\Common\Repositories\ManagedWooCommerceRepository;
use GoDaddy\WordPress\MWC\Common\Repositories\WordPressRepository;

class Overrides
{
    /**
     * Class constructor.
     *
     * @throws Exception
     */
    public function __construct()
    {
        Register::action()
            ->setGroup('plugins_loaded')
            ->setHandler([$this, 'setDefaults'])
            ->setPriority(PHP_INT_MAX)
            ->execute();

        $this->registerFilters();
    }

    /**
     * Registers filters.
     *
     * @since 2.0.0
     *
     * @throws Exception
     */
    private function registerFilters()
    {
        Register::filter()
            ->setGroup('woocommerce_show_admin_notice')
            ->setHandler([$this, 'suppressNotices'])
            ->setPriority(10)
            ->setArgumentsCount(2)
            ->execute();

        Register::filter()
            ->setGroup('woocommerce_helper_suppress_connect_notice')
            ->setHandler([$this, 'suppressConnectNotice'])
            ->setPriority(PHP_INT_MAX)
            ->setArgumentsCount(1)
            ->execute();

        Register::filter()
            ->setGroup('wc_pdf_product_vouchers_admin_hide_low_memory_notice')
            ->setCondition(function () {
                return ManagedWooCommerceRepository::isManagedWordPress();
            })
            ->setHandler([$this, 'hidePdfProductVouchersLowMemoryNotice'])
            ->setPriority(10)
            ->setArgumentsCount(1)
            ->execute();

        Register::filter()
            ->setGroup('wc_pdf_product_vouchers_admin_hide_sucuri_notice')
            ->setCondition(function () {
                return ManagedWooCommerceRepository::isManagedWordPress();
            })
            ->setHandler([$this, 'hidePdfProductVouchersSucuriNotice'])
            ->setPriority(10)
            ->setArgumentsCount(1)
            ->execute();

	    // add the authentication headers necessary for getting packages from the Extensions API
	    Register::filter()
            ->setGroup('http_request_args')
            ->setCondition(function () {
	            return ManagedWooCommerceRepository::isManagedWordPress();
            })
            ->setHandler([$this, 'addExtensionsApiAuthenticationHeaders'])
            ->setPriority(10)
            ->setArgumentsCount(2)
            ->execute();

        // ensure checkout is always HTTPS for temp sites
        Register::filter()
            ->setGroup('pre_option_woocommerce_force_ssl_checkout')
            ->setCondition(function () {
                return ManagedWooCommerceRepository::hasEcommercePlan();
            })
            ->setHandler([$this, 'maybeSetForceSsl'])
            ->setPriority(10)
            ->setArgumentsCount(1)
            ->execute();
    }

    /**
     * Set option defaults for a better experience on the MWP eCommerce plan.
     *
     * @action plugins_loaded - PHP_INT_MAX
     */
    public function setDefaults()
    {
        if (! ManagedWooCommerceRepository::hasEcommercePlan()) {
            return;
        }

        if (class_exists('WC_Admin_Notices') && ! ManagedWooCommerceRepository::hasCompletedWPNuxOnboarding()) {
            \WC_Admin_Notices::remove_notice('install', true);
        }

        if ('no' !== get_option('woocommerce_onboarding_opt_in')) {
            update_option('woocommerce_onboarding_opt_in', 'no');
        }

        if ('yes' !== get_option('woocommerce_task_list_hidden')) {
            update_option('woocommerce_task_list_hidden', 'yes');
        }

        $onboarding_profile = (array) get_option('woocommerce_onboarding_profile', []);

        if (empty($onboarding_profile['completed'])) {
            update_option('woocommerce_onboarding_profile', array_merge($onboarding_profile, ['completed' => true]));
        }
    }

	/**
	 * Adds the authentication headers necessary for getting packages from the Extensions API.
	 *
	 * @internal
	 *
	 * @since 2.0.0
	 *
	 * @param mixed $requestArgs request args
	 * @param string $url request URL
	 *
	 * @return mixed
	 * @throws Exception
	 */
	public function addExtensionsApiAuthenticationHeaders($requestArgs, string $url)
	{
		// target admin extensions API requests only
		if ((is_admin() || WordPressRepository::isApiRequest()) && StringHelper::contains($url, Configuration::get('mwc.extensions.api.url'))) {
			ArrayHelper::set($requestArgs, 'headers.X-Site-Token', Configuration::get('godaddy.site.token', 'empty'));
			ArrayHelper::set($requestArgs, 'headers.X-Account-UID', Configuration::get('godaddy.account.uid', ''));
		}

		return $requestArgs;
	}

    /**
     * Ensures checkout is always HTTPS for temp sites.
     *
     * @internal
     *
     * @since 2.0.0
     *
     * @param mixed $value
     *
     * @return mixed
     */
    public function maybeSetForceSsl($value)
    {
        if (ManagedWooCommerceRepository::isTemporaryDomain()) {
            return 'yes';
        }

        return $value;
    }

    /**
     * Callback for the woocommerce_helper_suppress_connect_notice filter.
     *
     * @internal
     *
     * @since 2.0.0
     *
     * @return bool
     */
    public function suppressConnectNotice()
    {
        return ManagedWooCommerceRepository::hasEcommercePlan();
    }

    /**
     * Callback for the wc_pdf_product_vouchers_admin_hide_low_memory_notice filter.
     *
     * @internal
     *
     * @since 2.0.0
     *
     * @return bool
     */
    public function hidePdfProductVouchersLowMemoryNotice()
    {
        return true;
    }

    /**
     * Callback for the wc_pdf_product_vouchers_admin_hide_sucuri_notice filter.
     *
     * @internal
     *
     * @since 2.0.0
     *
     * @return bool
     */
    public function hidePdfProductVouchersSucuriNotice()
    {
        return true;
    }

    /**
     * Suppress WooCommerce admin notices.
     *
     * @filter woocommerce_show_admin_notice - 10
     *
     * @param  bool   $bool   Boolean value to show/suppress the notice.
     * @param  string $notice The notice name being displayed.
     *
     * @return bool True to show the notice, false to suppress it.
     */
    public function suppressNotices($bool, $notice)
    {

        // Suppress the SSL notice when hosted on MWP on a temp domain.
        if ('no_secure_connection' === $notice && ManagedWooCommerceRepository::isTemporaryDomain()) {
            return false;
        }

        // Suppress the "Install WooCommerce Admin" notice when the Setup Wizard notice is visible.
        if ('wc_admin' === $notice && in_array('install', (array) get_option('woocommerce_admin_notices', []), true)) {
            return false;
        }

        return $bool;
    }
}
