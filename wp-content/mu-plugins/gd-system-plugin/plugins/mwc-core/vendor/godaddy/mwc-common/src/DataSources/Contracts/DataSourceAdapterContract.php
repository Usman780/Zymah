<?php

namespace GoDaddy\WordPress\MWC\Common\DataSources\Contracts;

interface DataSourceAdapterContract
{
    /**
     * Converts from Data Source format.
     *
     * @since x.y.z
     *
     * @return array
     */
    public function convertFromSource() : array;

    /**
     * Converts to Data Source format.
     *
     * @since x.y.z
     *
     * @return array
     */
    public function convertToSource() : array;
}
