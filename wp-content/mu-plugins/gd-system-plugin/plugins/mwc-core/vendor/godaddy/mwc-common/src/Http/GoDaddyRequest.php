<?php

namespace GoDaddy\WordPress\MWC\Common\Http;

use Exception;
use GoDaddy\WordPress\MWC\Common\Configuration\Configuration;

/**
 * Request handler for performing requests to GoDaddy.
 *
 * This class also wraps a Managed WooCommerce Site Token required by GoDaddy requests.
 *
 * @since x.y.z
 */
class GoDaddyRequest extends Request
{
    /**
     * Managed WooCommerce Site Token.
     *
     * @var string
     */
    public $siteToken;

    /**
     * Class constructor.
     *
     * @since x.y.z
     *
     * @throws Exception
     */
    public function __construct()
    {
        parent::__construct();

        $this->siteToken()->headers(['X-Site-Token' => $this->siteToken]);
    }

    /**
     * Sets the current site API request token.
     *
     * @since x.y.z
     *
     * @param string|null $token
     * @return GoDaddyRequest
     */
    public function siteToken($token = null) : self
    {
        $this->siteToken = $token ?: Configuration::get('godaddy.site.token', 'empty');

        return $this;
    }
}
