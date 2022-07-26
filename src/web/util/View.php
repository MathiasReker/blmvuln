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

namespace PrestaShop\Module\BlmVuln\web\util;

final class View
{
    public static function displayBtnLink(string $link, string $href): string
    {
        return '<a class="btn btn-default" style="margin-right: 10px" href="'
            . $href
            . '" target="_blank" rel="noopener noreferrer nofollow">'
            . $link
            . '</a>';
    }

    public static function displayLink(string $href, $link = null, bool $target = true): string
    {
        if (null === $link) {
            $link = $href;
        }

        $blank = $target ? 'target="_blank"' : '';

        return '<a style="white-space:nowrap;" href="'
            . $href
            . '"'
            . $blank
            . ' rel="noopener noreferrer nofollow"><i class="icon-external-link-sign"></i> '
            . $link
            . '</a>';
    }

    public static function displayAlertDanger(string $text): string
    {
        return '<div class="alert alert-danger">' . $text . '</div>';
    }

    public static function displayAlertSuccess(string $text): string
    {
        return '<div class="alert alert-success">' . $text . '</div>';
    }
}
