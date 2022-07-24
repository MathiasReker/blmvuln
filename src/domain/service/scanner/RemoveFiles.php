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

final class RemoveFiles implements ScannerInterface
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
        $root = $this->getRoot();

        foreach ($this->files as $file) {
            $currentFile = $root . $file;

            if (file_exists($currentFile)) {
                $this->infectedFiles[] = $file;
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
            unlink($this->getRoot() . $infectedFile);
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

    private function getRoot(): string
    {
        return _PS_ROOT_DIR_ . '/';
    }
}
