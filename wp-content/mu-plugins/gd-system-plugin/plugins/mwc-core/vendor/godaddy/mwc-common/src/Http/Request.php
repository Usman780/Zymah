<?php

namespace GoDaddy\WordPress\MWC\Common\Http;

use Exception;
use GoDaddy\WordPress\MWC\Common\Helpers\ArrayHelper;
use GoDaddy\WordPress\MWC\Common\Repositories\ManagedWooCommerceRepository;

/**
 * Request handler.
 *
 * @since x.y.z
 */
class Request
{
    /**
     * Body of the request.
     *
     * @var array
     */
    public $body;

    /**
     * Request headers.
     *
     * @var array
     */
    public $headers;

    /**
     * Request Method.
     *
     * @var string
     */
    public $method;

    /**
     * The query parameters.
     *
     * @var object|array
     */
    public $query;

    /**
     * Should SSL verify.
     *
     * @var bool
     */
    public $sslVerify;

    /**
     * Default timeout in seconds.
     *
     * @var int
     */
    public $timeout;

    /**
     * The url to send the request to.
     *
     * @var string
     */
    public $url;

    /**
     * Class constructor.
     *
     * @since x.y.z
     *
     * @param string|null $url
     * @throws Exception
     */
    public function __construct(string $url = null)
    {
        $this->headers()
            ->setMethod()
            ->sslVerify()
            ->timeout();

        if ($url) {
            $this->url($url);
        }
    }

    /**
     * Sets the body of the request.
     *
     * @since x.y.z
     *
     * @param array $body
     * @return Request
     */
    public function body(array $body) : self
    {
        $this->body = $body;

        return $this;
    }

    /**
     * Builds a valid url string with parameters.
     *
     * @since x.y.z
     *
     * @return string
     * @throws Exception
     */
    protected function buildUrlString() : string
    {
        $queryString = ! empty($this->query) ? '?'.ArrayHelper::query($this->query) : '';

        return $this->url.$queryString;
    }

    /**
     * Sets Request headers.
     *
     * @since x.y.z
     *
     * @param array|null $additionalHeaders
     * @return Request
     * @throws Exception
     */
    public function headers($additionalHeaders = []) : self
    {
        $this->headers = ArrayHelper::combine(['Content-Type' => 'application/json'], $additionalHeaders);

        return $this;
    }

    /**
     * Sets the request method.
     *
     * @since x.y.z
     *
     * @param string $method
     * @return Request
     */
    public function setMethod(string $method = 'get') : self
    {
        if (! ArrayHelper::contains(['GET', 'POST', 'HEAD', 'PUT', 'DELETE', 'TRACE', 'OPTIONS', 'PATCH'], strtoupper($method))) {
            $method = 'get';
        }

        $this->method = strtoupper($method);

        return $this;
    }

    /**
     * Sets query parameters.
     *
     * @since x.y.z
     *
     * @param array $params
     * @return Request
     */
    public function query(array $params) : self
    {
        $this->query = $params;

        return $this;
    }

    /**
     * Sends the request.
     *
     * @since x.y.z
     *
     * @return Response
     * @throws Exception
     */
    public function send() : Response
    {
        $this->validate();

        return new Response(wp_remote_request($this->buildUrlString(), [
            'body'      => $this->body ? json_encode($this->body) : null,
            'headers'   => $this->headers,
            'method'    => $this->method,
            'sslverify' => $this->sslVerify,
            'timeout'   => $this->timeout,
        ]));
    }

    /**
     * Sets SSL verify.
     *
     * @since x.y.z
     *
     * @param bool $default
     * @return Request
     */
    public function sslVerify($default = false) : self
    {
        $this->sslVerify = $default || ManagedWooCommerceRepository::isProductionEnvironment();

        return $this;
    }

    /**
     * Sets the request timeout.
     *
     * @since x.y.z
     *
     * @param int $seconds
     * @return Request
     */
    public function timeout(int $seconds = 30) : self
    {
        $this->timeout = $seconds;

        return $this;
    }

    /**
     * Sets the url of the request.
     *
     * @since x.y.z
     *
     * @param string $url
     * @return Request
     */
    public function url(string $url) : self
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Validates the request.
     *
     * @since x.y.z
     *
     * @throws Exception
     */
    protected function validate()
    {
        if (! $this->url) {
            throw new Exception('You must provide a url for an outgoing request');
        }
    }
}
