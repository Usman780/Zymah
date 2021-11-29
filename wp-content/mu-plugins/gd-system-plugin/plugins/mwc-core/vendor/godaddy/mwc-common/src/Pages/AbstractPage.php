<?php

namespace GoDaddy\WordPress\MWC\Common\Pages;

use GoDaddy\WordPress\MWC\Common\Pages\Contracts\RenderableContract;
use GoDaddy\WordPress\MWC\Common\Repositories\WordPressRepository;

/**
 * Abstract page class.
 *
 * Represents a base page for all pages to extend from
 *
 * @since x.y.z
 */
abstract class AbstractPage implements RenderableContract
{
    /**
     * Page screen ID.
     *
     * @var string
     */
    protected $screenId;

    /**
     * Page title.
     *
     * @var string
     */
    protected $pageTitle;

    /**
     * AbstractPage constructor.
     *
     * @since x.y.z
     *
     * @param string $screenId
     * @param string $pageTitle
     */
    public function __construct(string $screenId, string $pageTitle)
    {
        $this->screenId = $screenId;
        $this->pageTitle = $pageTitle;

        $this->registerAssets();
    }

    /**
     * Determines if the current page is the page we want to enqueue the registered assets.
     *
     * @since x.y.z
     *
     * @return bool
     */
    protected function shouldEnqueueAssets() : bool
    {
        return WordPressRepository::isCurrentPage('toplevel_page_'.strtolower($this->screenId));
    }

    /**
     * Renders page markup.
     *
     * @since x.y.z
     */
    public function render()
    {
        //@NOTE implement render() method.
    }

    /**
     * Maybe enqueues the necessary assets.
     *
     * @since x.y.z
     */
    public function maybeEnqueueAssets()
    {
        if (! $this->shouldEnqueueAssets()) {
            return;
        }

        $this->enqueueAssets();
    }

    /**
     * Enqueues/loads registered assets.
     *
     * @since x.y.z
     */
    protected function enqueueAssets()
    {
        //@NOTE implement assets loading for the page.
    }

    /**
     * Registers page assets.
     *
     * @since x.y.z
     */
    protected function registerAssets()
    {
        //@NOTE implement assets registration for the page
    }
}
