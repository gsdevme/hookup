<?php

namespace Hookup\Model;

class Server
{
    /**
     * @var string
     */
    private $host;

    /**
     * @var string
     */
    private $hostname;

    /**
     * @var int
     */
    private $port;

    /**
     * @var string|null
     */
    private $user;

    /**
     * @var string|null
     */
    private $identityFile;

    /**
     * @var string|null
     */
    private $localForward;

    /**
     * @var string|null
     */
    private $proxyCommand;

    /**
     * Server constructor.
     * @param string $host
     * @param string $hostname
     * @param int $port
     * @param null|string $user
     * @param null|string $identityFile
     * @param null|string $localForward
     * @param null|string $proxyCommand
     */
    public function __construct(
        string $host,
        string $hostname,
        int $port,
        string $user = null,
        string $identityFile = null,
        string $localForward = null,
        string $proxyCommand = null
    ) {
        $this->host = $host;
        $this->hostname = $hostname;
        $this->port = $port;
        $this->user = $user;
        $this->identityFile = $identityFile;
        $this->localForward = $localForward;
        $this->proxyCommand = $proxyCommand;
    }

    /**
     * @return string
     */
    public function getHost()
    {
        return $this->host;
    }

    /**
     * @return string
     */
    public function getHostname()
    {
        return $this->hostname;
    }

    /**
     * @return int
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * @return null|string
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @return null|string
     */
    public function getIdentityFile()
    {
        return $this->identityFile;
    }

    /**
     * @return null|string
     */
    public function getLocalForward()
    {
        return $this->localForward;
    }

    /**
     * @return null|string
     */
    public function getProxyCommand()
    {
        return $this->proxyCommand;
    }

}
