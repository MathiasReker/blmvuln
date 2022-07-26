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

final class RemoveFiles extends AbstractScanner implements ScannerInterface
{
    /**
     * @var string[]
     */
    private $infectedFiles = [];

    /**
     * @var string[]
     */
    private $files;

    public function __construct(array $files)
    {
        $this->files = $files;
    }

    public function scan(): self
    {
        $root = $this->getRoot();

        foreach ($this->files as $file) {
            $currentFile = $root . $file;

            if (file_exists($currentFile)) {
                $this->infectedFiles[] = $currentFile;
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
