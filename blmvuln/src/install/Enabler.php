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

final class Enabler extends AbstractInstaller
{
    public function execute(): bool
    {
        return $this->registerHooks();
    }

    private function registerHooks(): bool
    {
        if (empty($this->hooks)) {
            return false;
        }

        foreach ($this->hooks as $hook) {
            if (!$this->module->registerHook($hook)) {
                $error = sprintf($this->module->l('Hook %s has not been installed.', $this->className), $hook);

                $this->displayError($error);
            }
        }

        return true;
    }
}
