<?php

namespace GoDaddy\WordPress\MWC\Common\Helpers;

use ArrayAccess;
use Exception;

/**
 * An helper to manipulate arrays.
 *
 * @since x.y.z
 */
class ArrayHelper
{
    /**
     * Determines if a given item is an accessible array.
     *
     * @since x.y.z
     *
     * @param mixed $value
     * @return bool
     */
    public static function accessible($value) : bool
    {
        return is_array($value) || $value instanceof ArrayAccess;
    }

    /**
     * Combines two array values.
     *
     * @NOTE: This provides special handling for when one of the values is null.
     *
     * @since x.y.z
     *
     * @param array|ArrayAccess|null $array original array
     * @param array $merge variable list of arrays to merge
     * @return array
     * @throws Exception
     */
    public static function combine($array, ...$merge) : array
    {
        if (! self::accessible($array)) {
            throw new Exception('The array provided as the original array was not accessible!');
        }

        foreach ($merge as $item) {
            if (! self::accessible($item)) {
                throw new Exception('One of the arrays provided to merge into the original was not accessible!');
            }
        }

        return array_merge($array, ...$merge);
    }

    /**
     * Combines two array values recursively to preserve nested keys.
     *
     * @NOTE: This provides special handling for when one of the values is null.
     *
     * @since x.y.z
     *
     * @param array|ArrayAccess|null $array original array
     * @param array $merge variable list of arrays to merge
     * @return array
     * @throws Exception
     */
    public static function combineRecursive($array, ...$merge) : array
    {
        if (! self::accessible($array)) {
            throw new Exception('The array provided as the original array was not accessible!');
        }

        foreach ($merge as $item) {
            if (! self::accessible($item)) {
                throw new Exception('One of the arrays provided to merge into the original was not accessible!');
            }
        }

        return array_replace_recursive($array, ...$merge);
    }

    /**
     * Determines if an array has a given value.
     *
     * @since x.y.z
     *
     * @param array $array
     * @param mixed $value
     * @return bool
     */
    public static function contains(array $array, $value) : bool
    {
        return self::exists(array_flip(self::flatten($array)), $value);
    }

    /**
     * Gets an array excluding the given keys.
     *
     * @since x.y.z
     *
     * @param array $array
     * @param array|string $keys
     * @return array
     */
    public static function except(array $array, $keys) : array
    {
        $temp = $array;

        self::remove($temp, self::wrap($keys));

        return $temp;
    }

    /**
     * Determines if an array key exists.
     *
     * @since x.y.z
     *
     * @param ArrayAccess|array $array
     * @param string|int $key
     * @return bool
     */
    public static function exists($array, $key) : bool
    {
        if ($array instanceof ArrayAccess) {
            return $array->offsetExists($key);
        }

        return array_key_exists($key, self::wrap($array));
    }

    /**
     * Flattens a multi-dimensional array.
     *
     * @since x.y.z
     *
     * @param array $array
     * @return array
     */
    public static function flatten(array $array) : array
    {
        $arrayValues = [];

        foreach ($array as $value) {
            if (is_scalar($value) || is_resource($value)) {
                $arrayValues[] = $value;
            } elseif (is_array($value)) {
                $arrayValues = array_merge($arrayValues, self::flatten($value));
            }
        }

        return $arrayValues;
    }

    /**
     * Gets an array value from a dot notated key.
     *
     * @since x.y.z
     *
     * @param ArrayAccess|array $array
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public static function get($array, string $key, $default = null)
    {
        if (! self::accessible($array)) {
            return $default;
        }

        foreach (explode('.', $key) as $segment) {
            if (! self::exists($array, $segment)) {
                return $default;
            }

            $array = $array[$segment];
        }

        return $array;
    }

    /**
     * Determines if an array has a nested key by dot notation.
     *
     * @since x.y.z
     *
     * @param ArrayAccess|array $array
     * @param string|array $keys
     * @return bool
     */
    public static function has($array, $keys) : bool
    {
        $keys = self::wrap($keys);

        // @TODO: Remove when PHP 8 is the minimum version and can support multiple function parameter type casting
        if (! $array || empty($keys)) {
            return false;
        }

        foreach ($keys as $key) {
            if (self::exists($array, $key)) {
                continue;
            }

            $subArray = $array;

            foreach (explode('.', $key) as $segment) {
                if (! self::accessible($subArray) || ! self::exists($subArray, $segment)) {
                    return false;
                }

                $subArray = $subArray[$segment];
            }
        }

        return true;
    }

    /**
     * Encodes an array to JSON.
     *
     * @since x.y.z
     *
     * @param ArrayAccess|array $array
     * @return string
     */
    public static function jsonEncode(array $array) : string
    {
        return json_encode($array, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
    }

    /**
     * Plucks values from an array given a key with optional key assignment.
     *
     * @NOTE: The WordPress function {@see wp_list_pluck()} does not support multi-dimensional arrays in a standard way.
     *
     * @since x.y.z
     *
     * @param array|ArrayAccess $array
     * @param string|array|int $search
     * @return array
     */
    public static function pluck($array, $search) : array
    {
        $results = [];

        foreach ($array as $item) {
            if ($value = self::get($item, $search)) {
                $results[] = $value;
            }
        }

        return $results;
    }

    /**
     * Converts the array into a query string.
     *
     * @NOTE: We use a custom function here instead of {@see add_query_arg()} because the WordPress function appends items to the current or given url.
     * That can cause problems when using this class for non-standard WordPress redirects.
     * This function uses the native {@see http_build_query()} instead.
     *
     * @since x.y.z
     *
     * @param array $array
     * @return string
     */
    public static function query(array $array) : string
    {
        return http_build_query($array, '', '&', PHP_QUERY_RFC3986);
    }

    /**
     * Removes a given key or keys from the original array.
     *
     * @since x.y.z
     *
     * @param array $array
     * @param array|string $keys
     */
    public static function remove(array &$array, $keys)
    {
        foreach (self::wrap($keys) as $key) {

            // if the key exists at this level unset and bail
            if (self::exists($array, $key)) {
                unset($array[$key]);

                continue;
            }

            // if the key doesn't exist at all then bail
            if (! self::has($array, $key)) {
                continue;
            }

            $temporary = &$array;
            $segments = explode('.', $key);
            $levels = count($segments);

            // key exists so lets walk to it
            foreach ($segments as $currentLevel => $segment) {
                if ($currentLevel <= $levels - 1) {
                    unset($temporary[$segment]);
                }

                $temporary = &$temporary[$segment];
            }
        }
    }

    /**
     * Sets an array value from dot notated key.
     *
     * @since x.y.z
     *
     * @param ArrayAccess|array $array
     * @param string $search
     * @param mixed $value
     * @return mixed|void|null
     */
    public static function set(&$array, string $search, $value = null)
    {
        if (! self::accessible($array)) {
            return;
        }

        foreach (explode('.', $search) as $segment) {
            if (! self::exists($array, $segment)) {
                $array[$segment] = [];
            }

            $array = &$array[$segment];
        }

        return $array = $value;
    }

    /**
     * Filters a given array by its callback.
     *
     * @since x.y.z
     *
     * @param array    $array
     * @param callable $callback
     * @param bool     $maintainIndex
     *
     * @return array
     */
    public static function where(array $array, callable $callback, bool $maintainIndex = true) : array
    {
        $array = array_filter($array, $callback, ARRAY_FILTER_USE_BOTH);

        return $maintainIndex ? $array : array_values($array);
    }

    /**
     * Wraps a given item in an array if it is not an array.
     *
     * @since x.y.z
     *
     * @param mixed $item
     * @return array
     */
    public static function wrap($item = null) : array
    {
        if (is_array($item)) {
            return $item;
        }

        return $item ? [$item] : [];
    }
}
