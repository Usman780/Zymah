<?php

namespace GoDaddy\WordPress\MWC\Common\Email\Contracts;

interface SendableContract
{
    /**
     * Sends it.
     */
    public function send();
}
