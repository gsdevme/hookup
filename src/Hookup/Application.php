<?php

namespace Hookup;

use Hookup\Command\UpdateCommand;
use Hookup\Configuration\Remote\GithubConfigurationProvider;
use Hookup\Loader\YamlLoader;
use League\Flysystem\Adapter\Local;
use League\Flysystem\Filesystem;
use League\Plates\Engine;
use Symfony\Component\Console\Application as BaseApplication;

class Application extends BaseApplication
{
    public function __construct($name, $version)
    {
        parent::__construct($name, $version);

        // Cheap mans DIC
        $fileSystem = new Filesystem(new Local('/'));
        $yamlLoader = new YamlLoader($fileSystem);

        $engine = new Engine(realpath(__DIR__) . '/templates');
        $engine->registerFunction('write', function ($s, $v) {
            if ($v !== null) {
                return sprintf($s, $v) . PHP_EOL;
            }
        });

        $githubConfigurationProvider = new GithubConfigurationProvider();

        $this->addCommands([
            new UpdateCommand($fileSystem, $yamlLoader, $engine, $githubConfigurationProvider)
        ]);
    }

}
