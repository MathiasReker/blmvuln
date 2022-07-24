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
    private $oldFiles = [];

    private $files;

    public function __construct($files)
    {
        $this->files = $files;
    }

    public function scan(): self
    {
        $root = $this->getRoot();

        foreach ($this->files as $file) {
            $currentFile = $root . $file;

            if (file_exists($currentFile)) {
                $patchedFile = $this->getPatchRoot() . $file;

                if ($this->isDifferent($patchedFile, $currentFile)) {
                    $this->oldFiles[] = $file;
                }
            }
        }

        return $this;
    }

    public function fix(): bool
    {
        if (empty($this->oldFiles)) {
            return false;
        }

        $root = $this->getRoot();

        foreach ($this->oldFiles as $oldFile) {
            $patchedFile = file_get_contents($this->getPatchRoot() . $oldFile);

            file_put_contents($root . $oldFile, $patchedFile);
        }

        return true;
    }

    /**
     * @return mixed[]
     */
    public function dryRun(): array
    {
        return $this->oldFiles;
    }

    private function getRoot(): string
    {
        return _PS_ROOT_DIR_ . '/';
    }

    private function getPatchRoot(): string
    {
        return _PS_MODULE_DIR_ . Config::MODULE_NAME . '/patch/';
    }

    private function isDifferent(string $file1, string $file2): bool
    {
        return sha1_file($file1) !== sha1_file($file2);
    }
}
