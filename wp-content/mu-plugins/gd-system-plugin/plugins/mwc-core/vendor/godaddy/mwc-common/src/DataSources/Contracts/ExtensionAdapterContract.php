<?php

namespace GoDaddy\WordPress\MWC\Common\DataSources\Contracts;

interface ExtensionAdapterContract extends DataSourceAdapterContract
{
    /**
     * Gets the type of the extension.
     *
     * @since x.y.z
     *
     * @return string|null
     */
    public function getType();

    /**
     * Gets the image URLs.
     *
     * @return string[]
     */
    public function getImageUrls() : array;
}
