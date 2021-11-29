<?php

namespace GoDaddy\WordPress\MWC\Common\Exceptions;

use ErrorException;
use Exception;
use GoDaddy\WordPress\MWC\Common\Configuration\Configuration;
use GoDaddy\WordPress\MWC\Common\Helpers\ArrayHelper;
use GoDaddy\WordPress\MWC\Common\Loggers\Logger;
use GoDaddy\WordPress\MWC\Common\Repositories\ManagedWooCommerceRepository;
use GoDaddy\WordPress\MWC\Common\Repositories\WordPressRepository;
use Psr\Log\LoggerInterface;
use Throwable;

/**
 * Base exceptions handler.
 *
 * @since x.y.z
 */
class BaseExceptionHandler extends Exception
{
    /** @var int exception code */
    protected $code = 500;

    /** @var string exception level */
    protected $level = 'error';

    /**
     * Array of exception names to not report.
     *
     * @NOTE The exceptions stored in this property are those that should be ignored when thrown.
     * These may be items we want to raise and trigger some special handling, but don't want it to permeate to a full exception.
     * E.g. We want to send specific analytics information to an internal aggregator or log a report don't want to raise a full exception.
     * A more simplistic example is that we no longer care about a particular exception but can not confidently remove it from the code without breaking backwards compatibility.
     *
     * @var array
     */
    protected $dontReport = [];

    /**
     * Constructor.
     *
     * @since x.y.z
     *
     * @param string $message exception message
     */
    public function __construct(string $message)
    {
        set_error_handler([$this, 'handleError']);
        set_exception_handler([$this, 'handleException']);

        /*
         * All exceptions extending this base class should have their own error reporting workflow.
         * Exceptions not extending will function as normal.
         */
        if ('testing' !== ManagedWooCommerceRepository::getEnvironment()) {
            ini_set('display_errors', 'Off');
        }

        parent::__construct($message, $this->code);
    }

    /**
     * Adds an exception callback.
     *
     * @NOTE Allow exceptions to define a callback so that they may determine specific actions to take place following an exception of a certain type.
     *
     * @since x.y.z
     *
     * @param Throwable $exception
     */
    protected function callback(Throwable $exception)
    {
    }

    /**
     * Gets the default context to be included with the exception.
     *
     * @NOTE This allows us to ensure certain context is always included for exceptions or reporting.  Keep in mind that an exception inheriting this class may override this context with its over method.
     *
     * @since x.y.z
     *
     * @return array
     */
    protected function context() : array
    {
        try {
            return [
                'account'  => Configuration::get('godaddy.account.uid'),
                'cdn'      => Configuration::get('godaddy.cdn'),
                'site_url' => Configuration::get('mwc.url'),
                'versions' => [
                    'mwc'         => Configuration::get('mwc.version'),
                    'woocommerce' => Configuration::get('woocommerce.version'),
                    'wordpress'   => Configuration::get('wordpress.version'),
                ],
            ];
        } catch (Throwable $e) {
            return [];
        }
    }

    /**
     * Convert the given exception to an array.
     *
     * @NOTE This is useful if we want to deliver a json response which would be useful for rendering to the end user if we choose to split rendering and reporting on a given exception type or move to do that across the board in this base class.
     *
     * @since x.y.z
     *
     * @param Throwable $exception
     * @return array
     * @throws Exception
     */
    protected function convertExceptionToArray(Throwable $exception) : array
    {
        if (! ManagedWooCommerceRepository::isProductionEnvironment() || Configuration::get('mwc.debug')) {
            return [
                'message'   => $this->getExceptionMessage($exception),
                'exception' => get_class($exception),
                'file'      => $exception->getFile(),
                'line'      => $exception->getLine(),
                'trace'     => $this->getExceptionStackTrace($exception),
            ];
        }

        return [
            'message' => $this->isHttpResponse() ? $this->getExceptionMessage($exception) : 'Server Error',
        ];
    }

    /**
     * Gets the stack trace for an exception.
     *
     * @NOTE We want to remove the args from each stack trace entry to keep things condensed and protect any sensitive information.
     *
     * @since x.y.z
     *
     * @param Throwable $exception
     * @return array stack trace
     */
    protected function getExceptionStackTrace(Throwable $exception) : array
    {
        $stack = [];

        foreach ($exception->getTrace() as $trace) {
            $stack[] = ArrayHelper::except($trace, 'args');
        }

        return $stack;
    }

    /**
     * Gets the exception message.
     *
     * Allow Exceptions to overwrite the message which will be displayed.
     *
     * @since x.y.z
     *
     * @param Throwable $exception
     * @return string exception message
     * @throws Exception
     */
    public function getExceptionMessage(Throwable $exception) : string
    {
        return $exception->getMessage();
    }

    /**
     * Handles errors.
     *
     * Converts PHP errors to {@see ErrorException} instances.
     * @NOTE Errors are handled differently in PHP 7+ so we should convert them into an exception instance then handle via the normal workflow.
     *
     * @since x.y.z
     *
     * @param int $level
     * @param string $message
     * @param string $file
     * @param int $line
     * @throws ErrorException
     */
    public function handleError(int $level, string $message, string $file = '', int $line = 0)
    {
        if (error_reporting() & $level) {
            throw new ErrorException($message, 0, $level, $file, $line);
        }
    }

    /**
     * Handles the actual exceptions.
     *
     * @NOTE If we later want to handle the actual exception and what is reported/rendered to the end user then this is the place to do it.
     * E.g. We could allow a different "rendered message" from the more detailed exception message.
     *
     * @since x.y.z
     *
     * @param Throwable $exception
     * @throws Exception
     */
    public function handleException(Throwable $exception)
    {
        $this->report($exception);
    }

    /**
     * Ignores an exception.
     *
     * Set a specific exception to not be reported.
     *
     * @since x.y.z
     *
     * @param string $class exception class
     * @return self
     */
    public function ignore(string $class) : self
    {
        $this->dontReport[] = $class;

        return $this;
    }

    /**
     * Determines if the current is a HTTP response.
     *
     * @NOTE When this is not an HTTP response certain information will be hidden.
     * E.g. In CLI mode we do not want to ever display the stack trace etc unless we are specifically in debug mode.
     *
     * @since x.y.z
     *
     * @return bool
     */
    protected function isHttpResponse() : bool
    {
        return ! WordPressRepository::isCliMode();
    }

    /**
     * Logs the exception using {@see LoggerInterface}.
     *
     * @since x.y.z
     *
     * @param Throwable $exception the exception
     * @param string $level exception level
     * @throws Exception
     */
    private function log(Throwable $exception, string $level = 'error')
    {
        try {
            $this->getLogger()->log(
                $level,
                $this->getExceptionMessage($exception),
                ArrayHelper::combine($this->context(), ['exception' => $exception])
            );
        } catch (Exception $failed) {
            throw $failed;
        }
    }

    /**
     * Gets a Logger instance.
     *
     * Classes extending this handler can use alternative loggers, if desired.
     *
     * @since x.y.z
     *
     * @return Logger
     */
    protected function getLogger() : Logger
    {
        return new Logger();
    }

    /**
     * Reports an exception.
     *
     * @since x.y.z
     *
     * @param Throwable $exception
     * @throws Exception
     */
    public function report(Throwable $exception)
    {
        if ($this->shouldIgnore($exception)) {
            return;
        }

        // perform the callback defined by the exception
        $this->callback($exception);

        // log the actual exception
        $this->log($exception, $this->level);
    }

    /**
     * Determines if the exception should be ignored.
     *
     * @since x.y.z
     *
     * @param Throwable $exception
     * @return bool
     */
    protected function shouldIgnore(Throwable $exception) : bool
    {
        return ArrayHelper::contains($this->dontReport, get_class($exception));
    }
}
