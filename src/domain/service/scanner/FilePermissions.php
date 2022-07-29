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
use PrestaShop\Module\BlmVuln\resources\config\Config;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;

final class FilePermissions implements ScannerInterface
{
    /**
     * @var string[]
     */
    private $insecurePermissionFiles = [];

    /**
     * @var string[]
     */
    private $directories;

    /**
     * @param string
     */
    private $root;

    public function __construct(array $directories)
    {
        $this->directories = $directories;
    }

    public function setRoot($root): self {
        $this->root = $root;

        return $this;
    }

    public function scan(): self
    {
        foreach ($this->directories as $directory) {
            $path = $this->root . $directory;

            if (is_dir($path)) {
                $this->scanDirectory($path);
            }
        }

        return $this;
    }

    private function scanDirectory(string $directory)
    {
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($directory, FilesystemIterator::SKIP_DOTS),
            RecursiveIteratorIterator::SELF_FIRST,
            RecursiveIteratorIterator::CATCH_GET_CHILD // Ignore "Permission denied"
        );

        $isWindows = $this->isWindows();

        foreach ($iterator as $path) {
            $filePermissions = mb_substr(sprintf('%o', $path->getPerms()), -4);

            if ($path->isDir()) {
                if (\in_array($filePermissions, Config::ALLOWED_FOLDER_PERMISSIONS, true)) {
                    continue;
                }

                if ($isWindows && '0777' === $filePermissions) {
                    continue;
                }
            } else {
                if (\in_array($filePermissions, Config::ALLOWED_FILE_PERMISSIONS, true)) {
                    continue;
                }

                if ($isWindows && '0666' === $filePermissions) {
                    continue;
                }
            }

            $this->insecurePermissionFiles[] = $path->getPathname();
        }
    }

    public function fix(): bool
    {
        if (empty($this->insecurePermissionFiles)) {
            return false;
        }

        foreach ($this->insecurePermissionFiles as $path) {
            chmod($path, is_dir($path) ? Config::DEFAULT_MODE_FOLDER : Config::DEFAULT_MODE_FILES);
        }

        return true;
    }

    /**
     * @return string[]
     */
    public function dryRun(): array
    {
        return $this->insecurePermissionFiles;
    }

    private function isWindows(): bool
    {
        return 'WIN' === mb_strtoupper(
                mb_substr(\PHP_OS, 0, 3)
            );
    }
}
