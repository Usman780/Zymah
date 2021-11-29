<?php

namespace GoDaddy\WordPress\MWC\Common\Http;

use Exception;
use GoDaddy\WordPress\MWC\Common\Helpers\ArrayHelper;

class Redirect
{
    /** @var object|array The query parameters */
    public $queryParameters;

    /** @var string The path to redirect to */
    public $path;

    /**
     * Class constructor.
     *
     * @param string|null $path
     */
    public function __construct($path = null) {
        if ($path) {
            $this->setPath($path);
        }
    }

    /**
     * Build a valid url string with parameters
     *
     * @return string
     * @throws Exception
     */
    private function buildUrlString() : string
    {
        if (! $this->path) {
            throw new Exception('A valid url was not given for the requested redirect');
        }

        $queryString = ! empty($this->queryParameters) ? '?' . ArrayHelper::query($this->queryParameters) : '';

        return "{$this->path}{$queryString}";
    }

    /**
     * Execute the redirect
     * @NOTE: May need to support external redirects differently here
     *
     * @return void
     * @throws Exception
     */
    public function execute()
    {
        wp_safe_redirect($this->buildUrlString());

        exit;
    }

    /**
     * Set the redirect path
     *
     * @param string $path
     *
     * @return Redirect
     */
    public function setPath(string $path) : Redirect
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Set query parameters.
     *
     * @param array $params
     *
     * @return Redirect
     */
    public function setQueryParameters(array $params) : Redirect
    {
        $this->queryParameters = $params;

        return $this;
    }
}
