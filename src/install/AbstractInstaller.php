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

use Module;
use PrestaShop\Module\BlmVuln\resources\config\Field;
use PrestaShop\Module\BlmVuln\resources\config\Hook;
use ReflectionClass;

abstract class AbstractInstaller
{
    /**
     * @var Module
     */
    protected $module;

    /**
     * @var string[]
     */
    protected $hooks = [];

    /**
     * @var array<string, true>
     */
    protected $fieldValues = [];

    /**
     * @var string
     */
    protected $className;

    public function __construct(Module $module)
    {
        $this->module = $module;

        $this->hooks = Hook::getHooks();

        $this->fieldValues = Field::getFieldValues();

        $this->className = (new ReflectionClass($this))->getShortName();
    }

    abstract public function execute(): bool;

    protected function displayError(string $error): void
    {
        http_response_code(400);

        exit($error);
    }
}
