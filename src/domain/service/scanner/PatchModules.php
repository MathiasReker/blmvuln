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

use Module;
use Tools;

final class PatchModules implements ScannerInterface
{
    public $modules;

    /**
     * @var mixed[]
     */
    private $vulnableModules = [];

    public function __construct($modules)
    {
        $this->modules = $modules;
    }

    public function scan(): self
    {
        foreach ($this->modules as $module => $version) {
            if (Module::isInstalled($module)) {
                $moduleVersion = Module::getInstanceByName($module)->version;

                if (Tools::version_compare($moduleVersion, $version)) {
                    $this->vulnableModules[] = $module;
                }
            }
        }

        return $this;
    }

    public function fix(): bool
    {
        return false;
    }

    /**
     * @return mixed[]
     */
    public function dryRun(): array
    {
        return $this->vulnableModules;
    }
}
