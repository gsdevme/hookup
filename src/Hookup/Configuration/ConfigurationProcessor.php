<?php

namespace Hookup\Configuration;

use Doctrine\Common\Collections\ArrayCollection;
use Hookup\Configuration\Factory\ServerFactory;
use Symfony\Component\Config\Definition\Processor;

class ConfigurationProcessor
{
    /**
     * @var ServerFactory
     */
    private $serverFactory;

    /**
     * ConfigurationProcessor constructor.
     */
    public function __construct()
    {
        $this->serverFactory = new ServerFactory();
    }

    /**
     * @param array $configs
     * @return array
     */
    public function process(array $configs): array
    {
        $processor = new Processor();
        $configuration = new Configuration();
        $processedConfiguration = $processor->processConfiguration(
            $configuration,
            $configs
        );

        return $processedConfiguration;
    }

    /**
     * @param array $processedConfiguration
     * @return array
     */
    public function finalize(array $processedConfiguration): array
    {
        $servers = new ArrayCollection();
        foreach ($processedConfiguration['servers'] as $host => $config) {
            $config['host'] = $host;

            if ($config['user'] === null && $processedConfiguration['user'] !== null) {
                $config['user'] = $processedConfiguration['user'];
            }

            $servers->add($this->serverFactory->createFromArray($config));
        }

        $processedConfiguration['servers'] = $servers;

        return $processedConfiguration;
    }
}
