<?php

namespace Hookup\Command;

use Hookup\Configuration\ConfigurationProcessor;
use Hookup\Configuration\Remote\RemoteConfigurationProviderInterface;
use Hookup\Loader\YamlLoader;
use Hookup\Locator\FileLocator;
use League\Flysystem\FilesystemInterface;
use League\Plates\Engine;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class UpdateCommand extends Command
{
    /**
     * @var FilesystemInterface
     */
    private $filesystem;

    /**
     * @var YamlLoader
     */
    private $yamlLoader;

    /**
     * @var Engine
     */
    private $engine;
    /**
     * @var RemoteConfigurationProviderInterface
     */
    private $remoteConfigurationProvider;

    /**
     * UpdateCommand constructor.
     * @param FilesystemInterface $filesystem
     * @param YamlLoader $yamlLoader
     * @param Engine $engine
     * @param RemoteConfigurationProviderInterface $remoteConfigurationProvider
     */
    public function __construct(
        FilesystemInterface $filesystem,
        YamlLoader $yamlLoader,
        Engine $engine,
        RemoteConfigurationProviderInterface $remoteConfigurationProvider
    ) {
        parent::__construct();

        $this->filesystem = $filesystem;
        $this->yamlLoader = $yamlLoader;
        $this->engine = $engine;
        $this->remoteConfigurationProvider = $remoteConfigurationProvider;
    }

    protected function configure()
    {
        $this->setName('update')
            ->setDescription('Updates the ~/.ssh/config with the configuration');

        $this->addOption('directory', 'd', InputOption::VALUE_REQUIRED, 'Directory where to look for hookup.yml', null);
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return int
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $directory = $input->getOption('directory');

        if ($directory === null) {
            $directory = getenv('HOME');
        }

        $locator = new FileLocator();
        $processor = new ConfigurationProcessor();
        $configs = [];

        $output->writeln(
            sprintf('<comment>Looking for .hookup.yml configuration files within %s</comment>', $directory)
        );

        // Local
        foreach ($locator->locate('\.hookup.yml', $directory) as $configFile) {
            $output->writeln(sprintf('<info>Found %s</info>', $configFile));
            $config = $this->yamlLoader->load($configFile);

            if (isset($config['hookup'])) {
                $configs[] = $config['hookup'];
            }
        }

        $config = $processor->process($configs);

        if (!empty($config['github']['repositories'])) {
            $output->writeln(sprintf('<comment>Looking up remote repositories</comment>'));

            foreach ($config['github']['repositories'] as $repository) {
                $output->writeln(sprintf('<info>Downloading %s repositories hookup.yml file</info>', $repository));

                $remoteConfig = $this->remoteConfigurationProvider->get($repository, $config);

                if (isset($remoteConfig['hookup'])) {
                    $configs[] = $remoteConfig['hookup'];
                }
            }

            $config = $processor->process($configs);
        }



        $config = $processor->finalize($config);

        $file = $this->engine->render('config', ['servers' => $config['servers']]);

        $output->writeln(
            sprintf('<comment>~/.ssh/config has been updated</comment>')
        );
    }

}
