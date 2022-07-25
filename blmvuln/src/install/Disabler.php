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

final class Disabler extends AbstractInstaller
{
    public function execute(): bool
    {
        return $this->unregisterHooks();
    }

    private function unregisterHooks(): bool
    {
        if (empty($this->hooks)) {
            return true;
        }

        foreach ($this->hooks as $hook) {
            if (!$this->module->unregisterHook($hook)) {
                $error = sprintf($this->module->l('Hook %s has not been uninstalled.', $this->className), $hook);

                $this->displayError($error);
            }
        }

        return true;
    }
}
