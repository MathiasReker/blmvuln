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

namespace PrestaShop\Module\BlmVuln\install;

use Configuration;
use PrestaShop\Module\BlmVuln\resources\config\Config;
use PrestaShop\Module\BlmVuln\resources\config\Field;
use Tab;
use Tools;

final class Installer extends AbstractInstaller
{
    public function execute(): bool
    {
        $this->checkPhpVersion();

        $this->installConfig();

        return $this->installTab();
    }

    private function checkPhpVersion(): void
    {
        if (Tools::version_compare(Tools::checkPhpVersion(), Config::MINIMUM_PHP_VERSION)) {
            $error = sprintf(
                $this->module->l('The module requires PHP %s or higher.', $this->className),
                Config::MINIMUM_PHP_VERSION
            );

            $this->displayError($error);
        }
    }

    private function installConfig(): void
    {
        $configs = Field::getPreconfiguredValues();

        if (empty($configs)) {
            return;
        }

        foreach ($configs as $key => $value) {
            if (!Configuration::updateValue($key, $value)) {
                $error = sprintf(
                    $this->module->l('The configuration %s has not been installed.', $this->className),
                    $key
                );

                $this->displayError($error);
            }
        }
    }

    private function installTab(): bool
    {
        return (new TabBuilder(new Tab()))
            ->module($this->module->name)
            ->displayName($this->module->displayName)
            ->className(Config::CONTROLLER_NAME)
            ->parentClassName('IMPROVE')
            ->icon('whatshot')
            ->install();
    }
}
