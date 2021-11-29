<?php

namespace GoDaddy\WordPress\MWC\Common\Extensions\Types;

use Exception;
use GoDaddy\WordPress\MWC\Common\Configuration\Configuration;
use GoDaddy\WordPress\MWC\Common\Extensions\AbstractExtension;
use GoDaddy\WordPress\MWC\Common\Helpers\ArrayHelper;
use GoDaddy\WordPress\MWC\Common\Helpers\StringHelper;
use GoDaddy\WordPress\MWC\Common\Repositories\WordPressRepository;

/**
 * The plugin extension class.
 *
 * @since x.y.z
 */
class PluginExtension extends AbstractExtension
{
    /** @var string asset type */
    const TYPE = 'plugin';

    /** @var string|null The plugin's basename, e.g. some-plugin/some-plugin.php */
    protected $basename;

    /** @var string|null the extension install path */
    protected $installPath;

    /** @var array key-value list of available icon URLs */
    protected $imageUrls = [];

    /**
     * Plugin constructor.
     *
     * @since x.y.z
     */
    public function __construct()
    {
        $this->type = self::TYPE;
        $this->installPath = Configuration::get('wordpress.plugins_directory');
    }

    /**
     * Gets the plugin basename.
     *
     * e.g. woocommerce-plugin/woocommerce-plugin.php
     *
     * @since x.y.z
     *
     * @return string|null
     */
    public function getBasename()
    {
        return $this->basename;
    }

    /**
     * Gets the image URLs.
     *
     * @since x.y.z
     *
     * @return array
     */
    public function getImageUrls() : array
    {
        return $this->imageUrls;
    }

    /**
     * Gets the plugin install path.
     *
     * @since x.y.z
     *
     * @return string|null
     */
    public function getInstallPath()
    {
        return $this->installPath;
    }

    /**
     * Gets the currently installed version or returns null.
     *
     * @since x.y.z
     *
     * @return string|null
     */
    public function getInstalledVersion()
    {
        try {
            WordPressRepository::requireWordPressFilesystem();
        } catch (Exception $exception) {
            // assume the plugin is not installed to avoid changing the contract of the method to start throwing an exception
            return;
        }

        if (! $this->isInstalled()) {
            return;
        }

        return ArrayHelper::get(get_plugin_data(StringHelper::trailingSlash($this->getInstallPath()).$this->getBasename()), 'Version');
    }

    /**
     * Gets the name.
     *
     * @since x.y.z
     *
     * @return string|null
     */
    public function getName()
    {
        if ($this->name && StringHelper::startsWith($this->name, 'WooCommerce')) {
            return trim(StringHelper::after($this->name, 'WooCommerce'));
        }

        return $this->name;
    }

    /**
     * Sets the plugin basename.
     *
     * e.g. woocommerce-plugin/woocommerce-plugin.php
     *
     * @since x.y.z
     *
     * @param string $value basename value to set
     * @return self
     */
    public function setBasename(string $value) : self
    {
        $this->basename = $value;

        return $this;
    }

    /**
     * Sets the image URLs.
     *
     * @since x.y.z
     *
     * @param string[] $urls URLs to set
     *
     * @return self
     */
    public function setImageUrls(array $urls) : self
    {
        $this->imageUrls = $urls;

        return $this;
    }

    /**
     * Activates the plugin.
     *
     * @since x.y.z
     *
     * @throws Exception
     * @returns void
     */
    public function activate()
    {
        try {
            WordPressRepository::requireWordPressFilesystem();
        } catch (Exception $exception) {
            throw new Exception(sprintf('%1$s could not be successfully activated: %2$s', $this->getName() ?? 'The plugin', $exception->getMessage()), 0, $exception);
        }

        if (! $this->isInstalled()) {
            throw new Exception(sprintf('Cannot activate %s: the plugin is not installed.', $this->getName() ?? 'the plugin'));
        }

        $activated = activate_plugin($this->getBasename());

        if (is_a($activated, '\WP_Error')) {
            throw new Exception(sprintf('An error occurred during %1$s activation: %2$s', $this->getName() ?? 'plugin', $activated->get_error_message()));
        }
    }

    /**
     * Determines whether the plugin is active.
     *
     * @since x.y.z
     *
     * @return bool
     */
    public function isActive() : bool
    {
        try {
            WordPressRepository::requireWordPressFilesystem();
        } catch (Exception $exception) {
            // assume the plugin is not active to avoid changing the contract of the method to start throwing an exception
            return false;
        }

        return (bool) is_plugin_active($this->getBasename());
    }

    /**
     * Deactivates the plugin.
     *
     * @since x.y.z
     */
    public function deactivate()
    {
        try {
            WordPressRepository::requireWordPressFilesystem();
        } catch (Exception $exception) {
            // bail to avoid changing the contract of the method to start throwing an exception
            // TODO: throw an exception and update usages of this method to handle the exception appropriately {WV 2020-02-17}
            return;
        }

        deactivate_plugins($this->getBasename());
    }

    /**
     * Installs the plugin.
     *
     * @since x.y.z
     *
     * @throws Exception
     */
    public function install()
    {
        $downloadable = $this->download();

        try {
            WordPressRepository::requireWordPressFilesystem();
        } catch (Exception $exception) {
            throw new Exception(sprintf('%1$s could not be successfully installed: %2$s', $this->getName() ?? 'The plugin', $exception->getMessage()), 0, $exception);
        }

        $result = unzip_file($downloadable, $this->installPath);

        unlink($downloadable);

        if (is_a($result, '\WP_Error')) {
            throw new Exception(sprintf('%1$s could not be successfully installed: %2$s.', $this->getName() ?? 'The plugin', $result->get_error_message()));
        }

        if (! $this->isInstalled()) {
            throw new Exception(sprintf('%s could not be successfully installed.', $this->getName() ?? 'The plugin'));
        }
    }

    /**
     * Determines if the plugin is installed.
     *
     * @since x.y.z
     *
     * @return bool
     */
    public function isInstalled() : bool
    {
        return $this->installPath && $this->getBasename() && is_readable(StringHelper::trailingSlash($this->installPath).$this->getBasename());
    }

    /**
     * Uninstall the Plugin.
     *
     * Implementation adapted from {@see wp_ajax_delete_plugin()}.
     *
     * @since x.y.z
     */
    public function uninstall()
    {
        if (! $this->isInstalled()) {
            return;
        }

        if ($this->isActive()) {
            $this->deactivate();
        }

        // Check filesystem credentials first because `delete_plugins()` will terminate the PHP process if credentials cannot be retrieved or are invalid.
        try {
            WordPressRepository::requireWordPressFilesystem();
        } catch (Exception $exception) {
            throw new Exception(sprintf('%1$s could not be successfully uninstalled: %2$s', $this->getName() ?? 'The plugin', $exception->getMessage()), 0, $exception);
        }

        $result = delete_plugins([$this->getBasename()]);

        if (is_a($result, '\WP_Error')) {
            throw new Exception(sprintf('%1$s could not be successfully uninstalled: %2$s.', $this->getName() ?? 'The plugin', $result->get_error_message()));
        }

        if ($this->isInstalled()) {
            throw new Exception(sprintf('%s could not be successfully uninstalled.', $this->getName() ?? 'The plugin'));
        }
    }
}
