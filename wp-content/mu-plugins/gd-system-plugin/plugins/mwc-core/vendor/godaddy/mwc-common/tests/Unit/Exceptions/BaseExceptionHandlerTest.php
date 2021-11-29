<?php

namespace GoDaddy\WordPress\MWC\Common\Tests\Unit\Exceptions;

use ErrorException;
use Exception;
use GoDaddy\WordPress\MWC\Common\Configuration\Configuration;
use GoDaddy\WordPress\MWC\Common\Exceptions\BaseExceptionHandler;
use GoDaddy\WordPress\MWC\Common\Loggers\Logger;
use GoDaddy\WordPress\MWC\Common\Tests\WPTestCase;
use ReflectionClass;
use ReflectionException;
use ReflectionMethod;

/**
 * @covers \GoDaddy\WordPress\MWC\Common\Exceptions\BaseExceptionHandler
 */
final class BaseExceptionHandlerTest extends WPTestCase
{
    /** @var BaseExceptionHandler */
    private $handler;

    /** @var string|int stores the current error reporting value from PHP configuration */
    private $errorReporting;

    /**
     * Sets up the test variables.
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->handler = new BaseExceptionHandler('');
        $this->errorReporting = ini_get('error_reporting');
    }

    /**
     * Restores PHP configuration after completing tests.
     */
    public function tearDown(): void
    {
        ini_set('error_reporting', $this->errorReporting);

        parent::tearDown();
    }

    /**
     * Tests that it can get the context.
     *
     * @covers \GoDaddy\WordPress\MWC\Common\Exceptions\BaseExceptionHandler::context()
     * @throws ReflectionException
     */
    public function testCanGetContext()
    {
        $method = new ReflectionMethod($this->handler, 'context');
        $method->setAccessible(true);

        $this->assertIsArray($method->invoke($this->handler));
    }

    /**
     * Tests that it can convert an exception to array.
     *
     * @covers \GoDaddy\WordPress\MWC\Common\Exceptions\BaseExceptionHandler::convertExceptionToArray()
     * @throws ReflectionException
     */
    public function testCanConvertExceptionToArray()
    {
        $method = new ReflectionMethod($this->handler, 'convertExceptionToArray');
        $method->setAccessible(true);

        Configuration::set('mwc.debug', false);
        Configuration::set('mwc.env', 'prod');

        $result = $method->invokeArgs($this->handler, [new Exception()]);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('message', $result);
        $this->assertArrayNotHasKey('exception', $result);
        $this->assertArrayNotHasKey('file', $result);
        $this->assertArrayNotHasKey('line', $result);
        $this->assertArrayNotHasKey('trace', $result);

        Configuration::set('mwc.debug', true);
        Configuration::set('mwc.env', 'testing');

        $result = $method->invokeArgs($this->handler, [new Exception()]);

        $this->assertArrayHasKey('exception', $result);
        $this->assertArrayHasKey('file', $result);
        $this->assertArrayHasKey('line', $result);
        $this->assertArrayHasKey('trace', $result);
    }

    /**
     * Tests that it can get get an exception stack trace.
     *
     * @covers \GoDaddy\WordPress\MWC\Common\Exceptions\BaseExceptionHandler::getExceptionStackTrace()
     * @throws ReflectionException
     */
    public function testCanGetExceptionStackTrace()
    {
        $method = new ReflectionMethod($this->handler, 'getExceptionStackTrace');
        $method->setAccessible(true);

        $this->assertIsArray($method->invokeArgs($this->handler, [new Exception()]));
    }

    /**
     * Tests that it can get get an exception stack trace.
     *
     * @covers \GoDaddy\WordPress\MWC\Common\Exceptions\BaseExceptionHandler::getExceptionMessage()
     * @throws Exception
     */
    public function testCanGetExceptionMessage()
    {
        $this->assertStringContainsString('test', $this->handler->getExceptionMessage(new Exception('test')));
    }

    /**
     * Tests that it can handle an error.
     *
     * @covers \GoDaddy\WordPress\MWC\Common\Exceptions\BaseExceptionHandler::handleError()
     * @throws ErrorException
     */
    public function testCanHandleError()
    {
        ini_set('error_reporting', 0);

        $this->handler->handleError(1, 'test');

        $this->expectException(ErrorException::class);

        ini_set('error_reporting', E_ALL);

        $this->handler->handleError(1, 'test');
    }

    /**
     * Tests that it can handle an exception.
     * @TODO: Create a proper Test
     *
     * @covers \GoDaddy\WordPress\MWC\Common\Exceptions\BaseExceptionHandler::handleException()
     * @throws Exception
     */
    public function testCanHandleException()
    {
        // @TODO ideally we'd want to test that report() is called, but the mock below doesn't agree... {FN 2020-12-24}
        //$mock = $this->getMockBuilder(BaseExceptionHandler::class)->setConstructorArgs(['test'])->getMock();
        //$mock->expects($this->once())->method('report');
        //$mock->handleException(new Exception());

        $this->assertTrue(true);
    }

    /**
     * Tests that it can ignore exceptions.
     *
     * @covers \GoDaddy\WordPress\MWC\Common\Exceptions\BaseExceptionHandler::ignore()
     * @throws ReflectionException
     */
    public function testCanIgnoreExceptions()
    {
        $handler = new ReflectionClass(BaseExceptionHandler::class);

        $method = $handler->getMethod('ignore');
        $method->invokeArgs($this->handler, [Exception::class]);

        $property = $handler->getProperty('dontReport');
        $property->setAccessible(true);

        $this->assertContains(Exception::class, $property->getValue($this->handler));
    }

    /**
     * Tests that it can see if the current is a HTTP response.
     *
     * @covers \GoDaddy\WordPress\MWC\Common\Exceptions\BaseExceptionHandler::isHttpResponse()
     * @throws ReflectionException
     */
    public function testCanSeeIsHttpResponse()
    {
        Configuration::set('mwc.mode', null);

        $method = new ReflectionMethod($this->handler, 'isHttpResponse');
        $method->setAccessible(true);

        $this->assertTrue($method->invoke($this->handler));

        Configuration::set('mwc.mode', 'cli');

        $this->assertFalse($method->invoke($this->handler));
    }

    /**
     * Tests that it can log an exception error.
     * @TODO: Create a proper Test
     *
     * @covers \GoDaddy\WordPress\MWC\Common\Exceptions\BaseExceptionHandler::log()
     */
    public function testCanLog()
    {
        $this->assertTrue(true);
    }

    /**
     * Tests that it can get a Logger instance.
     *
     * @covers \GoDaddy\WordPress\MWC\Common\Exceptions\BaseExceptionHandler::getLogger()
     * @throws ReflectionException
     */
    public function testCanGetLogger()
    {
        $method = new ReflectionMethod($this->handler, 'getLogger');
        $method->setAccessible(true);

        $this->assertInstanceOf(Logger::class, $method->invoke($this->handler));
    }

    /**
     * Tests that it can report an exception.
     * @TODO: Create a proper Test
     *
     * @covers \GoDaddy\WordPress\MWC\Common\Exceptions\BaseExceptionHandler::report()
     * @throws Exception
     */
    public function testCanReport()
    {
        $this->assertTrue(true);
    }

    /**
     * Tests that it can determine whether an exception should be ignored.
     *
     * @covers \GoDaddy\WordPress\MWC\Common\Exceptions\BaseExceptionHandler::shouldIgnore()
     * @throws ReflectionException
     */
    public function testCanDetermineWhetherShouldIgnoreException()
    {
        $handler = new ReflectionClass(BaseExceptionHandler::class);

        $method = $handler->getMethod('shouldIgnore');
        $method->setAccessible(true);

        $property = $handler->getProperty('dontReport');
        $property->setAccessible(true);

        $this->assertFalse($method->invokeArgs($this->handler, [new Exception()]));

        $property->setValue($this->handler, [Exception::class]);

        $this->assertTrue($method->invokeArgs($this->handler, [new Exception()]));
    }
}
