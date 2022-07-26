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

final class Config
{
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
     * @var int
     */
    const DEFAULT_MODE_FOLDER = 0755;

    /**
     * @var int
     */
    const DEFAULT_MODE_FILES = 0644;

    /**
     * @var string[]
     */
    const ALLOWED_FILE_PERMISSIONS = ['0666', '0644', '0640'];

    /**
     * @var string[]
     */
    const ALLOWED_FOLDER_PERMISSIONS = ['0755', '0750'];

    /**
     * @var string[]
     */
    const PERMISSION_DIRECTORIES = [
        'classes', 'controllers'
    ];

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
        'classes/SmartyCacheResourceMysql.php',
    ];

    /**
     * @var int
     */
    const FILE_SIZE = 33637;

    /**
     * @var int
     */
    const FILE_LENGTH = [
        5,
        6,
    ];

    /**
     * @var string
     */
    const FILE_EXTENSION = 'js';

    /**
     * @var string[]
     */
    const MALWARE_FILES_PATTERN = [
        'blm.php',
        'app/Mage.php',
        '0x666.php',
        'IndoXploit.php',
        'Sh3ll.php',
        'XsamXadoo_Bot.php',
        'XsamXadoo_Bot_All.php',
        'XsamXadoo_deface.php',
        'Xsam_Xadoo.html',
        'anonsha1a0.php',
        'atx_bot.php',
        'azzoulshell.php',
        'b374k.php',
        'bajatax_xsam.php',
        'bigdump.php',
        'bypass.php',
        'c100.php',
        'c99.php',
        'cPanelCracker.php',
        'composer.json',
        'database.php',
        'docker-compose.yml',
        'efi.php',
        'f.php',
        'hacked.php',
        'httptest.php',
        'info.php',
        'kill.php',
        'lfishell.php',
        'olux.php',
        'perlinfo.php',
        'php.php',
        'phpinfo.php',
        'phppsinfo.php',
        'phpversion.php',
        'prestashop.zip',
        'proshell.php',
        'r00t.php',
        'r57.php',
        'sado.php',
        'shellwow.php',
        'simulasi.php',
        'sssp.php',
        'test.php',
        'testproxy.php',
        'upload.php',
        'wawa.php',
        'wolfm.php',
        'wso.php',
        'xGSx.php',
        'xaishell.php',
        'xcontact182.php',
        'xsam_xadoo_bot.php',
        'xsambot.php',
        'xsambot2.php',
        'xsamxadoo.php',
        'xsamxadoo101.php',
        'xsamxadoo102.php',
        'xsamxadoo95.php',
    ];

    /**
     * @var string[]
     */
    const SCAN_DIRECTORIES = [
        _PS_ROOT_DIR_ . '/vendor/'
    ];

    /**
     * @var array<string, string>
     */
    const PATCH_MODULES = [
        'blockwishlist' => '2.1.1',
        'bamegamenu' => '1.0.32',
    ];

    /**
     * @var string[]
     */
    const INFECTED_JS_PATHS = [
        _PS_ROOT_DIR_ . '/js/',
        _PS_MODULE_DIR_,
    ];

    /**
     * @var string
     */
    const REMOVE_DIRECTORY = 'phpunit';

    private function __construct()
    {
    }
}
