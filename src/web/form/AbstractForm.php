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

namespace PrestaShop\Module\BlmVuln\web\form;

use Module;
use ReflectionClass;

abstract class AbstractForm
{
    /**
     * @var string
     */
    protected $className;

    /**
     * @var Module
     */
    protected $module;

    public function __construct(Module $module)
    {
        $this->className = (new ReflectionClass($this))->getShortName();

        $this->module = $module;
    }

    /**
     * @return array{form: array<string, string>}
     */
    abstract public function getFields(): array;
}
