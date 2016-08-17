<?php

namespace Hookup\Loader;

use League\Flysystem\FilesystemInterface;
use Symfony\Component\Yaml\Exception\ParseException;
use Symfony\Component\Yaml\Yaml;

class YamlLoader
{
    /**
     * @var FilesystemInterface
     */
    private $filesystem;

    /**
     * YamlLoader constructor.
     * @param FilesystemInterface $filesystem
     */
    public function __construct(FilesystemInterface $filesystem)
    {
        $this->filesystem = $filesystem;
    }

    /**
     * @param string $pathname
     * @return array
     */
    public function load(string $pathname): array
    {
        try {
            $data = Yaml::parse($this->filesystem->read($pathname));

            if (is_array($data)) {
                return $data;
            }
        } catch (ParseException $e) {

        }

        return [];
    }
}
