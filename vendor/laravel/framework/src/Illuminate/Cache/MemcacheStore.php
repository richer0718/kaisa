<?php

namespace Illuminate\Cache;

use Memcache;
use Illuminate\Contracts\Cache\Store;
use Carbon\Carbon;

class MemcacheStore extends TaggableStore implements Store
{
    /**
     * The Memcache instance.
     *
     * @var \Memcache
     */
    protected $memcache;

    /**
     * A string that should be prepended to keys.
     *
     * @var string
     */
    protected $prefix;

    /**
     * Indicates whether we are using Memcache version >= 3.0.0.
     *
     * @var bool
     */
    protected $onVersionThree;

    /**
     * Create a new Memcache store.
     *
     * @param  \Memcache  $memcache
     * @param  string      $prefix
     * @return void
     */
    public function __construct($memcache, $prefix = '')
    {
        $this->memcache = $memcache;
        $this->prefix = strlen($prefix) > 0 ? $prefix.':' : '';
    }

    /**
     * Retrieve an item from the cache by key.
     *
     * @param  string  $key
     * @return mixed
     */
    public function get($key)
    {

        return $this->memcache->get($this->prefix.$key);


    }

    /**
     * Retrieve multiple items from the cache by key.
     *
     * Items not found in the cache will have a null value.
     *
     * @param  array  $keys
     * @return array
     */
    public function many(array $keys)
    {
        $prefixedKeys = array_map(function ($key) {
            return $this->prefix.$key;
        }, $keys);

        $values = $this->memcache->getMulti($prefixedKeys, null, Memcache::GET_PRESERVE_ORDER);

        if ($this->memcache->getResultCode() != 0) {
            return array_fill_keys($keys, null);
        }

        return array_combine($keys, $values);
    }

    /**
     * Store an item in the cache for a given number of minutes.
     *
     * @param  string  $key
     * @param  mixed   $value
     * @param  float|int  $minutes
     * @return void
     */
    public function put($key, $value, $minutes)
    {
        //$compress = is_bool($value) || is_int($value) || is_float($value) ? false : MEMCACHE_COMPRESSED;
        $this->memcache->set($this->prefix.$key, $value, $this->toTimestamp($minutes));
    }

    /**
     * Store multiple items in the cache for a given number of minutes.
     *
     * @param  array  $values
     * @param  float|int  $minutes
     * @return void
     */
    public function putMany(array $values, $minutes)
    {
        $prefixedValues = [];

        foreach ($values as $key => $value) {
            $prefixedValues[$this->prefix.$key] = $value;
        }

        $this->memcache->setMulti($prefixedValues, $minutes * 60);
    }

    /**
     * Store an item in the cache if the key doesn't exist.
     *
     * @param  string  $key
     * @param  mixed   $value
     * @param  float|int  $minutes
     * @return bool
     */
    public function add($key, $value, $minutes)
    {
        return $this->memcache->add($this->prefix.$key, $value, $minutes * 60);
    }

    /**
     * Increment the value of an item in the cache.
     *
     * @param  string  $key
     * @param  mixed   $value
     * @return int|bool
     */
    public function increment($key, $value = 1)
    {
        return $this->memcache->increment($this->prefix.$key, $value);
    }

    /**
     * Decrement the value of an item in the cache.
     *
     * @param  string  $key
     * @param  mixed   $value
     * @return int|bool
     */
    public function decrement($key, $value = 1)
    {
        return $this->memcache->decrement($this->prefix.$key, $value);
    }

    /**
     * Store an item in the cache indefinitely.
     *
     * @param  string  $key
     * @param  mixed   $value
     * @return void
     */
    public function forever($key, $value)
    {
        $this->put($key, $value, 0);
    }

    /**
     * Remove an item from the cache.
     *
     * @param  string  $key
     * @return bool
     */
    public function forget($key)
    {
        return $this->memcache->delete($this->prefix.$key);
    }

    /**
     * Remove all items from the cache.
     *
     * @return bool
     */
    public function flush()
    {
        return $this->memcache->flush();
    }

    /**
     * Get the UNIX timestamp for the given number of minutes.
     *
     * @param  int  $minutes
     * @return int
     */
    protected function toTimestamp($minutes)
    {
        return $minutes > 0 ? Carbon::now()->addSeconds($minutes * 60)->getTimestamp() : 0;
    }

    /**
     * Get the underlying memcache connection.
     *
     * @return \memcache
     */
    public function getmemcache()
    {
        return $this->memcache;
    }

    /**
     * Get the cache key prefix.
     *
     * @return string
     */
    public function getPrefix()
    {
        return $this->prefix;
    }

    /**
     * Set the cache key prefix.
     *
     * @param  string  $prefix
     * @return void
     */
    public function setPrefix($prefix)
    {
        $this->prefix = ! empty($prefix) ? $prefix.':' : '';
    }
}
