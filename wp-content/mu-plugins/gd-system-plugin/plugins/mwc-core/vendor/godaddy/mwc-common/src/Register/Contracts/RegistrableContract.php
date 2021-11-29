<?php

namespace GoDaddy\WordPress\MWC\Common\Register\Contracts;

interface RegistrableContract
{
    /**
     * Sets the registrable type.
     *
     * @since x.y.z
     */
    public function __construct();

    /**
     * Determines how to deregister the registrable object.
     *
     * @since x.y.z
     */
    public function deregister();

    /**
     * Determines how to execute the register.
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
