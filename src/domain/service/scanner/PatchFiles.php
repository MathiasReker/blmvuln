<?php
/**
 * This file is part of the blmvuln package.
 *
 * @author Mathias Reker
 * @copyright Mathias Reker
 * @license MIT
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace PrestaShop\Module\BlmVuln\domain\service\scanner;

use PrestaShop\Module\BlmVuln\resources\config\Config;

final class PatchFiles implements ScannerInterface
{
    /**
     * @var mixed[]
     */
    private $patchedFiles = [];

    private $files;

    public function __construct($files)
    {
        $this->files = $files;
    }

    public function scan(): self
    {
        $patchRoot = $this->getPatchRoot();

        $root = $this->getRoot();

        foreach ($this->files as $file) {
            $currentFile = $root . $file;

            if (file_exists($currentFile)) {
                $patchFile = $patchRoot . $file;

                if (!is_file($patchFile)) {
                    continue;
                }

                if ($this->isDifferance($patchFile, $currentFile)) {
                    $this->patchedFiles[] = $file;
                }
            }
        }

        return $this;
    }

    public function fix(): bool
    {
        if (empty($this->patchedFiles)) {
            return false;
        }

        foreach ($this->patchedFiles as $patchedFile) {
            $cleanFile = file_get_contents($this->getPatchRoot() . $patchedFile);

            file_put_contents($this->getRoot() . $patchedFile, $cleanFile);
        }

        return true;
    }

    /**
     * @return mixed[]
     */
    public function dryRun(): array
    {
        return $this->patchedFiles;
    }

    private function getRoot(): string
    {
        return _PS_ROOT_DIR_ . '/';
    }

    private function getPatchRoot(): string
    {
        return _PS_MODULE_DIR_ . Config::MODULE_NAME . '/patch/';
    }

    private function isDifferance(string $file1, string $file2): bool
    {
        return sha1_file($file1) !== sha1_file($file2);
    }
}
