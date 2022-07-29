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

final class RestoreFiles implements ScannerInterface
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
     * @var string
     */
    private $patchRoot;

    public function __construct(array $files)
    {
        $this->files = $files;
    }

    public function setRoot(string $root): self {
        $this->root = $root;

        return $this;
    }

    public function setPatchRoot(string $patchRoot): self {
        $this->patchRoot = $patchRoot;

        return $this;
    }

    public function scan(): self
    {
        foreach ($this->files as $file) {
            $currentFile = $this->root . $file;

            if (file_exists($currentFile)) {
                $patchFile = $this->patchRoot . $file;

                if (!is_file($patchFile)) {
                    continue;
                }

                if ($this->isDifferance($patchFile, $currentFile)) {
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
            $cleanFile = file_get_contents($this->patchRoot . $infectedFile);

            file_put_contents($this->root . $infectedFile, $cleanFile);
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

    private function isDifferance(string $file1, string $file2): bool
    {
        return sha1_file($file1) !== sha1_file($file2);
    }
}
