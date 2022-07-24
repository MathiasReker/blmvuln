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

namespace PrestaShop\Module\BlmVuln\domain\service\util;

use Context;
use Link;

final class ContextService
{
    public static function getLanguage()
    {
        return Context::getContext()->language;
    }

    public static function getContext(): ?Context
    {
        return Context::getContext();
    }

    public static function getController()
    {
        return Context::getContext()->controller;
    }

    public static function getLink(): Link
    {
        return Context::getContext()->link;
    }
}
