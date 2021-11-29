<?php

namespace GoDaddy\WordPress\MWC\Common\Cache;

use GoDaddy\WordPress\MWC\Common\Cache\Types\CacheConfigurations;
use GoDaddy\WordPress\MWC\Common\Cache\Types\CacheExtensions;
use GoDaddy\WordPress\MWC\Common\Helpers\ArrayHelper;
use GoDaddy\WordPress\MWC\Common\Repositories\WordPressRepository;

class Cache
{
    /**
     * The current static cache instance.
     * @NOTE: This is always checked first before checking for the
     * persistent database cache.
     *
     * @var array
     */
    protected static $cache = [];

    /**
     * How long in seconds should the cache be kept for.
     * @NOTE: Static caches are reset on each page change and will not have
     * an expiry set.  Databases will respect the expiry.
     *
     * @var int
     */
    protected $expires;

    /**
     * The cache key.
     *
     * @var string
     */
    protected $key = 'system';

    /**
     * The cache key prefix applied to subclass keys.
     *
     * @var string
     */
    protected $keyPrefix = 'gd_';

    /**
     * The type of object we are caching.
     *
     * @var string
     */
    protected $type;

    /**
     * Create an instance for caching configurations.
     *
     * @return CacheConfigurations
     */
    public static function configurations() : CacheConfigurations
    {
        return new CacheConfigurations();
    }

    /**
     * Create an instance for caching extensions.
     *
     * @return CacheExtensions
     */
    public static function extensions(): CacheExtensions
    {
        return new CacheExtensions();
    }

    /**
     * Clears the current cache.
     *
     * @NOTE: The persisted stores may rely on configurations so be sure to clear them first before their dependencies
     *
     * @since x.y.z
     *
     * @param bool $persisted
     */
    public function clear(bool $persisted = true)
    {
        if ($persisted) {
            $this->clearPersisted();
        }

        ArrayHelper::remove(self::$cache, $this->getKey());
    }

    /**
     * Clears the persisted store.
     *
     * @since x.y.z
     */
    protected function clearPersisted()
    {
        if (WordPressRepository::hasWordPressInstance()) {
            delete_transient($this->getKey());
        }
    }

    /**
     * Sets when the cache should expire.
     *
     * @since x.y.z
     *
     * @param int $seconds
     * @return Cache
     */
    public function expires(int $seconds) : self
    {
        $this->expires = $seconds;

        return $this;
    }

    /**
     * Get a cached value from the static store.
     *
     * @since x.y.z
     *
     * @param $default
     * @return mixed|null
     */
    public function get($default = null)
    {
        if (ArrayHelper::has(self::$cache, $this->getKey())) {
            return ArrayHelper::get(self::$cache, $this->getKey(), $default);
        }

        return $this->getPersisted() ?: $default;
    }

    /**
     * Gets a cached value from the persisted store.
     *
     * @since x.y.z
     *
     * @return mixed|null
     */
    public function getPersisted()
    {
        if ($value = get_transient($this->getKey())) {
            $this->set($value, false);
        }

        return $value;
    }

    /**
     * Get the full key string.
     *
     * @since x.y.z
     *
     * @return string
     */
    public function getKey() : string
    {
        return "{$this->keyPrefix}{$this->key}";
    }

    /**
     * Sets what key the data will be stored in within the cache.
     *
     * @since x.y.z
     *
     * @param string $key
     * @return Cache
     */
    public function key(string $key) : self
    {
        $this->key = $key;

        return $this;
    }

    /**
     * Set a value in the cache.
     *
     * @since x.y.z
     *
     * @param mixed $value
     * @param bool $persisted
     */
    public function set($value, bool $persisted = true)
    {
        ArrayHelper::set(self::$cache, $this->getKey(), $value);

        if ($persisted) {
            $this->setPersisted($value);
        }
    }

    /**
     * Set a value in the persisted store.
     *
     * @since x.y.z
     *
     * @param mixed $value
     */
    protected function setPersisted($value)
    {
        if (WordPressRepository::hasWordPressInstance()) {
            set_transient($this->getKey(), $value, $this->expires);
        }
    }

    /**
     * Sets the type of data being cached.
     *
     * @since x.y.z
     *
     * @param string $type
     * @return Cache
     */
    public function type(string $type) : self
    {
        $this->type = $type;

        return $this;
    }
}
