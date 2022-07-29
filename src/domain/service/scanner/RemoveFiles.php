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

final class RemoveFiles implements ScannerInterface
{
    /**
     * @var string[]
     */
    private $infectedFiles = [];

    /**
     * @var string[]
     */
    private $files;

    /**
     * @var string
     */
    private $root;

    /**
     * @var bool
     */
    private $isRecursive;

    public function __construct(array $files)
    {
        $this->files = $files;
    }

    public function setRoot(string $root): self
    {
        $this->root = $root;

        return $this;
    }

    public function isRecursive(bool $isRecursive): self
    {
        $this->isRecursive = $isRecursive;

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

    private function scanRecursive()
    {
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($this->root, FilesystemIterator::SKIP_DOTS),
            RecursiveIteratorIterator::SELF_FIRST,
            RecursiveIteratorIterator::CATCH_GET_CHILD // Ignore "Permission denied"
        );

        foreach ($iterator as $path) {
            if ($path->isFile() && in_array($path->getFilename(), $this->files)) {
                $this->infectedFiles[] = $path->getRealpath();
            }
        }
    }

    private function scanNonRecursive()
    {
        foreach ($this->files as $file) {
            $currentFile = $this->root . $file;

            if (file_exists($currentFile)) {
                $this->infectedFiles[] = $currentFile;
            }
        }
    }

    public function fix(): bool
    {
        if (empty($this->infectedFiles)) {
            return false;
        }

        foreach ($this->infectedFiles as $infectedFile) {
            if (file_exists($infectedFile)) {
                unlink($infectedFile);
            }
        }

        return true;
    }

    /**
     * @return string[]
     */
    public function dryRun(): array
    {
        return $this->infectedFiles;
    }
}
