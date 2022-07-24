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

final class Config
{
    /**
     * @var int
     */
    public const DEFAULT_MODE_FOLDER = 0755;

    /**
     * @var string
     */
    public const MODULE_NAME = 'blmvuln';

    /**
     * @var string
     */
    public const CONTROLLER_NAME = 'AdminBlmVuln';

    /**
     * @var string
     */
    public const MINIMUM_PHP_VERSION = '7.1';

    /**
     * @var string[]
     */
    public const INFECTED_FILES_PATTERN = [
        'classes/Controller.php',
        'classes/controller/Controller.php',
        'classes/controller/FrontController.php',
        'classes/Db/Db.php',
        'classes/db/Db.php',
        'classes/Dispatcher.php',
        'classes/Hook.php',
        'classes/module/Module.php',
        'classes/modules/Module.php',
        'controllers/admin/AdminLoginController.php',
        'controllers/AdminLoginController.php',
        'controllers/front/IndexController.php',
        'tools/smarty/sysplugins/smarty_internal_templatebase.php',
    ];

    /**
     * @var string[]
     */
    public const MALWARE_FILES_PATTERN = ['blm.php', 'app/Mage.php'];

    /**
     * @var string[]
     */
    public const PATCHED_FILES = ['config/smarty.config.inc.php'];

    /**
     * @var array<string, string>
     */
    public const PATCH_MODULES = [
        'blockwishlist' => '2.1.1',
    ];

    private function __construct()
    {
    }
}
