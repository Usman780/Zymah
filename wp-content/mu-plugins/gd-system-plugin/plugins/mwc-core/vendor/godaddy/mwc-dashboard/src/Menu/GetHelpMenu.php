<?php

namespace GoDaddy\WordPress\MWC\Dashboard\Menu;

use Exception;
use GoDaddy\WordPress\MWC\Common\Configuration\Configuration;
use GoDaddy\WordPress\MWC\Common\Enqueue\Enqueue;
use GoDaddy\WordPress\MWC\Common\Pages\WordPressAdminPage;
use GoDaddy\WordPress\MWC\Common\Register\Register;
use GoDaddy\WordPress\MWC\Common\Repositories\ManagedWooCommerceRepository;
use GoDaddy\WordPress\MWC\Dashboard\Pages\GetHelpPage;

/**
 * Class GetHelpMenu handler.
 *
 * @since x.y.z
 */
class GetHelpMenu
{
    /**
     * The minimum capability to load the menu items.
     *
     * @since x.y.z
     *
     * @var string
     */
    const CAPABILITY = 'manage_options';

    /**
     * The slug for the top-level menu item.
     *
     * @since x.y.z
     *
     * @var string
     */
    const MENU_SLUG = 'mwc-get-help';

    /**
     * The page to associate with the menu item.
     *
     * @since x.y.z
     *
     * @var WordPressAdminPage
     */
    protected $page;

    /**
     * The app handle prefix for enqueing assets.
     *
     * @var string
     */
    protected $appHandle;

    /**
     * GetHelpMenu constructor.
     *
     * @since x.y.z
     *
     * @throws Exception
     */
    public function __construct()
    {
        $this->addAdminActions();

        $this->page = (new GetHelpPage());
        $this->appHandle = 'mwcDashboardClient';
    }

    /**
     * Add WordPress actions to add menu item as well enqueue it's assets.
     *
     * @since x.y.z
     *
     * @throws Exception
     */
    protected function addAdminActions()
    {
        // add main menu item
        Register::action()
            ->setGroup('admin_menu')
            ->setHandler([$this, 'addMenuItem'])
            ->execute();

        // enqueue the JavaScript assets
        Register::action()
            ->setGroup('admin_enqueue_scripts')
            ->setHandler([$this, 'enqueueAdminJS'])
            ->execute();

        // enqueue the style assets
        Register::action()
            ->setGroup('admin_enqueue_scripts')
            ->setHandler([$this, 'enqueueAdminStyles'])
            ->execute();
    }

    /**
     * Enqueues menu style assets.
     *
     * @since x.y.z
     *
     * @throws Exception
     */
    public function enqueueAdminStyles()
    {
        Enqueue::style()
            ->setHandle("{$this->appHandle}-admin")
            ->setSource(Configuration::get('mwc_dashboard.assets.admin.url'))
            ->execute();
    }

    /**
     * Enqueues admin JavaScript assets.
     *
     * @since x.y.z
     *
     * @throws Exception
     */
    public function enqueueAdminJS()
    {
        $adminRelativeUrl = untrailingslashit(str_replace(site_url(), '', admin_url()));

        try {
            Enqueue::script()
                ->setHandle("{$this->appHandle}-runtime")
                ->setSource(Configuration::get('mwc_dashboard.client.runtime.url'))
                ->setDeferred(true)
                ->execute();

            Enqueue::script()
                ->setHandle("{$this->appHandle}-vendors")
                ->setSource(Configuration::get('mwc_dashboard.client.vendors.url'))
                ->setDeferred(true)
                ->execute();

            Enqueue::script()
                ->setHandle("{$this->appHandle}-menu")
                ->setSource(Configuration::get('mwc_dashboard.client.menu.url'))
                ->setDeferred(true)
                ->attachInlineScriptObject('MWCDashboardAPIParams')
                ->attachInlineScriptVariables(
                    [
                        'root' => esc_url_raw(rest_url()),
                        'nonce' => wp_create_nonce('wp_rest'),
                        'adminRelativeUrl' => esc_url_raw($adminRelativeUrl),
                    ]
                )
                ->execute();
        } catch (Exception $exception) {
            throw new Exception('Cannot enqueue script: '.$exception->getMessage());
        }
    }

    /**
     * Adds parent menu item.
     *
     * @since x.y.z
     */
    public function addMenuItem()
    {
        $pageTitle = __('Get Help', 'mwc-dashboard');
        $pageIcon = 'dashicons-sos';
        $iconFilePath = null;

        try {
            $iconFilePath = Configuration::get('mwc_dashboard.assets.go_icon.path');
        } catch (Exception $ex) {
            // ignore the icon and use the default SOS icon
        }

        if ($iconFilePath && is_readable($iconFilePath) && ! ManagedWooCommerceRepository::isReseller()) {
            $pageIcon = 'data:image/svg+xml;base64,'.base64_encode(file_get_contents($iconFilePath));
        }

        add_menu_page(
            $pageTitle,
            $pageTitle.'<div id="mwc-dashboard-main-menu-item"></div>',
            self::CAPABILITY,
            self::MENU_SLUG,
            '__return_empty_string', // TODO: update later to point to the actual page render method. NM {2020-12-29}
            $pageIcon,
            1
        );
    }
}
