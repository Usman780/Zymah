<?php

namespace GoDaddy\WordPress\MWC\Common\Enqueue\Types;

use Exception;
use GoDaddy\WordPress\MWC\Common\Enqueue\Contracts\EnqueuableContract;
use GoDaddy\WordPress\MWC\Common\Enqueue\Enqueue;

final class EnqueueStyle extends Enqueue implements EnqueuableContract
{
    /** @var string context the stylesheet applies to */
    protected $media = 'all';

    /**
     * EnqueueStyle constructor.
     *
     * @since x.y.z
     */
    public function __construct()
    {
        $this->setType('style');
    }

    /**
     * Sets the media context.
     *
     * @since x.y.z
     *
     * @param string $media the media type the stylesheet applies to
     * @return self
     */
    public function setMedia(string $media) : self
    {
        $this->media = $media;

        return $this;
    }

    /**
     * Loads the stylesheet in WordPress.
     *
     * @since x.y.z
     *
     * @throws Exception
     */
    public function execute()
    {
        $this->validate();
        $this->register();
        $this->enqueue();
    }

    /**
     * Registers the asset in WordPress.
     *
     * @since x.y.z
     */
    private function register()
    {
        wp_register_style(
            $this->handle,
            $this->source,
            $this->dependencies,
            $this->version,
            $this->media
        );
    }

    /**
     * Enqueues the stylesheet in WordPress.
     *
     * @since x.y.z
     */
    private function enqueue()
    {
        if (! $this->shouldEnqueue()) {
            return;
        }

        wp_enqueue_style($this->handle);
    }

    /**
     * Validates the current instance.
     *
     * @since x.y.z
     *
     * @throws Exception
     */
    public function validate()
    {
        if (! $this->handle) {
            throw new Exception('You must provide a handle name for the stylesheet to be enqueued.');
        }

        if (! $this->source) {
            throw new Exception("You must provide a URL to enqueue the stylesheet `{$this->handle}`.");
        }

        if (! function_exists('wp_register_style')) {
            throw new Exception("Cannot register the stylesheet `{$this->handle}`: the function `wp_register_style()` does not exist.");
        }

        if (! function_exists('wp_enqueue_style')) {
            throw new Exception("Cannot enqueue the stylesheet `{$this->handle}`: the function `wp_enqueue_style()` does not exist.");
        }
    }
}
