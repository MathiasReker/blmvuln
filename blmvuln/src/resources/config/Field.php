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

namespace PrestaShop\Module\BlmVuln\resources\config;

final class Field
{
    private function __construct()
    {
    }

    /**
     * @return mixed[]
     */
    public static function getPreconfiguredValues(): array
    {
        return [];
    }

    /**
     * @return mixed[]
     */
    public static function getFieldValues(): array
    {
        return [];
    }
}
