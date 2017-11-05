<?php

namespace Illuminate\Cache;

use Memcache;

class MemcacheConnector
{
    /**
     * Create a new Memcache connection.
     *
     * @param  array  $servers
     * @param  string|null  $connectionId
     * @param  array  $options
     * @param  array  $credentials
     * @return \Memcache
     */
    public function connect(array $servers, $connectionId = null, array $options = [], array $credentials = [])
    {
        $memcache = $this->getMemcache(
            $connectionId, $credentials, $options
        );

        foreach ($servers as $server)
        {
            $memcache->addServer($server['host'], $server['port'], $server['weight']);
        }

        if ($memcache->getVersion() === false)
        {
            throw new RuntimeException("Could not establish Memcache connection.");
        }

        return $memcache;
    }

    /**
     * Get a new Memcache instance.
     *
     * @param  string|null  $connectionId
     * @param  array  $credentials
     * @param  array  $options
     * @return \Memcache
     */
    protected function getMemcache($connectionId, array $credentials, array $options)
    {
        $memcache = $this->createMemcacheInstance($connectionId);

        if (count($credentials) == 2) {
            $this->setCredentials($memcache, $credentials);
        }

        if (count($options)) {
            $memcache->setOptions($options);
        }

        return $memcache;
    }

    /**
     * Create the Memcache instance.
     *
     * @param  string|null  $connectionId
     * @return \Memcache
     */
    protected function createMemcacheInstance($connectionId)
    {
        return empty($connectionId) ? new Memcache : new Memcache($connectionId);
    }

    /**
     * Set the SASL credentials on the Memcache connection.
     *
     * @param  \Memcache  $memcache
     * @param  array  $credentials
     * @return void
     */
    protected function setCredentials($memcache, $credentials)
    {
        list($username, $password) = $credentials;

        $memcache->setOption(Memcache::OPT_BINARY_PROTOCOL, true);

        $memcache->setSaslAuthData($username, $password);
    }
}
