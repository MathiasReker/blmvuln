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

namespace PrestaShop\Module\BlmVuln\resources\config;

final class Hook
{
    private function __construct()
    {
    }

    /**
     * @return string[]
     */
    public static function getHooks(): array
    {
        return [];
    }
}
