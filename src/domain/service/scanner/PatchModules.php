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

use Module;
use Tools;

final class PatchModules implements ScannerInterface
{
    /**
     * @var string[]
     */
    public $modules;

    /**
     * @var string[]
     */
    private $vulnerableModules = [];

    public function __construct(array $modules)
    {
        $this->modules = $modules;
    }

    public function scan(): self
    {
        foreach ($this->modules as $module => $version) {
            if (Module::isInstalled($module)) {
                $installedModuleVersion = Module::getInstanceByName($module)->version;

                if (is_string($version)) {
                    if (Tools::version_compare($installedModuleVersion, $version)) {
                        $this->vulnerableModules[] = $module;
                    }
                } else {
                    if (Tools::version_compare($installedModuleVersion, $version[0], '>=') &&
                        Tools::version_compare($installedModuleVersion, $version[1], '<=')
                    ) {
                        $this->vulnerableModules[] = $module;
                    }
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
     * @return string[]
     */
    public function dryRun(): array
    {
        return $this->vulnerableModules;
    }
}
