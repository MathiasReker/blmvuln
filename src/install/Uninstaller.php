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

namespace PrestaShop\Module\BlmVuln\install;

use Configuration;
use PrestaShop\Module\BlmVuln\resources\config\Config;
use Tab;

final class Uninstaller extends AbstractInstaller
{
    public function execute(): bool
    {
        $this->uninstallConfig();

        return true;
    }

    private function uninstallConfig()
    {
        if (empty($this->fieldValues)) {
            return;
        }

        foreach (array_keys($this->fieldValues) as $name) {
            Configuration::deleteByName($name);
        }
    }
}
