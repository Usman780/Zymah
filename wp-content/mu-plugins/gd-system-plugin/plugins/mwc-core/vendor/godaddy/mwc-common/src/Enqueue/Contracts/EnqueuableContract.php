<?php

namespace GoDaddy\WordPress\MWC\Common\Enqueue\Contracts;

interface EnqueuableContract
{
    /**
     * Sets the enqueue type.
     *
     * @since x.y.z
     */
    public function __construct();

    /**
     * Registers and enqueues the asset in WordPress.
     *
     * @since x.y.z
     */
    public function execute();

    /**
     * Validates the current instance settings.
     *
     * @since x.y.z
     */
    public function validate();
}
