<?php

namespace GoDaddy\WordPress\MWC\Common\Plugin;

use Exception;
use GoDaddy\WordPress\MWC\Common\Configuration\Configuration;
use GoDaddy\WordPress\MWC\Common\Helpers\ArrayHelper;
use GoDaddy\WordPress\MWC\Common\Helpers\StringHelper;
use GoDaddy\WordPress\MWC\Common\Repositories\WordPressRepository;

/**
 * Class BasePlatformPlugin.
 *
 * @since x.y.z
 */
class BasePlatformPlugin
{
    /**
     * Classes to instantiate.
     *
     * @var array
     */
    protected $classesToInstantiate;

    /**
     * Configuration Values.
     *
     * @var array
     */
    protected $configurationValues;

    /**
     * Configuration directories.
     *
     * @var array
     */
    protected $configurationDirectories = ['configurations'];

    /**
     * Plugin Name.
     *
     * @var string
     */
    protected $name;

    /**
     * Class constructor.
     *
     * @since x.y.z
     *
     * @throws Exception
     */
    public function __construct()
    {
        // @NOTE: Load configurations so that they are cached - Should always be called first
        $this->initializeConfiguration();

        WordPressRepository::requireWordPressInstance();

        // @NOTE: Make sure all PHP constants are set
        $this->instantiateConfigurationValues();
        Configuration::reload();

        // @NOTE: Instantiate required classes
        $this->instantiatePluginClasses();
    }

    /**
     * Initializes the Configuration class and loads the configuration values.
     *
     * @since x.y.z
     */
    protected function initializeConfiguration()
    {
        Configuration::initialize($this->getConfigurationDirectories());
    }

    /**
     * Gets the classes that should be instantiated when initializing the inheriting plugin.
     *
     * @NOTE: This is here so it can be overridden if needed before setting values
     *
     * @since x.y.z
     *
     * @return array
     */
    protected function getClassesToInstantiate() : array
    {
        return ArrayHelper::wrap($this->classesToInstantiate);
    }

    /**
     * Gets configuration values.
     *
     * @NOTE: This is here so it can be overridden if needed before setting values.
     *
     * @since x.y.z
     *
     * @return array
     */
    protected function getConfigurationValues() : array
    {
        return ArrayHelper::wrap($this->configurationValues);
    }

    /**
     * Gets configuration directories.
     *
     * @NOTE: This is here so it can be overridden if needed before setting values.
     *
     * @since x.y.z
     *
     * @return array
     */
    protected function getConfigurationDirectories() : array
    {
        $directories = [];
        $sourceDirectory = StringHelper::before(__DIR__, 'src');

        foreach (ArrayHelper::wrap($this->configurationDirectories) as $directory) {
            $directories[] = StringHelper::trailingSlash($sourceDirectory.$directory);
        }

        return $directories;
    }

    /**
     * Gets plugin prefix.
     *
     * @since x.y.z
     *
     * @return string
     * @throws Exception
     */
    protected function getPluginPrefix() : string
    {
        $pluginName = $this->name ?: StringHelper::afterLast(Configuration::get('wordpress.absolute_path'), '/');

        return strtoupper($pluginName);
    }

    /**
     * Instantiates the plugin constants and configuration values.
     *
     * @since x.y.z
     *
     * @throws Exception
     */
    protected function instantiateConfigurationValues()
    {
        foreach ($this->getConfigurationValues() as $key => $value) {
            $this->defineConfigurationConstant($key, $value);
        }
    }

    /**
     * Safely converts the platform's configuration into global constant.
     *
     * @since x.y.z
     *
     * @param string $configurationName
     * @param string $configurationValue
     *
     * @throws Exception
     */
    protected function defineConfigurationConstant(string $configurationName, string $configurationValue)
    {
        $pluginPrefix = $this->getPluginPrefix();
        $constantName = strtoupper(StringHelper::snakeCase(strtolower("{$pluginPrefix} {$configurationName}")));

        if (! defined($constantName)) {
            define($constantName, $configurationValue);
        }
    }

    /**
     * Instantiates the plugin specific classes.
     *
     * @since x.y.z
     */
    protected function instantiatePluginClasses()
    {
        foreach ($this->getClassesToInstantiate() as $class => $mode) {
            if (is_bool($mode) && $mode) {
                new $class();
            }

            if ($mode === 'cli' && WordPressRepository::isCliMode()) {
                new $class();
            }

            if ($mode === 'web' && ! WordPressRepository::isCliMode()) {
                new $class();
            }
        }
    }
}
