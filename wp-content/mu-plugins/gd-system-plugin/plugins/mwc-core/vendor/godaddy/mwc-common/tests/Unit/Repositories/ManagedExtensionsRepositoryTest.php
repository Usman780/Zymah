<?php

namespace GoDaddy\WordPress\MWC\Common\Tests\Unit\Repositories;

use Exception;
use GoDaddy\WordPress\MWC\Common\Cache\Cache;
use GoDaddy\WordPress\MWC\Common\Configuration\Configuration;
use GoDaddy\WordPress\MWC\Common\Extensions\Types\PluginExtension;
use GoDaddy\WordPress\MWC\Common\Extensions\Types\ThemeExtension;
use GoDaddy\WordPress\MWC\Common\Helpers\ArrayHelper;
use GoDaddy\WordPress\MWC\Common\Helpers\StringHelper;
use GoDaddy\WordPress\MWC\Common\Repositories\ManagedExtensionsRepository;
use GoDaddy\WordPress\MWC\Common\Repositories\WordPressRepository;
use GoDaddy\WordPress\MWC\Common\Tests\TestHelpers;
use GoDaddy\WordPress\MWC\Common\Tests\WPTestCase;
use ReflectionException;
use WP_Mock;

/**
 * @covers \GoDaddy\WordPress\MWC\Common\Repositories\ManagedExtensionsRepository
 */
final class ManagedExtensionsRepositoryTest extends WPTestCase
{
    /**
     * Runs before each test.
     */
    public function setUp(): void
    {
        parent::setUp();

        WP_Mock::userFunction('get_transient')->with('gd_extensions')->andReturnFalse();

        Cache::extensions()->clear();
    }

    /**
     * Tests that it can get the Managed Extensions.
     *
     * @covers \GoDaddy\WordPress\MWC\Common\Repositories\ManagedExtensionsRepository::getManagedExtensions()
     * @throws Exception
     */
    public function testCanGetManagedExtensions()
    {
        $this->mockWooCommerceExtensionsRequestFunctions();
        $this->mockSkyVergeExtensionsRequestFunctions();

        $extensions = ManagedExtensionsRepository::getManagedExtensions();

        $this->assertIsArray($extensions);
        $this->assertCount(4, $extensions);
    }

    /**
     * Tests that it can Get the Managed Plugins.
     *
     * @covers \GoDaddy\WordPress\MWC\Common\Repositories\ManagedExtensionsRepository::getManagedPlugins()
     * @throws Exception
     */
    public function testCanGetManagedPlugins()
    {
        $this->mockWooCommerceExtensionsRequestFunctions();
        $this->mockSkyVergeExtensionsRequestFunctions();

        $managedPlugins = ManagedExtensionsRepository::getManagedPlugins();

        $this->assertIsArray($managedPlugins);
        $this->assertContainsOnlyInstancesOf(PluginExtension::class, $managedPlugins);
    }

    /**
     * Tests that it can Get the Installed Managed Plugins.
     *
     * @covers \GoDaddy\WordPress\MWC\Common\Repositories\ManagedExtensionsRepository::getInstalledManagedPlugins()
     * @throws Exception
     */
    public function testCanGetInstalledManagedPlugins()
    {
        $this->mockWooCommerceExtensionsRequestFunctions();
        $this->mockSkyVergeExtensionsRequestFunctions();
        $this->mockStaticMethod(WordPressRepository::class, 'requireWordPressFilesystem')->andReturnNull();

        WP_Mock::userFunction('get_plugins')
            ->andReturn(['test-plugin/test-plugin.php' => ['name' => 'Test Plugin']]);

        $baseNames = [];

        foreach(ManagedExtensionsRepository::getInstalledManagedPlugins() as $plugin) {
            $baseNames[] = $plugin->getBasename();
        }

        $this->assertEquals(['test-plugin/test-plugin.php'], $baseNames);
    }

    /**
     * Tests that it can Get the Installed Managed Themes.
     *
     * @covers \GoDaddy\WordPress\MWC\Common\Repositories\ManagedExtensionsRepository::getInstalledManagedThemes()
     * @throws Exception
     */
    public function testCanGetInstalledManagedThemes()
    {
        $this->mockWooCommerceExtensionsRequestFunctions();
        $this->mockSkyVergeExtensionsRequestFunctions();
        $this->mockStaticMethod(WordPressRepository::class, 'requireWordPressFilesystem')->andReturnNull();

        WP_Mock::userFunction('wp_get_themes')
            ->andReturn(['test-theme' => ['name' => 'Test Theme']]);

        $baseNames = [];

        foreach(ManagedExtensionsRepository::getInstalledManagedThemes() as $theme) {
            $baseNames[] = $theme->getSlug();
        }

        $this->assertEquals(['test-theme'], $baseNames);
    }

    /**
     * Tests that it can get the SkyVerge specific extensions.
     *
     * @covers \GoDaddy\WordPress\MWC\Common\Repositories\ManagedExtensionsRepository::getManagedSkyVergeExtensions()
     * @throws Exception
     */
    public function testCanGetManagedSkyVergeExtensions()
    {
        Cache::extensions()->clear();

        $this->mockSkyVergeExtensionsRequestFunctions();

        $extensions = ManagedExtensionsRepository::getManagedSkyVergeExtensions();

        $this->assertIsArray($extensions);

        $this->assertInstanceOf(PluginExtension::class, $extensions[0]);
        $this->assertSame('1001', $extensions[0]->getId());
        $this->assertSame('test-plugin', $extensions[0]->getSlug());
        $this->assertSame('test-plugin/test-plugin.php', $extensions[0]->getBasename());
        $this->assertSame('Test Plugin', $extensions[0]->getName());
        $this->assertSame('Test Plugin description', $extensions[0]->getShortDescription());
        $this->assertSame(PluginExtension::TYPE, $extensions[0]->getType());
        $this->assertNull($extensions[0]->getCategory());
        $this->assertSame('1.2.3', $extensions[0]->getVersion());
        $this->assertSame(1610151181, $extensions[0]->getLastUpdated());
        $this->assertSame('7.0', $extensions[0]->getMinimumPhpVersion());
        $this->assertSame('5.2', $extensions[0]->getMinimumWordPressVersion());
        $this->assertSame('3.5', $extensions[0]->getMinimumWooCommerceVersion());
        $this->assertSame('https://example.org/1001/package', $extensions[0]->getPackageUrl());
        $this->assertSame('https://example.org/1001/homepage', $extensions[0]->getHomepageUrl());
        $this->assertSame('https://example.org/1001/documentation', $extensions[0]->getDocumentationUrl());

        $this->assertInstanceOf(ThemeExtension::class, $extensions[1]);
        $this->assertSame('1002', $extensions[1]->getId());
        $this->assertSame('test-theme', $extensions[1]->getSlug());
        $this->assertSame('Test Theme', $extensions[1]->getName());
        $this->assertSame('Test Theme description', $extensions[1]->getShortDescription());
        $this->assertSame(ThemeExtension::TYPE, $extensions[1]->getType());
        $this->assertNull($extensions[1]->getCategory());
        $this->assertSame(1610151181, $extensions[1]->getLastUpdated());
        $this->assertSame('1.4.3', $extensions[1]->getVersion());
        $this->assertSame('7.0', $extensions[1]->getMinimumPhpVersion());
        $this->assertSame('5.2', $extensions[1]->getMinimumWordPressVersion());
        $this->assertSame('3.5', $extensions[1]->getMinimumWooCommerceVersion());
        $this->assertSame('https://example.org/1002/package', $extensions[1]->getPackageUrl());
        $this->assertSame('https://example.org/1002/homepage', $extensions[1]->getHomepageUrl());
        $this->assertSame('https://example.org/1002/documentation', $extensions[1]->getDocumentationUrl());
    }

    /**
     * Mocks WordPress request functions to return SkyVerge extensions data.
     */
    protected function mockSkyVergeExtensionsRequestFunctions()
    {
        Configuration::initialize(StringHelper::trailingSlash(StringHelper::before(__DIR__, 'tests').'tests/Configurations'));
        Configuration::set('mwc.extensions.api.url', 'https://example.org/skyverge/v1/');

        $this->mockWordPressRequestFunctionsWithArgs([
            'url'      => 'https://example.org/skyverge/v1/extensions/',
            'response' => [
                'code' => 200,
                'body' => [
                    'data' => [
                        [
                            'extensionId'      => '1001',
                            'slug'             => 'test-plugin',
                            'label'            => 'Test Plugin',
                            'shortDescription' => 'Test Plugin description',
                            'type'             => 'PLUGIN',
                            'category'         => null,
                            'version'          => [
                                'version'                   => '1.2.3',
                                'minimumPhpVersion'         => '7.0',
                                'minimumWordPressVersion'   => '5.2',
                                'minimumWooCommerceVersion' => '3.5',
                                'releasedAt'                => '2021-01-09T00:13:01.000000Z',
                                'links'                     => [
                                    'package' => [
                                        'href' => 'https://example.org/1001/package',
                                    ],
                                ],
                            ],
                            'links'             => [
                                'homepage'      => [
                                    'href' => 'https://example.org/1001/homepage',
                                ],
                                'documentation' => [
                                    'href' => 'https://example.org/1001/documentation',
                                ],
                            ],
                        ],
                        [
                            'extensionId'      => '1002',
                            'slug'             => 'test-theme',
                            'label'            => 'Test Theme',
                            'shortDescription' => 'Test Theme description',
                            'type'             => 'THEME',
                            'category'         => null,
                            'version'          => [
                                'version'                   => '1.4.3',
                                'minimumPhpVersion'         => '7.0',
                                'minimumWordPressVersion'   => '5.2',
                                'minimumWooCommerceVersion' => '3.5',
                                'releasedAt'                => '2021-01-09T00:13:01.000000Z',
                                'links'                     => [
                                    'package' => [
                                        'href' => 'https://example.org/1002/package',
                                    ],
                                ],
                            ],
                            'links'             => [
                                'homepage'      => [
                                    'href' => 'https://example.org/1002/homepage',
                                ],
                                'documentation' => [
                                    'href' => 'https://example.org/1002/documentation',
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ]);
    }

    /**
     * Tests that it can build managed SkyVerge extensions.
     *
     * @covers \GoDaddy\WordPress\MWC\Common\Repositories\ManagedExtensionsRepository::buildManagedSkyVergeExtension()
     * @param string $class expected type for the generated extension
     * @param string $type  value for the type data entry
     * @dataProvider provideBuildManagedSkyVergeExtensionsData
     * @throws ReflectionException
     */
    public function testCanBuildManagedSkyVergeExtensions(string $class, string $type)
    {
        $repository = new ManagedExtensionsRepository();

        $extension = TestHelpers::getInaccessibleMethod($repository, 'buildManagedSkyVergeExtension')
            ->invoke($repository, ['type' => $type]);

        $this->assertInstanceOf($class, $extension);
    }

    /** @see testCanBuildManagedSkyVergeExtensions() */
    public function provideBuildManagedSkyVergeExtensionsData() : array
    {
        return [
            [ThemeExtension::class, ThemeExtension::TYPE],
            [PluginExtension::class, PluginExtension::TYPE],
        ];
    }

    /**
     * Tests that it can trigger a notice when trying to get managed SkyVerge extensions and API URL is missing.
     *
     * @covers \GoDaddy\WordPress\MWC\Common\Repositories\ManagedExtensionsRepository::getManagedSkyVergeExtensions()
     * @throws Exception
     */
    public function testGetManagedSkyVergeExtensionsThrowsWhenApiUrlIsMissing()
    {
        Cache::extensions()->clear();
        $configurationDirectories = TestHelpers::getInaccessibleProperty(Configuration::class, 'configurationDirectories');
        $configurationDirectories->setValue([]);
        Configuration::reload();

        $this->mockWordPressRequestFunctions();

        // TODO: expect a dedicated exception type {WV 2020-12-18}
        $this->expectExceptionMessage('You must provide a url for an outgoing request');

        $this->assertIsArray(ManagedExtensionsRepository::getManagedSkyVergeExtensions());
    }

    /**
     * Tests that it can return empty array on API errors.
     *
     * @covers \GoDaddy\WordPress\MWC\Common\Repositories\ManagedExtensionsRepository::getManagedSkyVergeExtensions()
     * @throws Exception
     */
    public function testGetManagedSkyVergeExtensionsReturnsEmptyArrayOnApiError()
    {
        Configuration::set('mwc.extensions.api.url', 'https://example.org/extensions');

        $this->mockWordPressRequestFunctions(401, 'error', true);

        $extensions = ManagedExtensionsRepository::getManagedSkyVergeExtensions();

        $this->assertIsArray($extensions);
        $this->assertEmpty($extensions);
    }

    /**
     * Tests that it can get managed extensions from the cache.
     *
     * @covers \GoDaddy\WordPress\MWC\Common\Repositories\ManagedExtensionsRepository::getManagedExtensionsFromCache()
     * @throws Exception
     */
    public function testCanGetManagedExtensionsFromCache()
    {
        $value = [new PluginExtension()];

        Cache::extensions()->set(['key' => $value]);

        $method = TestHelpers::getInaccessibleMethod(ManagedExtensionsRepository::class, 'getManagedExtensionsFromCache');
        $method->setAccessible(true);

        $extensions = $method->invoke(null, 'key', function () {
            return [];
        });

        $this->assertSame($value, $extensions);
    }

    /**
     * Tests that when it gets managed extensions from the cache, it updates the cache.
     *
     * @covers \GoDaddy\WordPress\MWC\Common\Repositories\ManagedExtensionsRepository::getManagedExtensionsFromCache()
     * @throws Exception
     */
    public function testGetManagedExtensionsFromCacheUpdatesCache()
    {
        Cache::extensions()->clear();

        $method = TestHelpers::getInaccessibleMethod(ManagedExtensionsRepository::class, 'getManagedExtensionsFromCache');
        $method->setAccessible(true);

        $extensions = $method->invoke(null, 'key', function () {
            return [new PluginExtension()];
        });

        $this->assertSame(['key'=> $extensions], Cache::extensions()->get());
    }

    /**
     * Tests that can get the managed themes.
     *
     * @covers \GoDaddy\WordPress\MWC\Common\Repositories\ManagedExtensionsRepository::getManagedThemes()
     * @throws Exception
     */
    public function testCanGetManagedThemes()
    {
        $this->mockWooCommerceExtensionsRequestFunctions();
        $this->mockSkyVergeExtensionsRequestFunctions();

        $managedThemes = ManagedExtensionsRepository::getManagedThemes();

        $this->assertIsArray($managedThemes);
        $this->assertContainsOnlyInstancesOf(ThemeExtension::class, $managedThemes);
    }

    /**
     * Tests that can get managed WooCommerce extensions.
     *
     * @covers \GoDaddy\WordPress\MWC\Common\Repositories\ManagedExtensionsRepository::getManagedWooExtensions()
     * @throws Exception
     */
    public function testCanGetManagedWooExtensions()
    {
        Cache::extensions()->clear();

        $this->mockWooCommerceExtensionsRequestFunctions();

        $extensions = ManagedExtensionsRepository::getManagedWooExtensions();

        $this->assertIsArray($extensions);

        $this->assertInstanceOf(PluginExtension::class, $extensions[0]);
        $this->assertSame('first-plugin', $extensions[0]->getSlug());
        $this->assertSame('first-plugin/first-plugin.php', $extensions[0]->getBasename());
        $this->assertSame('First Plugin', $extensions[0]->getName());
        $this->assertSame('First Plugin description', $extensions[0]->getShortDescription());
        $this->assertSame(PluginExtension::TYPE, $extensions[0]->getType());
        $this->assertNull($extensions[0]->getCategory());
        $this->assertSame('1.2.3', $extensions[0]->getVersion());
        $this->assertSame(1606089600, $extensions[0]->getLastUpdated());
        $this->assertSame('https://example.org/first/package', $extensions[0]->getPackageUrl());
        $this->assertSame('https://example.org/first/homepage', $extensions[0]->getHomepageUrl());
        $this->assertSame('https://example.org/first/documentation', $extensions[0]->getDocumentationUrl());

        $this->assertInstanceOf(PluginExtension::class, $extensions[1]);
        $this->assertSame('second-plugin', $extensions[1]->getSlug());
        $this->assertSame('second-plugin/second-plugin.php', $extensions[1]->getBasename());
        $this->assertSame('Second Plugin', $extensions[1]->getName());
        $this->assertSame('Second Plugin description', $extensions[1]->getShortDescription());
        $this->assertSame(PluginExtension::TYPE, $extensions[1]->getType());
        $this->assertNull($extensions[1]->getCategory());
        $this->assertSame(1602806400, $extensions[1]->getLastUpdated());
        $this->assertSame('1.4.3', $extensions[1]->getVersion());
        $this->assertSame('https://example.org/second/package', $extensions[1]->getPackageUrl());
        $this->assertSame('https://example.org/second/homepage', $extensions[1]->getHomepageUrl());
        $this->assertSame('https://example.org/second/documentation', $extensions[1]->getDocumentationUrl());
    }

    /**
     * Tests that it can get the API url of managed WooCommerce extensions.
     *
     * @param string $configured_url
     * @param string $expected
     * @param string|null $env
     * @throws ReflectionException
     * @covers \GoDaddy\WordPress\MWC\Common\Repositories\ManagedExtensionsRepository::getManagedWooExtensionsApiUrl()
     * @dataProvider providerCanGetManagedWooExtensionsApiUrl
     */
    public function testCanGetManagedWooExtensionsApiUrl(string $configured_url, string $expected, string $env = null)
    {
        Configuration::initialize();
        Configuration::set('godaddy.account.uid', '1234');
        Configuration::set('godaddy.extensions.api.url', $configured_url);

        $repository = new ManagedExtensionsRepository();
        $method = TestHelpers::getInaccessibleMethod(ManagedExtensionsRepository::class, 'getManagedWooExtensionsApiUrl');

        Configuration::set('mwc.env', $env);
        $this->assertEquals($expected, $method->invoke($repository));
    }

    /** @see testCanGetManagedWooExtensionsApiUrl() */
    public function providerCanGetManagedWooExtensionsApiUrl() : array
    {
        return [
            'prod - no environment replacement' => ['noreplacementhere', 'noreplacementhere/sites/1234/partner/a8c/woocommerce/info', 'prod'],
            'prod - environment replaced'       => ['this{environment_prefix}isreplaced', 'thisisreplaced/sites/1234/partner/a8c/woocommerce/info', 'prod'],
            'test - no environment replacement' => ['noreplacementhere', 'noreplacementhere/sites/1234/partner/a8c/woocommerce/info', 'test'],
            'test - environment replaced'       => ['this{environment_prefix}isreplaced', 'thistest-isreplaced/sites/1234/partner/a8c/woocommerce/info', 'test'],
            'null - no environment replacement' => ['noreplacementhere', 'noreplacementhere/sites/1234/partner/a8c/woocommerce/info', null],
            'null - environment replaced'       => ['this{environment_prefix}isreplaced', 'thisisreplaced/sites/1234/partner/a8c/woocommerce/info', null],
        ];
    }

    /**
     * Tests that it can get available versions for a given extension.
     *
     * @covers \GoDaddy\WordPress\MWC\Common\Repositories\ManagedExtensionsRepository::getManagedExtensionVersions()
     */
    public function testCanGetManagedExtensionVersions()
    {
        $this->mockExtensionVersionsRequestFunctions();

        $plugin = new PluginExtension();

        $plugin->setId(1);

        $versions = ManagedExtensionsRepository::getManagedExtensionVersions($plugin);

        $this->assertCount(2, $versions);
        $this->assertContainsOnlyInstancesOf(PluginExtension::class, $versions);

        $this->assertSame($plugin->getId(), $versions[0]->getId());
        $this->assertSame($plugin->getId(), $versions[1]->getId());

        $this->assertTrue(version_compare($versions[0]->getVersion(), $versions[1]->getVersion(), '<'));
    }

    /**
     * Mocks WordPress request functions to return WooCommerce extensions data.
     */
    protected function mockWooCommerceExtensionsRequestFunctions()
    {
        Configuration::initialize(StringHelper::trailingSlash(StringHelper::before(__DIR__, 'tests').'tests/Configurations'));
        Configuration::set('godaddy.extensions.api.url', 'https://example.org/woocommerce/v1/extensions');

        $this->mockWordPressRequestFunctionsWithArgs([
            'url'      => 'https://example.org/woocommerce/v1/extensions',
            'response' => [
                'code' => 200,
                'body' => [
                    'name'        => 'godaddy_ecommerce',
                    'description' => 'GoDaddy eCommerce Plan',
                    'products'    => [
                        [
                            'download_link'         => 'https://example.org/first/package',
                            'homepage'              => 'https://example.org/first/homepage',
                            'icons'                 => [
                                '1x' => 'https://example.org/icons-128x128.png',
                                '2x' => 'https://example.org/icons-256x256.png',
                            ],
                            'last_updated'          => '2020-11-23', // 1606089600
                            'name'                  => 'First Plugin',
                            'short_description'     => 'First Plugin description',
                            'slug'                  => 'first-plugin',
                            'support_documentation' => 'https://example.org/first/documentation',
                            'type'                  => PluginExtension::TYPE,
                            'version'               => '1.2.3',
                        ],
                        [
                            'download_link'         => 'https://example.org/second/package',
                            'homepage'              => 'https://example.org/second/homepage',
                            'icons'                 => [
                                '1x' => 'https://example.org/icons-128x128.png',
                                '2x' => 'https://example.org/icons-256x256.png',
                            ],
                            'last_updated'          => '2020-10-16',
                            'name'                  => 'Second Plugin',
                            'short_description'     => 'Second Plugin description',
                            'slug'                  => 'second-plugin',
                            'support_documentation' => 'https://example.org/second/documentation',
                            'type'                  => PluginExtension::TYPE,
                            'version'               => '1.4.3',
                        ],
                    ],
                ],
            ],
        ]);
    }

    /**
     * Mock WordPress request functions to return SkyVerge extensions version data.
     */
    protected function mockExtensionVersionsRequestFunctions()
    {
        Configuration::set('mwc.extensions.api.url', 'https://api.mwc.secureserver.net/v1/');

        $this->mockWordPressRequestFunctionsWithArgs([
            'url' => 'https://api.mwc.secureserver.net/v1/extensions/1/versions',
            'response' => [
                'code' => 200,
                'body' => [
                    'data' => [
                        [
                            'extensionVersionId' => 12,
                            'version' => '3.10.1',
                            'minimumPhpVersion' => '7.0',
                            'minimumWordPressVersion' => '5.2',
                            'minimumWooCommerceVersion' => '3.5',
                            'releasedAt' => '2021-02-14T21:22:34.000000Z',
                            'links' => [
                                'self' => [
                                    'href' => 'https://api.mwc.secureserver.net/v1/extensions/1/versions/3.10.1',
                                ],
                                'package' => [
                                    'href' => 'https://api.mwc.secureserver.net/v1/extensions/1/versions/3.10.1/package',
                                ],
                            ],
                        ],
                        [
                            'extensionVersionId' => 13,
                            'version' => '10.0.1',
                            'minimumPhpVersion' => '7.0',
                            'minimumWordPressVersion' => '5.2',
                            'minimumWooCommerceVersion' => '3.5',
                            'releasedAt' => '2021-02-14T22:22:34.000000Z',
                            'links' => [
                                'self' => [
                                    'href' => 'https://api.mwc.secureserver.net/v1/extensions/1/versions/10.10.1',
                                ],
                                'package' => [
                                    'href' => 'https://api.mwc.secureserver.net/v1/extensions/1/versions/10.10.1/package',
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ]);
    }
}
