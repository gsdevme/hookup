<?php

namespace Hookup\Configuration\Remote;

interface RemoteConfigurationProviderInterface
{
    /**
     * @param string $name
     * @param array $config
     * @return array
     */
    public function get(string $name, array $config): array;
}
