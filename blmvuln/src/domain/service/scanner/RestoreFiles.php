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

final class RestoreFiles implements ScannerInterface
{
    /**
     * @var mixed[]
     */
    private $infectedFiles = [];

    private $files;

    public function __construct($files)
    {
        $this->files = $files;
    }

    public function scan(): self
    {
        $originalRoot = $this->getOriginalRoot();

        $root = $this->getRoot();

        foreach ($this->files as $file) {
            $currentFile = $root . $file;

            if (file_exists($currentFile)) {
                $originalFile = $originalRoot . $file;

                if ($this->isDifferent($originalFile, $currentFile)) {
                    $this->infectedFiles[] = $file;
                }
            }
        }

        return $this;
    }

    public function fix(): bool
    {
        if (empty($this->infectedFiles)) {
            return false;
        }

        foreach ($this->infectedFiles as $infectedFile) {
            $cleanFile = file_get_contents($this->getOriginalRoot() . $infectedFile);

            file_put_contents($this->getRoot() . $infectedFile, $cleanFile);
        }

        return true;
    }

    /**
     * @return mixed[]
     */
    public function dryRun(): array
    {
        return $this->infectedFiles;
    }

    private function getOriginalRoot(): string
    {
        return _PS_MODULE_DIR_ . Config::MODULE_NAME . '/bin/' . _PS_VERSION_ . '/';
    }

    private function getRoot(): string
    {
        return _PS_ROOT_DIR_ . '/';
    }

    private function isDifferent(string $file1, string $file2): bool
    {
        return sha1_file($file1) !== sha1_file($file2);
    }
}
