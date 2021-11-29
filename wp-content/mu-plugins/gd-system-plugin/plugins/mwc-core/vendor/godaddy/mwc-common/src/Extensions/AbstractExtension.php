<?php

namespace GoDaddy\WordPress\MWC\Common\Extensions;

use Exception;
use GoDaddy\WordPress\MWC\Common\Repositories\WordPressRepository;
use GoDaddy\WordPress\MWC\Common\Traits\CanBulkAssignPropertiesTrait;
use GoDaddy\WordPress\MWC\Common\Traits\CanConvertToArrayTrait;

/**
 * Abstract extension class.
 *
 * Represents an extension to the WordPress base, such as a plugin or theme.
 *
 * @since x.y.z
 */
abstract class AbstractExtension
{
    use CanBulkAssignPropertiesTrait;
    use CanConvertToArrayTrait;

    /** @var string|null The ID, if any */
    protected $id;

    /** @var string|null The slug */
    protected $slug;

    /** @var string|null The name */
    protected $name;

    /** @var string|null The short description */
    protected $shortDescription;

    /** @var string|null The extension type */
    protected $type;

    /** @var string|null The slug of an assigned category, if any */
    protected $category;

    /** @var string|null The version number */
    protected $version;

    /** @var string|null The UNIX timestamp representing when the extension was last updated */
    protected $lastUpdated;

    /** @var string|null The minimum version of PHP required to run the extension */
    protected $minimumPhpVersion;

    /** @var string|null The minimum version of WordPress required to run the extension */
    protected $minimumWordPressVersion;

    /** @var string|null The minimum version of WooCommerce required to run the extension */
    protected $minimumWooCommerceVersion;

    /** @var string|null The URL to download the extension package */
    protected $packageUrl;

    /** @var string|null The URL for the extension's homepage */
    protected $homepageUrl;

    /** @var string|null The URL for the extension's documentation */
    protected $documentationUrl;

    /**
     * Gets the ID.
     *
     * @since x.y.z
     *
     * @return string|null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Gets the slug.
     *
     * @since x.y.z
     *
     * @return string|null
     */
    public function getSlug()
    {
        return $this->slug;
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
        return $this->name;
    }

    /**
     * Gets the short description.
     *
     * @since x.y.z
     *
     * @return string|null
     */
    public function getShortDescription()
    {
        return $this->shortDescription;
    }

    /**
     * Gets the type.
     *
     * @since x.y.z
     *
     * @return string|null
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Gets the category.
     *
     * @since x.y.z
     *
     * @return string|null
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Gets the version.
     *
     * @since x.y.z
     *
     * @return string|null
     */
    public function getVersion()
    {
        return $this->version;
    }

    /**
     * Gets the timestamp representing when the asset was last updated.
     *
     * @since x.y.z
     *
     * @return int|null
     */
    public function getLastUpdated()
    {
        return $this->lastUpdated;
    }

    /**
     * Gets the minimum required PHP version to use this asset.
     *
     * @since x.y.z
     *
     * @return string|null
     */
    public function getMinimumPHPVersion()
    {
        return $this->minimumPhpVersion;
    }

    /**
     * Gets the minimum required WordPress version to use this asset.
     *
     * @since x.y.z
     *
     * @return string|null
     */
    public function getMinimumWordPressVersion()
    {
        return $this->minimumWordPressVersion;
    }

    /**
     * Gets the minimum required WooCommerce version to use this asset.
     *
     * @since x.y.z
     *
     * @return string|null
     */
    public function getMinimumWooCommerceVersion()
    {
        return $this->minimumWooCommerceVersion;
    }

    /**
     * Gets the package URL.
     *
     * @since x.y.z
     *
     * @return string|null
     */
    public function getPackageUrl()
    {
        return $this->packageUrl;
    }

    /**
     * Gets the homepage URL.
     *
     * @since x.y.z
     *
     * @return string|null
     */
    public function getHomepageUrl()
    {
        return $this->homepageUrl;
    }

    /**
     * Gets the documentation URL.
     *
     * @since x.y.z
     *
     * @return string|null
     */
    public function getDocumentationUrl()
    {
        return $this->documentationUrl;
    }

    /**
     * Sets the ID.
     *
     * @since x.y.z
     *
     * @param string $value value to set
     * @return self
     */
    public function setId(string $value) : self
    {
        $this->id = $value;

        return $this;
    }

    /**
     * Sets the slug.
     *
     * @since x.y.z
     *
     * @param string $value value to set
     * @return self
     */
    public function setSlug(string $value) : self
    {
        $this->slug = $value;

        return $this;
    }

    /**
     * Sets the name.
     *
     * @since x.y.z
     *
     * @param string $value value to set
     * @return self
     */
    public function setName(string $value) : self
    {
        $this->name = $value;

        return $this;
    }

    /**
     * Sets the short description.
     *
     * @since x.y.z
     *
     * @param string $value value to set
     * @return self
     */
    public function setShortDescription(string $value) : self
    {
        $this->shortDescription = $value;

        return $this;
    }

    /**
     * Sets the type.
     *
     * @since x.y.z
     *
     * @param string $value value to set
     * @return self
     */
    public function setType(string $value) : self
    {
        $this->type = $value;

        return $this;
    }

    /**
     * Sets the category.
     *
     * @since x.y.z
     *
     * @param string $value value to set
     * @return self
     */
    public function setCategory(string $value) : self
    {
        $this->category = $value;

        return $this;
    }

    /**
     * Sets the version.
     *
     * @since x.y.z
     *
     * @param string $value value to set
     * @return self
     */
    public function setVersion(string $value) : self
    {
        $this->version = $value;

        return $this;
    }

    /**
     * Sets the time the asset was last updated.
     *
     * @since x.y.z
     *
     * @param int $value value to set, as a UTC timestamp
     * @return self
     */
    public function setLastUpdated(int $value) : self
    {
        $this->lastUpdated = $value;

        return $this;
    }

    /**
     * Sets the minimum PHP version required to use this asset.
     *
     * @since x.y.z
     *
     * @param string $value value to set
     * @return self
     */
    public function setMinimumPHPVersion(string $value) : self
    {
        $this->minimumPhpVersion = $value;

        return $this;
    }

    /**
     * Sets the minimum WordPress version required to use this asset.
     *
     * @since x.y.z
     *
     * @param string $value value to set
     * @return self
     */
    public function setMinimumWordPressVersion(string $value) : self
    {
        $this->minimumWordPressVersion = $value;

        return $this;
    }

    /**
     * Sets the minimum WooCommerce version required to use this asset.
     *
     * @since x.y.z
     *
     * @param string $value value to set
     * @return self
     */
    public function setMinimumWooCommerceVersion(string $value) : self
    {
        $this->minimumWooCommerceVersion = $value;

        return $this;
    }

    /**
     * Sets the package URL.
     *
     * @since x.y.z
     *
     * @param string $value value to set
     * @return self
     */
    public function setPackageUrl(string $value) : self
    {
        $this->packageUrl = $value;

        return $this;
    }

    /**
     * Sets the homepage URL.
     *
     * @since x.y.z
     *
     * @param string $value value to set
     * @return self
     */
    public function setHomepageUrl(string $value) : self
    {
        $this->homepageUrl = $value;

        return $this;
    }

    /**
     * Sets the documentation URL.
     *
     * @since x.y.z
     *
     * @param string $value value to set
     * @return self
     */
    public function setDocumentationUrl(string $value) : self
    {
        $this->documentationUrl = $value;

        return $this;
    }

    /**
     * Downloads the extension.
     *
     * @NOTE Methods calling this function need to {@see unlink()} the temporary file returned by {@see download_url()}.
     *
     * @since x.y.z
     *
     * @return string temporary filename
     * @throws Exception
     */
    public function download() : string
    {
        WordPressRepository::requireWordPressFilesystem();

        $downloadable = download_url($this->getPackageUrl());

        if (is_a($downloadable, '\WP_Error', true)) {
            throw new Exception($downloadable->get_error_message());
        }

        return $downloadable;
    }

    /**
     * Activates the extension.
     *
     * @since x.y.z
     *
     * @return void
     */
    abstract public function activate();

    /**
     * Determines whether the extension is active.
     *
     * @since x.y.z
     *
     * @return bool
     */
    abstract public function isActive() : bool;

    /**
     * Deactivates the extension.
     *
     * @since x.y.z
     *
     * @return void
     */
    abstract public function deactivate();

    /**
     * Installs the extension.
     *
     * @since x.y.z
     *
     * @return void
     */
    abstract public function install();

    /**
     * Determines if the extension is installed.
     *
     * @since x.y.z
     *
     * @return bool
     */
    abstract public function isInstalled() : bool;

    /**
     * Uninstalls the Extension.
     *
     * @since x.y.z
     *
     * @return void
     */
    abstract public function uninstall();
}
