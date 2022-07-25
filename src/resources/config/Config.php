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
    const DEFAULT_MODE_FOLDER = 0755;

    /**
     * @var string
     */
    const MODULE_NAME = 'blmvuln';

    /**
     * @var string
     */
    const CONTROLLER_NAME = 'AdminBlmVuln';

    /**
     * @var string
     */
    const MINIMUM_PHP_VERSION = '7.0';

    /**
     * @var string[]
     */
    const INFECTED_FILES_PATTERN = [
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
        'config/smarty.config.inc.php',
    ];

    /**
     * @var string[]
     */
    const CACHE_FILES = [
        'cache/class_index.php',
    ];

    /**
     * @var string[]
     */
    const PATCHED_FILES = [
        'classes/Smarty/SmartyCacheResourceMysql.php',
        'classes/SmartyCacheResourceMysql.php'
    ];

    /**
     * @var int
     */
    const FILE_SIZE = 33637;

    /**
     * @var int
     */
    const FILE_LENGTH = 5;

    /**
     * @var string
     */
    const FILE_EXTENSION = 'js';

    /**
     * @var string[]
     */
    const MALWARE_FILES_PATTERN = ['blm.php', 'app/Mage.php'];

    /**
     * @var array<string, string>
     */
    const PATCH_MODULES = [
        'blockwishlist' => '2.1.1',
    ];

    private function __construct()
    {
    }

    public static function getPathsInfectedJsFiles(): array
    {
        return [
            _PS_ROOT_DIR_ . '/js/',
            _PS_MODULE_DIR_
        ];
    }
}
