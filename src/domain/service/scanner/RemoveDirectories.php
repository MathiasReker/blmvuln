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

    private $scanDirectories;

    private $isRecursive;

    private $folder;

    public function __construct(array $scanDirectories)
    {
        $this->scanDirectories = $scanDirectories;
    }

    public function setRecursive(bool $isRecursive): self
    {
        $this->isRecursive = $isRecursive;

        return $this;
    }

    public function setFolder(string $folder): self
    {
        $this->folder = $folder;

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
        foreach ($this->scanDirectories as $scanDirectory) {
            if (!is_dir($scanDirectory)) {
                continue;
            }

            $iterator = new RecursiveIteratorIterator(
                new RecursiveDirectoryIterator($scanDirectory, FilesystemIterator::SKIP_DOTS),
                RecursiveIteratorIterator::SELF_FIRST,
                RecursiveIteratorIterator::CATCH_GET_CHILD // Ignore "Permission denied"
            );

            foreach ($iterator as $path) {
                if ($path->isDir() && $this->folder === $path->getFilename()) {
                    $this->vulnerableDirectories[] = $path->getRealpath();
                }
            }
        }
    }

    private function scanNonRecursive()
    {
        foreach ($this->scanDirectories as $scanDirectory) {
            if (!is_dir($scanDirectory)) {
                continue;
            }

            $fullPath = $scanDirectory . $this->folder;

            if (is_dir($fullPath)) {
                $this->vulnerableDirectories[] = $fullPath;
            }
        }
    }
}
