<?php

namespace Hookup\Configuration\Factory;

use Hookup\Model\Server;

class ServerFactory
{
    /**
     * @param array $server
     * @return Server
     */
    public function createFromArray(array $server): Server
    {
        if (!isset($server['user'])) {
            $server['user'] = null;
        }

        return new Server($server['host'], $server['hostname'], $server['port'], $server['user']);
    }
}
