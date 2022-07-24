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

use Language;
use PrestaShopException;
use Tab;

final class TabBuilder
{
    /**
     * @var string
     */
    private $moduleName;

    /**
     * @var string
     */
    private $displayName;

    /**
     * @var string
     */
    private $className;

    /**
     * @var string
     */
    private $icon;

    /**
     * @var string
     */
    private $parentClassName;

    /**
     * @var Tab
     */
    private $tab;

    public function __construct(Tab $tab)
    {
        $this->tab = $tab;
    }

    public function module(string $moduleName): self
    {
        $this->moduleName = $moduleName;

        return $this;
    }

    public function displayName(string $displayName): self
    {
        $this->displayName = $displayName;

        return $this;
    }

    public function className(string $className): self
    {
        $this->className = $className;

        return $this;
    }

    public function icon(string $icon): self
    {
        $this->icon = $icon;

        return $this;
    }

    public function parentClassName(string $parentClassName): self
    {
        $this->parentClassName = $parentClassName;

        return $this;
    }

    public function install(): bool
    {
        $languages = Language::getLanguages(false);

        $tabName = [];

        foreach ($languages as $language) {
            $tabName[$language['id_lang']] = $this->displayName;
        }

        $this->tab->name = $tabName;

        $this->tab->class_name = $this->className;

        $this->tab->icon = $this->icon;

        $this->tab->id_parent = (int) Tab::getIdFromClassName($this->parentClassName);

        $this->tab->module = $this->moduleName;

        return $this->tab->save();
    }

    public function uninstall(): bool
    {
        $tabId = Tab::getIdFromClassName($this->className);

        if (!$tabId) {
            return true;
        }

        try {
            return $this->tab->delete();
        } catch (PrestaShopException $prestaShopException) {
            return false;
        }
    }
}
