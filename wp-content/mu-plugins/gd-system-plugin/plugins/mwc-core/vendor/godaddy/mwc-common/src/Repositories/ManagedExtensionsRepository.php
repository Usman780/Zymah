<?php

namespace GoDaddy\WordPress\MWC\Common\Repositories;

use Closure;
use Exception;
use GoDaddy\WordPress\MWC\Common\Cache\Cache;
use GoDaddy\WordPress\MWC\Common\Configuration\Configuration;
use GoDaddy\WordPress\MWC\Common\DataSources\Contracts\ExtensionAdapterContract;
use GoDaddy\WordPress\MWC\Common\DataSources\SkyVerge\Adapters\SkyVergeExtensionAdapter;
use GoDaddy\WordPress\MWC\Common\DataSources\WooCommerce\Adapters\WooCommerceExtensionAdapter;
use GoDaddy\WordPress\MWC\Common\Extensions\AbstractExtension;
use GoDaddy\WordPress\MWC\Common\Extensions\Types\PluginExtension;
use GoDaddy\WordPress\MWC\Common\Extensions\Types\ThemeExtension;
use GoDaddy\WordPress\MWC\Common\Helpers\ArrayHelper;
use GoDaddy\WordPress\MWC\Common\Helpers\StringHelper;
use GoDaddy\WordPress\MWC\Common\Http\GoDaddyRequest;

/**
 * Managed extensions repository class.
 *
 * Provides methods for getting Woo and SkyVerge managed extensions.
 *
 * @since x.y.z
 */
class ManagedExtensionsRepository
{
    /**
     * Gets all managed extensions.
     *
     * @since x.y.z
     *
     * @return AbstractExtension[]
     * @throws Exception
     */
    public static function getManagedExtensions() : array
    {
        return ArrayHelper::combine(static::getManagedWooExtensions(), static::getManagedSkyVergeExtensions());
    }

    /**
     * Gets the managed plugins.
     *
     * @since x.y.z
     *
     * @return PluginExtension[]
     * @throws Exception
     */
    public static function getManagedPlugins() : array
    {
        return ArrayHelper::where(static::getManagedExtensions(), static function (AbstractExtension $extension) {
            return $extension->getType() === PluginExtension::TYPE;
        }, false);
    }

    /**
     * Get only the installed managed plugins.
     *
     * @since x.y.z
     *
     * @return PluginExtension[]
     * @throws Exception
     */
    public static function getInstalledManagedPlugins() : array
    {
        WordPressRepository::requireWordPressFilesystem();

        $availablePlugins = get_plugins();

        return ArrayHelper::where(static::getManagedPlugins(), function(PluginExtension $plugin) use ($availablePlugins) {
            return ArrayHelper::exists($availablePlugins, $plugin->getBasename());
        });
    }

    /**
     * Get only the installed managed plugins.
     *
     * @since x.y.z
     *
     * @return PluginExtension[]
     * @throws Exception
     */
    public static function getInstalledManagedThemes() : array
    {
        WordPressRepository::requireWordPressFilesystem();

        $availableThemes = wp_get_themes();

        return ArrayHelper::where(static::getManagedThemes(), function(ThemeExtension $theme) use ($availableThemes) {
            return ArrayHelper::exists($availableThemes, $theme->getSlug());
        });
    }

    /**
     * Gets the SkyVerge managed extensions.
     *
     * @since x.y.z
     *
     * @return AbstractExtension[]
     * @throws Exception
     */
    public static function getManagedSkyVergeExtensions() : array
    {
        return static::getManagedExtensionsFromCache('skyverge', function () {
            return static::loadManagedSkyVergeExtensions();
        });
    }

    /**
     * Gets managed extensions from cache.
     *
     * If the cache has no value, it attempts to get the extensions invoking the given $loader.
     *
     * @since x.y.z
     *
     * @param string $key cache key
     * @param Closure $loader function to call
     * @return array
     * @throws Exception
     */
    protected static function getManagedExtensionsFromCache(string $key, Closure $loader): array
    {
        $cache = Cache::extensions()->get([]);

        if (ArrayHelper::exists($cache, $key)) {
            return ArrayHelper::get($cache, $key);
        }

        $extensions = $loader();

        ArrayHelper::set($cache, $key, $extensions);

        Cache::extensions()->set($cache);

        return $extensions;
    }

    /**
     * Loads the SkyVerge managed extensions from the API.
     *
     * @since x.y.z
     *
     * @return array
     * @throws Exception
     */
    protected static function loadManagedSkyVergeExtensions(): array
    {
        return array_map(function ($data) {
            return static::buildManagedSkyVergeExtension($data);
        }, static::getManagedSkyVergeExtensionsData());
    }

    /**
     * Gets data for managed SkyVerge extensions.
     *
     * @since x.y.z
     *
     * @return array
     * @throws Exception
     */
    protected static function getManagedSkyVergeExtensionsData() : array
    {
        $response = (new GoDaddyRequest())
            ->query(['method' => 'GET'])
            ->url(static::getManagedSkyVergeExtensionsApiUrl())
            ->send();

        return ArrayHelper::get($response->getBody(), 'data', []);
    }

    /**
     * Gets the URL for the Managed SkyVerge Extensions API.
     *
     * @return string
     */
    protected static function getManagedSkyVergeExtensionsApiUrl() : string
    {
        // @TODO: Remove and use proper endpoint declarations in consumers following deploy {JO 2021-02-17}
        return Configuration::get('mwc.extensions.api.url', '') ? StringHelper::trailingSlash(Configuration::get('mwc.extensions.api.url', '')).'extensions/' : '';
    }

    /**
     * Builds an instance of an extension using the data from SkyVerge Extensions API.
     *
     * @since x.y.z
     *
     * @param array $data extension data
     * @return AbstractExtension
     */
    protected static function buildManagedSkyVergeExtension(array $data) : AbstractExtension
    {
        return static::buildManagedExtension(new SkyVergeExtensionAdapter($data));
    }

    /**
     * Builds an instance of an extension using the data returned by the given adapter.
     *
     * @since x.y.z
     *
     * @param ExtensionAdapterContract $adapter data source adapter
     * @return AbstractExtension
     */
    protected static function buildManagedExtension(ExtensionAdapterContract $adapter) : AbstractExtension
    {
        if (ThemeExtension::TYPE === $adapter->getType()) {
            return (new ThemeExtension())->setProperties($adapter->convertFromSource());
        }

        return (new PluginExtension())->setProperties($adapter->convertFromSource());
    }

    /**
     * Gets the managed themes.
     *
     * @since x.y.z
     *
     * @return ThemeExtension[]
     * @throws Exception
     */
    public static function getManagedThemes() : array
    {
        return ArrayHelper::where(static::getManagedExtensions(), static function (AbstractExtension $extension) {
            return $extension->getType() === ThemeExtension::TYPE;
        }, false);
    }

    /**
     * Gets the Woo managed extensions.
     *
     * @since x.y.z
     *
     * @return AbstractExtension[]
     * @throws Exception
     */
    public static function getManagedWooExtensions() : array
    {
        return static::getManagedExtensionsFromCache('woocommerce', function () {
            return static::loadManagedWooExtensions();
        });
    }

    /**
     * Loads the WooCommerce managed extensions from the API.
     *
     * @since x.y.z
     *
     * @return array
     * @throws Exception
     */
    protected static function loadManagedWooExtensions(): array
    {
        return array_map(function ($data) {
            return static::buildManagedWooExtension($data);
        }, static::getManagedWooExtensionsData());
    }

    /**
     * Gets data for managed WooCommerce extensions.
     *
     * @since x.y.z
     *
     * @return array
     * @throws Exception
     */
    protected static function getManagedWooExtensionsData() : array
    {
        $response = (new GoDaddyRequest())
            ->url(static::getManagedWooExtensionsApiUrl())
            ->sslVerify(ManagedWooCommerceRepository::isProductionEnvironment())
            ->send();

        return ArrayHelper::get($response->getBody(), 'products', []);
    }

    /**
     * Gets the Woo extensions API URL.
     *
     * @since x.y.z
     *
     * @return string
     */
    protected static function getManagedWooExtensionsApiUrl() : string
    {
        $environment = ManagedWooCommerceRepository::getEnvironment();
        $environment_prefix = ! ManagedWooCommerceRepository::isProductionEnvironment() && $environment ? $environment.'-' : '';
        $api_url = StringHelper::replaceFirst(Configuration::get('godaddy.extensions.api.url', ''), '{environment_prefix}', $environment_prefix);
        $account_uid = Configuration::get('godaddy.account.uid', '');

        return "{$api_url}/sites/{$account_uid}/partner/a8c/woocommerce/info";
    }

    /**
     * Builds an instance of an extension using data from GoDaddy's WooCommerce Extensions API.
     *
     * @since x.y.z
     *
     * @param array $data extension data
     *
     * @return AbstractExtension
     */
    protected static function buildManagedWooExtension(array $data) : AbstractExtension
    {
        return static::buildManagedExtension(new WooCommerceExtensionAdapter($data));
    }

    /**
     * Gets available versions for the given extension.
     *
     * It currently returns data for extensions listed in the SkyVerge Extensions API only.
     *
     * @since x.y.z
     *
     * @param AbstractExtension $extension the extension object
     *
     * @return AbstractExtension[]
     */
    public static function getManagedExtensionVersions(AbstractExtension $extension)
    {
        if (! $extension->getId()) {
            return [$extension];
        }

        return array_map(static function ($data) {
            return static::buildManagedExtension(new SkyVergeExtensionAdapter($data));
        }, static::getManagedExtensionVersionsDataFromCache($extension));
    }

    /**
     * Gets version data for the given managed extension from cache.
     *
     * It the cache has no value, it attempts to get the data from the API.
     *
     * @since x.y.z
     *
     * @param AbstractExtension $extension the extension object
     *
     * @return array
     */
    protected static function getManagedExtensionVersionsDataFromCache(AbstractExtension $extension)
    {
        return static::getManagedExtensionsDataFromCache("versions.{$extension->getSlug()}", static function () use ($extension) {
            return static::loadManagedExtensionVersionsData($extension);
        });
    }

    /**
     * Gets managed extensions data from cache.
     *
     * If the cache has no value, it attempts to get the extensions invoking the given $loader.
     *
     * TODO: use this method from the getManaged[*]Extensions() methods to store raw data in the cache instead of objects {WV 2021-02-14}
     *
     * @since x.y.z
     *
     * @param string $key cache key
     * @param Closure $loader function to call
     *
     * @return array
     */
    protected static function getManagedExtensionsDataFromCache(string $key, Closure $loader)
    {
        $cache = Cache::extensions()->get([]);

        if (ArrayHelper::has($cache, $key)) {
            return ArrayHelper::get($cache, $key);
        }

        $data = $loader();

        ArrayHelper::set($cache, $key, $data);

        Cache::extensions()->set($cache);

        return $data;
    }

    /**
     * Loads data for the available versions of the given managed extension from the API.
     *
     * @since x.y.z
     *
     * @return array
     */
    protected static function loadManagedExtensionVersionsData(AbstractExtension $extension)
    {
        $response = (new GoDaddyRequest())
            ->query(['method' => 'GET'])
            ->url(static::getManagedExtensionVersionsApiUrl($extension))
            ->send();

        $versions = ArrayHelper::get($response->getBody(), 'data', []);

        usort($versions, static function ($a, $b) {
            return version_compare(ArrayHelper::get($a, 'version'), ArrayHelper::get($b, 'version'));
        });

        return static::addExtensionDataToVersionData($extension, $versions);
    }

    /**
     * Gets the URL for the endpoint used to retrieve available versions for a given extension.
     *
     * @since x.y.z
     *
     * @param AbstractExtension $extension the extension object
     * @param int $count the max number of versions to retrieve
     *
     * @return string
     */
    protected static function getManagedExtensionVersionsApiUrl(AbstractExtension $extension, int $count = 1000) : string
    {
        return StringHelper::trailingSlash(Configuration::get('mwc.extensions.api.url', ''))."extensions/{$extension->getId()}/versions?limit={$count}";
    }

    /**
     * Updates the version data with the values of the properties of the given extension.
     *
     * @since x.y.z
     *
     * @param AbstractExtension $extension the extension object
     * @param array $versions available versions data
     *
     * @return array
     */
    protected static function addExtensionDataToVersionData(AbstractExtension $extension, array $versions)
    {
        return array_map(static function ($version) use ($extension) {
            return [
                'extensionId'      => $extension->getId(),
                'slug'             => $extension->getSlug(),
                'label'            => $extension->getName(),
                'shortDescription' => $extension->getShortDescription(),
                'type'             => $extension->getType(),
                'category'         => $extension->getCategory(),
                'version'          => $version,
                'links'            => [
                    'homepage' => [
                        'href' => $extension->getHomepageUrl(),
                    ],
                    'documentation' => [
                        'href' => $extension->getDocumentationUrl(),
                    ],
                ],
            ];
        }, $versions);
    }
}
