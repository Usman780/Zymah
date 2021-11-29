<?php

namespace GoDaddy\WordPress\MWC\Common\Pages;

use Exception;
use GoDaddy\WordPress\MWC\Common\Register\Register;

/**
 * Abstract WordPress Admin page class.
 *
 * Represents a base page for all WordPress admin pages to extend from.
 *
 * @since x.y.z
 */
abstract class WordPressAdminPage extends AbstractPage
{
    /**
     * The minimum capability to have access to this menu item.
     *
     * @since x.y.z
     *
     * @var string
     */
    protected $capability;

    /**
     * The menu title.
     *
     * @since x.y.z
     *
     * @var string
     */
    protected $menuTitle;

    /**
     * The parent menu slug.
     *
     * @since x.y.z
     *
     * @var string
     */
    protected $parentMenuSlug;

    /**
     * WordPressAdminPage constructor.
     *
     * @param string $screenId
     * @param string $pageTitle
     */
    public function __construct(string $screenId, string $pageTitle)
    {
        parent::__construct($screenId, $pageTitle);

        $this->registerMenuItem();
    }

    /**
     * Adds the menu page.
     *
     * @since x.y.z
     *
     * @internal
     *
     * @see https://developer.wordpress.org/reference/functions/add_submenu_page/
     *
     * @return self
     */
    public function addMenuItem() : self
    {
        if (empty($this->parentMenuSlug)) {
            // TODO: log an error using a wrapper for WC_Logger {WV 2021-02-15}
            // throw new Exception('The page parent menu slug property should be defined.');
        }

        add_submenu_page(
            $this->parentMenuSlug,
            $this->pageTitle,
            $this->menuTitle ?? $this->pageTitle,
            $this->capability,
            $this->screenId,
            [$this, 'render']
        );

        return $this;
    }

    /**
     * Registers the menu page.
     *
     * @since x.y.z
     *
     * @return self
     */
    protected function registerMenuItem() : self
    {
        try {
            if ($this->shouldAddMenuItem()) {
                Register::action()
                    ->setGroup('admin_menu')
                    ->setHandler([$this, 'addMenuItem'])
                    ->execute();
            }
        } catch (Exception $ex) {
            // TODO: log an error using a wrapper for WC_Logger {WV 2021-02-15}
            // throw new Exception('Cannot register the menu item: '.$ex->getMessage());
        }

        return $this;
    }

    /**
     * Checks if the menu item for this page should be added/registered or not.
     *
     * @since x.y.z
     *
     * @return bool
     */
    protected function shouldAddMenuItem() : bool
    {
        return true;
    }

    /**
     * Registers the page assets.
     *
     * @since x.y.z
     *
     * @return self
     */
    protected function registerAssets() : self
    {
        try {
            Register::action()
                ->setGroup('admin_enqueue_scripts')
                ->setHandler([$this, 'maybeEnqueueAssets'])
                ->execute();
        } catch (Exception $ex) {
            // TODO: log an error using a wrapper for WC_Logger {WV 2021-02-15}
            // throw new Exception('Cannot register assets: '.$ex->getMessage());
        }

        return $this;
    }
}
