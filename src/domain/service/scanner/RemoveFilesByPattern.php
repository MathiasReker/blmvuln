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

use SplFileInfo;

final class RemoveFilesByPattern implements ScannerInterface
{
    /**
     * @var string[]
     */
    private $infectedFiles = [];

    /**
     * @var array
     */
    private $paths;

    /**
     * @var int
     */
    private $fileSize;

    /**
     * @var array
     */
    private $fileLength;

    /**
     * @var string
     */
    private $fileExtension;

    public function __construct(array $paths)
    {
        $this->paths = $paths;
    }

    public function setFilesize(int $fileSize): self
    {
        $this->fileSize = $fileSize;

        return $this;
    }

    public function setFileLength(array $fileLength): self
    {
        $this->fileLength = $fileLength;

        return $this;
    }

    public function setFileExtension(string $fileExtension): self
    {
        $this->fileExtension = $fileExtension;

        return $this;
    }

    public function scan(): self
    {
        $fileExtensionLength = $this->fileExtensionLength();

        foreach ($this->paths as $path) {
            $files = glob($path . '*');

            foreach ($files as $file) {
                if ((new SplFileInfo($file))->getExtension() !== $this->fileExtension) {
                    continue;
                }

                if (!\in_array(mb_strlen(basename($file)) - $fileExtensionLength, $this->fileLength, true)) {
                    continue;
                }

                if (filesize($file) !== $this->fileSize) {
                    continue;
                }

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

    private function fileExtensionLength(): int
    {
        return mb_strlen($this->fileExtension) + 1;
    }
}
