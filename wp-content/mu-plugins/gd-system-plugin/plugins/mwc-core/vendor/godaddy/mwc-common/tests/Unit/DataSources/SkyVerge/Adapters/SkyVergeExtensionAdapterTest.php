<?php

namespace GoDaddy\WordPress\MWC\Common\Tests\Unit\DataSources\SkyVerge\Adapters;

use Exception;
use GoDaddy\WordPress\MWC\Common\DataSources\SkyVerge\Adapters\SkyVergeExtensionAdapter;
use GoDaddy\WordPress\MWC\Common\Extensions\Types\PluginExtension;

/**
 * @covers \GoDaddy\WordPress\MWC\Common\DataSources\SkyVerge\Adapters\SkyVergeExtensionAdapter
 */
final class SkyVergeExtensionAdapterTest extends \PHPUnit\Framework\TestCase
{
    /**
     * Tests that can convert from source.
     *
     * @covers \GoDaddy\WordPress\MWC\Common\DataSources\SkyVerge\Adapters\SkyVergeExtensionAdapter::convertFromSource()
     * @throws Exception
     */
    public function testCanConvertFromSource()
    {
        $adapter = new SkyVergeExtensionAdapter($this->getSourceData());

        $this->assertSame([
            'id'                        => '1001',
            'slug'                      => 'test-plugin',
            'name'                      => 'Test Plugin',
            'shortDescription'          => 'Test Plugin description',
            'type'                      => PluginExtension::TYPE,
            'version'                   => '1.2.3',
            'lastUpdated'               => 1610151181,
            'minimumPhpVersion'         => '7.0',
            'minimumWordPressVersion'   => '5.2',
            'minimumWooCommerceVersion' => '3.5',
            'packageUrl'                => 'https://example.org/package',
            'homepageUrl'               => 'https://example.org/homepage',
            'documentationUrl'          => 'https://example.org/documentation',
            'imageUrls'                 => [
                '1x' => 'url1',
                '2x' => 'url2',
            ],
            'basename'                  => 'test-plugin/test-plugin.php',
        ], $adapter->convertFromSource());
    }

    /**
     * Gets SkyVerge source data used for tests.
     */
    private function getSourceData(): array
    {
        return [
            'extensionId'      => '1001',
            'slug'             => 'test-plugin',
            'label'            => 'Test Plugin',
            'shortDescription' => 'Test Plugin description',
            'type'             => 'PLUGIN',
            'category'         => null,
            'imageUrls'        => [
                '1x' => 'url1',
                '2x' => 'url2',
            ],
            'version'          => [
                'version'                   => '1.2.3',
                'minimumPhpVersion'         => '7.0',
                'minimumWordPressVersion'   => '5.2',
                'minimumWooCommerceVersion' => '3.5',
                'releasedAt'                => '2021-01-09T00:13:01.000000Z',
                'links'                     => [
                    'package' => [
                        'href' => 'https://example.org/package',
                    ],
                ],
            ],
            'links'             => [
                'homepage'      => [
                    'href' => 'https://example.org/homepage',
                ],
                'documentation' => [
                    'href' => 'https://example.org/documentation',
                ],
            ],
        ];
    }

    /**
     * Tests that it can convert to source.
     *
     * @covers \GoDaddy\WordPress\MWC\Common\DataSources\SkyVerge\Adapters\SkyVergeExtensionAdapter::convertToSource()
     */
    public function testCanConvertToSource()
    {
        $adapter = new SkyVergeExtensionAdapter($this->getSourceData());

        $this->assertSame($this->getSourceData(), $adapter->convertToSource());
    }

    /**
     * Tests that it can get the type.
     *
     * @covers \GoDaddy\WordPress\MWC\Common\DataSources\SkyVerge\Adapters\SkyVergeExtensionAdapter::getType()
     */
    public function testCanGetType()
    {
        $adapter = new SkyVergeExtensionAdapter($this->getSourceData());

        $this->assertSame(PluginExtension::TYPE, $adapter->getType());
    }

    /**
     * Tests that it can get valid image URLs.
     *
     * @dataProvider providerCanGetImageUrls
     *
     * @param array $data input data
     * @param array $expected expected return
     *
     * @covers \GoDaddy\WordPress\MWC\Common\DataSources\SkyVerge\Adapters\SkyVergeExtensionAdapter::getImageUrls()
     */
    public function testCanGetImageUrls(array $data, array $expected)
    {
        $adapter = new SkyVergeExtensionAdapter($data);

        $this->assertSame($expected, $adapter->getImageUrls());
    }

    /** @see testCanGetImageUrls */
    public function providerCanGetImageUrls() : array
    {
        return [
            'valid data' => [
                [
                    'imageUrls' => [
                        '1x' => 'url1',
                        '2x' => 'url2',
                    ],
                ],
                [
                    '1x' => 'url1',
                    '2x' => 'url2',
                ],
            ],
            'missing data' => [
                [
                    'no URLs' => [],
                ],
                [],
            ],
            'not an array' => [
                [
                    'imageUrls' => 'url',
                ],
                [
                    'url',
                ],
            ],
            'invalid URLs' => [
                [
                    'imageUrls' => [
                        'empty string' => '',
                        'not a string' => 1234,
                        'valid'        => 'url1',
                    ],
                ],
                [
                    'valid' => 'url1',
                ],
            ],
        ];
    }
}
