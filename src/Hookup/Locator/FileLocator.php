<?php

namespace Hookup\Locator;

use RecursiveDirectoryIterator;
use RegexIterator;
use RecursiveIteratorIterator;
use RecursiveRegexIterator;

class FileLocator
{
    /**
     * @var int
     */
    private $flags;

    /**
     * FileLocator constructor.
     * @param int|null $flags
     */
    public function __construct(int $flags = null)
    {
        if ($flags === null) {
            $flags = RecursiveDirectoryIterator::SKIP_DOTS;
        }

        $this->flags = $flags;
    }

    /**
     * @param string $filename
     * @param string $baseDirectory
     * @return array
     */
    public function locate(string $filename, string $baseDirectory): array
    {
        $directoryIterator = new RecursiveDirectoryIterator($baseDirectory, $this->flags);
        $directoryIteratorIterator = new RecursiveIteratorIterator($directoryIterator, null, $this->flags);
        $iterator = new RegexIterator(
            $directoryIteratorIterator,
            sprintf('/%s$/', $filename),
            RecursiveRegexIterator::GET_MATCH,
            $this->flags
        );

        return array_keys(iterator_to_array($iterator));
    }
}
