<?php

namespace GoDaddy\WordPress\MWC\Dashboard\Tests\Unit\API\Controllers;

use Exception;
use GoDaddy\WordPress\MWC\Common\Tests\TestHelpers;
use GoDaddy\WordPress\MWC\Common\Tests\WPTestCase;
use GoDaddy\WordPress\MWC\Dashboard\API\Controllers\Support;
use GoDaddy\WordPress\MWC\Dashboard\Helpers\SupportHelper;
use Mockery;
use ReflectionException;
use WP_Mock;

/**
 * @covers \GoDaddy\WordPress\MWC\Dashboard\API\Controllers\Support
 */
class SupportTest extends WPTestCase
{
    /**
     * Tests the constructor.
     *
     * @covers \GoDaddy\WordPress\MWC\Dashboard\API\Controllers\Support::__construct()
     *
     * @throws ReflectionException
     */
    public function testConstructor()
    {
        $controller = new Support();
        $route = TestHelpers::getInaccessibleProperty($controller, 'route');
        $this->assertSame('support-requests', $route->getValue($controller));
    }

    /**
     * @covers \GoDaddy\WordPress\MWC\Dashboard\API\Controllers\Support::createItem()
     *
     * @throws Exception
     */
    public function testCreateItem()
    {
        $controller = new Support();

        // sanitize_text_field is used by StringHelper::sanitize()
        WP_Mock::userFunction('sanitize_text_field')->with('createDebugUser')->andReturn('');
        WP_Mock::userFunction('sanitize_text_field')->andReturn('sanitized');

        // we don't want to test SupportHelper methods here
        $this->mockStaticMethod(SupportHelper::class, 'getSupportRequestData')->andReturn(['body']);
        $this->mockStaticMethod(SupportHelper::class, 'createSupportRequest')->times(1);

        // mocks the request parameters
        $request = Mockery::mock('WP_REST_Request');
        $request->shouldReceive('get_param')->with('subject')->andReturn('subject');
        $request->shouldReceive('get_param')->with('message')->andReturn('message');
        $request->shouldReceive('get_param')->with('replyTo')->andReturn('replyTo');
        $request->shouldReceive('get_param')->with('reason')->andReturn('reason');
        $request->shouldReceive('get_param')->with('plugin')->andReturn('plugin');
        $request->shouldReceive('get_param')->with('createDebugUser')->andReturn('createDebugUser');

        $response = (object) ['response' => true];

        WP_Mock::userFunction('rest_ensure_response')
            ->with(Mockery::any())
            ->andReturn($response);

        $this->assertSame($response, $controller->createItem($request));
    }

    /**
     * Tests the getItemSchema() method is returning the correct required and optional arguments.
     *
     * @covers \GoDaddy\WordPress\MWC\Dashboard\API\Controllers\Support::getItemSchema()
     *
     * @param string $arg
     * @param bool $required
     *
     * @dataProvider dataProviderGetItemSchema
     */
    public function testGetItemSchema(string $arg, bool $required)
    {
        WP_Mock::userFunction('__');

        $controller = new Support();

        $args = $controller->getItemSchema();

        $this->assertIsArray($args);
        $this->assertEquals($required, $args[$arg]['required'] ?? false);
    }

    /**
     * @see testGetItemSchema
     */
    public function dataProviderGetItemSchema() : array
    {
        return [
            ['replyTo', true],
            ['plugin', false],
            ['subject', true],
            ['message', true],
            ['reason', true],
            ['createDebugUser', false],
        ];
    }

    /**
     * @covers \GoDaddy\WordPress\MWC\Dashboard\API\Controllers\Support::registerRoutes()
     */
    public function testRegisterRoutes()
    {
        WP_Mock::userFunction('__');

        WP_Mock::userFunction('register_rest_route', ['times' => 1])
            ->with('godaddy/mwc/v1', '/support-requests', Mockery::any());

        (new Support())->registerRoutes();

        $this->assertConditionsMet();
    }
}
