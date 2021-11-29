<?php

namespace GoDaddy\WordPress\MWC\Dashboard\Tests\Unit\Pages;

use GoDaddy\WordPress\MWC\Common\Configuration\Configuration;
use GoDaddy\WordPress\MWC\Common\Helpers\StringHelper;
use GoDaddy\WordPress\MWC\Common\Tests\TestHelpers;
use GoDaddy\WordPress\MWC\Common\Tests\WPTestCase;
use GoDaddy\WordPress\MWC\Dashboard\Pages\GetHelpPage;
use GoDaddy\WordPress\MWC\Dashboard\Tests\TestHelpers as DashboardTestHelpers;
use ReflectionException;
use WP_Mock;

/**
 * @covers \GoDaddy\WordPress\MWC\Dashboard\Pages\GetHelpPage
 */
final class GetHelpPageTest extends WPTestCase
{
    public function setUp() : void
    {
        parent::setUp();

        // mock WordPress functions
        WP_Mock::userFunction('remove_action');
        WP_Mock::userFunction('admin_enqueue_scripts');
        WP_Mock::userFunction('__')->withArgs(['Get Help', 'mwc-dashboard'])->andReturn('Get Help');

        DashboardTestHelpers::mockRegisterActionCalls();
    }

    /**
     * @covers \GoDaddy\WordPress\MWC\Dashboard\Pages\GetHelpPage::__construct
     * @throws ReflectionException
     */
    public function testConstructor()
    {
        Configuration::set('mwc_dashboard.client.source.url', 'test-app-source-url');

        $properties = [
            'screenId' => 'mwc-get-help',
            'pageTitle' => 'Get Help',
            'parentMenuSlug' => 'mwc-get-help',
            'capability' => 'manage_options',
            'appHandle' => 'mwcDashboardClient',
            'appSource' => 'test-app-source-url',
            'divId' => 'mwc-dashboard',
        ];

        $dashboard = new GetHelpPage();

        foreach ($properties as $propertyName => $expectedValue) {
            $property = TestHelpers::getInaccessibleProperty(GetHelpPage::class, $propertyName);

            $this->assertSame($expectedValue, $property->getValue($dashboard));
        }
    }

    /**
     * @covers \GoDaddy\WordPress\MWC\Dashboard\Pages\GetHelpPage::isGetHelpPage
     * @covers \GoDaddy\WordPress\MWC\Dashboard\Pages\GetHelpPage::shouldEnqueueAssets
     * @throws ReflectionException
     */
    public function testIsGetHelpPage()
    {
        $isGetHelpPageMethod = TestHelpers::getInaccessibleMethod(GetHelpPage::class, 'isGetHelpPage');
        $dashboard = new GetHelpPage();

        $this->mockDashboardScreen();
        $this->assertTrue($isGetHelpPageMethod->invoke($dashboard));

        $this->mockNonDashboardScreen();
        $this->assertFalse($isGetHelpPageMethod->invoke($dashboard));
    }

    /**
     * @covers \GoDaddy\WordPress\MWC\Dashboard\Pages\GetHelpPage::injectBeforeNotices
     * @covers \GoDaddy\WordPress\MWC\Dashboard\Pages\GetHelpPage::injectAfterNotices
     */
    public function testHideNoticesWrapperMethods()
    {
        $dashboard = new GetHelpPage();

        // mock current screen is Dashboard
        $this->mockDashboardScreen();

        ob_start();
        $dashboard->injectBeforeNotices();
        $this->assertStringContainsString('class="skyverge-dashboard-hidden"', ob_get_clean());

        ob_start();
        $dashboard->injectAfterNotices();
        $this->assertEquals('</div>', ob_get_clean());

        // mock current screen IS NOT Dashboard
        $this->mockNonDashboardScreen();

        ob_start();
        $dashboard->injectBeforeNotices();
        $this->assertEmpty(ob_get_clean());

        ob_start();
        $dashboard->injectAfterNotices();
        $this->assertEmpty(ob_get_clean());
    }

    /**
     * @covers \GoDaddy\WordPress\MWC\Dashboard\Pages\GetHelpPage::addMenuItem
     */
    public function testAddMenuItem()
    {
        $dashboard = new GetHelpPage();

        WP_Mock::userFunction('add_submenu_page', [
            'times' => 1,
            'args' => [
                'mwc-get-help',
                'Get Help',
                'Get Help<div id="mwc-dashboard-menu-item"></div>',
                'manage_options',
                'mwc-get-help',
                [$dashboard, 'render'],
            ],
        ]);

        $this->expectNotToPerformAssertions();

        $dashboard->addMenuItem();
    }

    private function mockDashboardScreen()
    {
        global $current_screen;

        $current_screen = $this->getMockScreen('mwc-get-help');
    }

    private function mockNonDashboardScreen()
    {
        global $current_screen;

        $current_screen = $this->getMockScreen('different-screen');
    }

    private function getMockScreen(string $screenId)
    {
        return new class($screenId) {
            public $id;

            public function __construct($screenId)
            {
                $this->id = 'toplevel_page_'.$screenId;
            }
        };
    }
}
