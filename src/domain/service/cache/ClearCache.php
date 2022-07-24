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

namespace PrestaShop\Module\BlmVuln\domain\service\cache;

use Category;
use Tools;

final class ClearCache implements CacheInterface
{
    public function all(): void
    {
        Tools::clearSmartyCache();

        Tools::clearSf2Cache('dev');

        Tools::clearSf2Cache('prod');

        self::regenerateCache();
    }

    private function regenerateCache(): void
    {
        Tools::generateIndex();

        Category::regenerateEntireNtree();
    }
}
