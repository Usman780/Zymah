<?php

namespace GoDaddy\WordPress\MWC\Dashboard\Tests\Unit\Pages;

use GoDaddy\WordPress\MWC\Common\Cache\Cache;
use GoDaddy\WordPress\MWC\Common\Configuration\Configuration;
use GoDaddy\WordPress\MWC\Common\Extensions\Types\PluginExtension;
use GoDaddy\WordPress\MWC\Common\Helpers\ArrayHelper;
use GoDaddy\WordPress\MWC\Common\Helpers\StringHelper;
use GoDaddy\WordPress\MWC\Common\Repositories\ManagedWooCommerceRepository;
use GoDaddy\WordPress\MWC\Common\Tests\TestHelpers as CommonTestHelpers;
use GoDaddy\WordPress\MWC\Common\Tests\WPTestCase;
use GoDaddy\WordPress\MWC\Dashboard\Pages\WooCommerceExtensionsPage;
use GoDaddy\WordPress\MWC\Dashboard\Tests\TestHelpers;
use Patchwork;
use WP_Mock;

/**
 * @covers \GoDaddy\WordPress\MWC\Dashboard\Pages\WooCommerceExtensionsPage
 */
final class WooCommerceExtensionsPageTest extends WPTestCase
{
    /**
     * Set up function.
     */
    public function setUp() : void
    {
        parent::setUp();

        WP_Mock::userFunction('__')->withArgs(['WooCommerce extensions', 'mwc-dashboard'])->andReturnArg(0);
        WP_Mock::userFunction('__')->withArgs(['Extensions', 'mwc-dashboard'])->andReturnArg(0);
    }

    /**
     * @covers \GoDaddy\WordPress\MWC\Dashboard\Pages\WooCommerceExtensionsPage::__construct
     *
     * @throws ReflectionException
     */
    public function testConstructor()
    {
        TestHelpers::mockRegisterActionCalls();
        TestHelpers::mockRegisterFilterCalls();

        Configuration::set('mwc_extensions.client.source.url', 'test-app-source-url');

        $page = new WooCommerceExtensionsPage();

        $properties = [
            'screenId' => 'wc-addons',
            'pageTitle' => 'WooCommerce extensions',
            'parentMenuSlug' => 'woocommerce',
            'capability' => 'manage_woocommerce',
            'appHandle' => 'mwcDashboardClient',
            'appSource' => 'test-app-source-url',
            'divId' => 'mwc-extensions',
        ];

        foreach ($properties as $propertyName => $expectedValue) {
            $property = CommonTestHelpers::getInaccessibleProperty(WooCommerceExtensionsPage::class, $propertyName);

            $this->assertSame($expectedValue, $property->getValue($page));
        }
    }

    /**
     * @covers \GoDaddy\WordPress\MWC\Dashboard\Pages\WooCommerceExtensionsPage::maybeClearUpdatesCacheCount
     *
     * @throws ReflectionException
     *
     * @dataProvider provideClearUpdatesCountCacheData
     */
    public function testCanClearUpdatesCountCache(int $times, string $tab)
    {
        TestHelpers::mockRegisterActionCalls();
        TestHelpers::mockRegisterFilterCalls();

        WP_Mock::userFunction('delete_transient', '_woocommerce_helper_updates_count')->times($times);

        Patchwork\redefine(WooCommerceExtensionsPage::class.'::getCurrentTab', Patchwork\always($tab));

        $method = CommonTestHelpers::getInaccessibleMethod(WooCommerceExtensionsPage::class, 'maybeClearUpdatesCacheCount');

        $method->invoke(new WooCommerceExtensionsPage());

        $this->assertConditionsMet();
    }

    /** @see testCanClearUpdatesCountCache() */
    public function provideClearUpdatesCountCacheData()
    {
        return [
            [0, WooCommerceExtensionsPage::TAB_AVAILABLE_EXTENSIONS],
            [0, WooCommerceExtensionsPage::TAB_BROWSE_EXTENSIONS],
            [1, WooCommerceExtensionsPage::TAB_SUBSCRIPTIONS],
        ];
    }

    /**
     * @covers \GoDaddy\WordPress\MWC\Dashboard\Pages\WooCommerceExtensionsPage::getCurrentTab
     *
     * @dataProvider provideCanGetCurrentTabData
     */
    public function testCanGetCurrentTab($tab, $params)
    {
        $this->mockStaticMethod(StringHelper::class, 'sanitize')->andReturnArg(0);

        $_GET = ArrayHelper::combine($_GET, $params);

        TestHelpers::mockRegisterActionCalls();
        TestHelpers::mockRegisterFilterCalls();

        $method = CommonTestHelpers::getInaccessibleMethod(WooCommerceExtensionsPage::class, 'getCurrentTab');

        $this->assertSame($tab, $method->invoke(new WooCommerceExtensionsPage()));
    }

    /** @see testCanGetCurrentTab() */
    public function provideCanGetCurrentTabData()
    {
        return [
            [
                'available_extensions',
                [],
            ],
            [
                'available_extensions',
                [
                    'tab' => 'available_extensions',
                ],
            ],
            [
                'browse_extensions',
                [
                    'section' => 'not-helper',
                ]
            ],
            [
                'browse_extensions',
                [
                    'tab'     => 'available_extensions',
                    'section' => 'not-helper',
                ]
            ],
            [
                'subscriptions',
                [
                    'section' => 'helper',
                ]
            ],
            [
                'subscriptions',
                [
                    'tab'     => 'subscriptions',
                    'section' => 'helper',
                ]
            ],
            [
                'subscriptions',
                [
                    'tab'     => 'available_extensions',
                    'section' => 'helper',
                ]
            ],
        ];
    }

    /**
     * @covers \GoDaddy\WordPress\MWC\Dashboard\Pages\WooCommerceExtensionsPage::getTabs
     *
     * @dataProvider provideCanGetTabsData
     */
    public function testCanGetTabs(string $label, string $tab, bool $isReseller)
    {
        TestHelpers::mockRegisterActionCalls();
        TestHelpers::mockRegisterFilterCalls();

        WP_Mock::userFunction('admin_url')->andReturn('https//example.org/');
        WP_Mock::userFunction('esc_html__')->andReturnArg(0);

        Patchwork\redefine(WooCommerceExtensionsPage::class.'::getUpdatesCountHtml', Patchwork\always(''));

        $method = CommonTestHelpers::getInaccessibleMethod(WooCommerceExtensionsPage::class, 'getTabs');

        $this->mockStaticMethod(ManagedWooCommerceRepository::class.'::isReseller')->andReturn($isReseller);

        $tabs = $method->invoke(new WooCommerceExtensionsPage());

        $this->assertSame($label, $tabs[$tab]['label']);
    }

    /** @see testCanGetTabs() */
    public function provideCanGetTabsData()
    {
        return [
            ['GoDaddy Included Extensions', WooCommerceExtensionsPage::TAB_AVAILABLE_EXTENSIONS, false],
            ['Included Extensions', WooCommerceExtensionsPage::TAB_AVAILABLE_EXTENSIONS, true],
            ['Browse Extensions', WooCommerceExtensionsPage::TAB_BROWSE_EXTENSIONS, true],
            ['WooCommerce.com Subscriptions', WooCommerceExtensionsPage::TAB_SUBSCRIPTIONS, true],
        ];
    }

    /**
     * @covers \GoDaddy\WordPress\MWC\Dashboard\Pages\WooCommerceExtensionsPage::removeManagedPluginsFromCount
     */
    public function testRemoveManagedPluginsFromCount()
    {
        TestHelpers::mockRegisterActionCalls();
        TestHelpers::mockRegisterFilterCalls();

        // mock managed plugins
        Cache::extensions()->set([
            'skyverge' => [
                (new PluginExtension())->setHomepageUrl('https://woocommerce.com/products/product-add-ons/'),
            ],
            'woocommerce' => [
                (new PluginExtension())->setHomepageUrl('https://woocommerce.com/products/checkout-fi-gateway/'),
            ],
        ]);

        $page = new WooCommerceExtensionsPage();

        $filteredTransientValue = $page->removeManagedPluginsFromCount($this->getWooCommerceHelperUpdatesTransientValue());

        // products that were filtered out
        $this->assertFalse(ArrayHelper::has($filteredTransientValue, 'products.18618'));
        $this->assertFalse(ArrayHelper::has($filteredTransientValue, 'products.18625'));

        // filtered products
        $this->assertTrue(ArrayHelper::has($filteredTransientValue, 'products.18628'));
    }

    /**
     * Gets a mocked _woocommerce_helper_updates transient value.
     *
     * @return array
     */
    protected function getWooCommerceHelperUpdatesTransientValue() : array
    {
        return [
            'hash' => '22b90e47c69c74d5c1260a0b00ca44e9',
            'updated' => 1609153653,
            'products' => [
                18618 => [
                    'version' => '3.3.0',
                    'slug' => 'product-add-ons',
                    'url' => 'https://woocommerce.com/products/product-add-ons/',
                    'package' => null,
                    'upgrade_notice' => 'Fix - Fix Checkbox add-on not showing required styles when all are unselected.',
                ],
                18625 => [
                    'version' => '3.2.0',
                    'slug' => 'checkout-fi-gateway',
                    'url' => 'https://woocommerce.com/products/checkout-fi-gateway/',
                    'package' => null,
                    'upgrade_notice' => 'Misc - Add compatibility for WooCommerce 4.7',
                ],
                18628 => [
                    'version' => '2.8.0',
                    'slug' => 'woocommerce-paytrail',
                    'url' => 'https://woocommerce.com/products/woocommerce-paytrail/',
                    'package' => null,
                    'upgrade_notice' => 'Misc - Add compatibility for WooCommerce 4.7',
                ],
            ],
            'errors' => [],
        ];
    }
}
