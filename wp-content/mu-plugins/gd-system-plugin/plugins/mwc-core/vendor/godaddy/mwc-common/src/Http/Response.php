<?php

namespace GoDaddy\WordPress\MWC\Common\Http;

use Exception;
use GoDaddy\WordPress\MWC\Common\Helpers\ArrayHelper;

class Response
{
    /**
     * The body of the response.
     *
     * @var array
     */
    public $body;

    /**
     * The status code.
     *
     * @var int
     */
    public $status;

    /**
     * The response object.
     *
     * @var object|array
     */
    public $response;

    /**
     * Class constructor.
     *
     * @since x.y.z
     *
     * @param $response
     */
    public function __construct($response = null)
    {
        if ($response) {
            $this->setInitialBody($response)
                ->setInitialResponse($response)
                ->setInitialStatus($response);
        }
    }

    /**
     * Sets the response body.
     *
     * @since x.y.z
     *
     * @param array $parameters
     * @return Response
     */
    public function body(array $parameters) : self
    {
        $this->body = $parameters;

        return $this;
    }

    /**
     * Sets the response as an error response.
     *
     * @see https://developer.wordpress.org/reference/functions/wp_send_json_error/#source
     *
     * @since x.y.z
     *
     * @param array|string $errors
     * @param int|null $responseCode
     * @return Response
     * @throws Exception
     */
    public function error($errors, $responseCode = null) : self
    {
        if ($responseCode) {
            $this->status($responseCode);
        }

        foreach (ArrayHelper::wrap($errors) as $error) {
            $this->body(ArrayHelper::combine(ArrayHelper::wrap($this->body), [
                'code' => $responseCode, 'message' => $error,
            ]));
        }

        $this->body(ArrayHelper::combine(ArrayHelper::wrap($this->body), ['success' => false]));

        return $this;
    }

    /**
     * Gets the response body.
     *
     * @since x.y.z
     *
     * @return array|null
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * Gets the error message.
     *
     * @NOTE: Will need to expand to handle non-wp/wc responses in the future when needed
     *
     * @since x.y.z
     *
     * @return string|null
     */
    public function getErrorMessage()
    {
        return $this->isError() ? $this->response->get_error_message() : null;
    }

    /**
     * Gets the response status code.
     *
     * @since x.y.z
     *
     * @return int|null
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Determines if the response is an error response.
     *
     * @NOTE: Will need to expand to handle non-wp/wc responses in the future when needed
     *
     * @since x.y.z
     *
     * @param array|null $response
     * @return bool
     */
    public function isError($response = null) : bool
    {
        return (bool) is_wp_error($response ?: $this->response);
    }

    /**
     * Determines if the response is a success response.
     *
     * @NOTE: Will need to expand to handle non-wp/wc responses in the future when needed
     *
     * @since x.y.z
     *
     * @return bool
     */
    public function isSuccess() : bool
    {
        return ! $this->isError();
    }

    /**
     * Sends a response.
     *
     * @NOTE: This will send a standard WP or API response back from the calling entity
     *
     * @since x.y.z
     *
     * @param bool $killAfter
     */
    public function send($killAfter = true)
    {
        wp_send_json($this->getBody(), $this->getStatus());

        if ($killAfter) {
            exit;
        }
    }

    /**
     * Sets the initial response body.
     *
     * @since x.y.z
     *
     * @param $originalResponse
     * @return Response
     */
    private function setInitialBody($originalResponse) : self
    {
        // @TODO: Are we sure we don't want to do anything when there is a wp error?
        if ($this->isError($originalResponse)) {
            $this->body = [];

            return $this;
        }

        $this->body = json_decode(ArrayHelper::get($originalResponse, 'body'), true);

        return $this;
    }

    /**
     * Set the initial response object.
     *
     * @NOTE: This is separated because we may want special handling of responses in the platform later
     *
     * @since x.y.z
     *
     * @param $originalResponse
     * @return Response
     */
    private function setInitialResponse($originalResponse) : self
    {
        $this->response = $originalResponse;

        return $this;
    }

    /**
     * Set the initial response code.
     *
     * @TODO: Consider throwing an exception or default code here if there is no code as something likely went wrong
     * @TODO: This is a good place to log a sentry error or some sort of broader error reporting
     *
     * @since x.y.z
     *
     * @param $originalResponse
     * @return Response
     */
    public function setInitialStatus($originalResponse) : self
    {
        if (! $this->isError($originalResponse)) {
            $this->status = wp_remote_retrieve_response_code($originalResponse);
        }

        return $this;
    }

    /**
     * Sets the response status code.
     *
     * @since x.y.z
     *
     * @param int|null $code
     * @return Response
     */
    public function status(int $code = 200) : self
    {
        $this->status = $code;

        return $this;
    }

    /**
     * Sets the response as a successful response.
     *
     * @NOTE WordPress just sets a success key. This is better to standardize ourselves so its not WordPress-dependent.
     * @link https://developer.wordpress.org/reference/functions/wp_send_json_success/#source
     *
     * @since x.y.z
     *
     * @param int|null $code
     * @return Response
     * @throws Exception
     */
    public function success($code = null) : self
    {
        if ($code) {
            $this->status($code);
        }

        $this->body(ArrayHelper::combine(ArrayHelper::wrap($this->body), ['success' => true]));

        return $this;
    }
}
