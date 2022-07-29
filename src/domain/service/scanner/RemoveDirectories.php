<?php
/**
 * This file is part of the blmvuln package.
 *
 * @author Mathias Reker
 * @copyright Mathias Reker
 * @license MIT License
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace PrestaShop\Module\BlmVuln\domain\service\scanner;

use FilesystemIterator;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use Tools;

final class RemoveDirectories implements ScannerInterface
{
    /**
     * @var string[]
     */
    private $vulnerableDirectories = [];

    /**
     * @var string[]
     */
    private $directories;

    /**
     * @var bool
     */
    private $isRecursive;

    /**
     * @var string
     */
    private $directory;

    public function __construct(array $directories)
    {
        $this->directories = $directories;
    }

    public function setRecursive(bool $isRecursive): self
    {
        $this->isRecursive = $isRecursive;

        return $this;
    }

    public function setDirectory(string $directory): self
    {
        $this->directory = $directory;

        return $this;
    }

    public function scan(): self
    {
        if ($this->isRecursive) {
            $this->scanRecursive();
        } else {
            $this->scanNonRecursive();
        }

        return $this;
    }

    public function fix(): bool
    {
        if (empty($this->vulnerableDirectories)) {
            return false;
        }

        foreach ($this->vulnerableDirectories as $vulnerableDirectory) {
            if (is_dir($vulnerableDirectory)) {
                Tools::deleteDirectory($vulnerableDirectory, true);
            }
        }

        return true;
    }

    /**
     * @return string[]
     */
    public function dryRun(): array
    {
        return $this->vulnerableDirectories;
    }

    private function scanRecursive()
    {
        foreach ($this->directories as $directory) {
            if (!is_dir($directory)) {
                continue;
            }

            $iterator = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($directory, FilesystemIterator::SKIP_DOTS),
                RecursiveIteratorIterator::SELF_FIRST,
                RecursiveIteratorIterator::CATCH_GET_CHILD // Ignore "Permission denied"
            );

            foreach ($iterator as $path) {
                if ($path->isDir() && $this->directory === $path->getFilename()) {
                    $this->vulnerableDirectories[] = $path->getRealpath();
                }
            }
        }
    }

    private function scanNonRecursive()
    {
        foreach ($this->directories as $directory) {
            if (!is_dir($directory)) {
                continue;
            }

            $path = $directory . $this->directory;

            if (is_dir($path)) {
                $this->vulnerableDirectories[] = $path;
            }
        }
    }
}
