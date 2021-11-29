<?php

namespace GoDaddy\WordPress\MWC\Common\Cache\Types;

use GoDaddy\WordPress\MWC\Common\Cache\Cache;
use GoDaddy\WordPress\MWC\Common\Cache\Contracts\CacheableContract;

final class CacheConfigurations extends Cache implements CacheableContract
{
    /**
     * How long in seconds should the cache be kept for.
     *
     * @NOTE: Static caches are reset on each page change and will not have an expiry set.
     * Databases will respect the expiry.
     *
     * @since x.y.z
     *
     * @var int seconds
     */
    protected $expires = 3600;

    /**
     * The cache key.
     *
     * @var string
     */
    protected $key = 'configurations';

    /**
     * Constructor.
     *
     * @since x.y.z
     */
    public function __construct()
    {
        $this->type('configurations');
    }

    /**
     * Clears the persisted store.
     *
     * @since x.y.z
     *
     * @NOTE: Configurations are need for checking if a WordPress instance exists, so we need to assume that will be cleared out here and handle this clear manually.
     */
    protected function clearPersisted()
    {
        delete_transient($this->getKey());
    }
}
