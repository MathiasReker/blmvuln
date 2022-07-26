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

    public function __construct(array $directories)
    {
        $this->directories = $directories;
    }

    public function scan(): self
    {
        $root = $this->getRoot();

        foreach ($this->directories as $directory) {
            $path = $root . $directory;

            if (!is_dir($path)) {
                continue;
            }

            $this->scanDirectory($path);
        }

        return $this;
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

    private function getRoot(): string
    {
        return _PS_ROOT_DIR_ . '/';
    }

    private function scanDirectory(string $path)
    {
        $iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($path), 1);

        foreach ($iterator as $info) {

            // Don't process folders starting with a dot
            if ('.' === mb_substr(basename($info->getPathname()), 0, 1)) {
                continue;
            }

            $filePermissions = mb_substr(sprintf('%o', $info->getPerms()), -4);

            if ($info->isDir()) {
                if (\in_array($filePermissions, Config::ALLOWED_FOLDER_PERMISSIONS, true)) {
                    continue;
                }

                // On a Windows OS, don't bother for chmod 777
                if (('WIN' === mb_strtoupper(
                    mb_substr(\PHP_OS, 0, 3)
                )) && ('0777' === $filePermissions)) {
                    continue;
                }
            } else {
                if (\in_array($filePermissions, Config::ALLOWED_FILE_PERMISSIONS, true)) {
                    continue;
                }
            }

            $this->insecurePermissionFiles[] = $info->getPathname();
        }
    }
}
