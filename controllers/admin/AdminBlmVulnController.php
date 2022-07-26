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

use PrestaShop\Module\BlmVuln\domain\service\cache\ClearSmartyCache;
use PrestaShop\Module\BlmVuln\domain\service\form\Form;
use PrestaShop\Module\BlmVuln\domain\service\scanner\FilePermissions;
use PrestaShop\Module\BlmVuln\domain\service\scanner\PatchFiles;
use PrestaShop\Module\BlmVuln\domain\service\scanner\RemoveDirectories;
use PrestaShop\Module\BlmVuln\domain\service\scanner\RemoveFiles;
use PrestaShop\Module\BlmVuln\domain\service\scanner\RemoveFilesByPattern;
use PrestaShop\Module\BlmVuln\domain\service\scanner\RestoreFiles;
use PrestaShop\Module\BlmVuln\resources\config\Config;
use PrestaShop\Module\BlmVuln\web\form\HelpForm;
use PrestaShop\Module\BlmVuln\web\form\ScannerForm;

final class AdminBlmVulnController extends ModuleAdminController
{
    /**
     * @var string
     */
    const SUBMIT_NAME = 'submitConfig';

    /**
     * @var bool
     */
    public $bootstrap = true;

    public function renderList(): string
    {
        if (Tools::isSubmit(self::SUBMIT_NAME)) {
            $this->fixVulnerabilities();
        }

        return $this->renderAdminForm();
    }

    private function fixVulnerabilities()
    {
        (new RestoreFiles(Config::INFECTED_FILES_PATTERN))->scan()->fix();

        (new RemoveFiles(array_merge(Config::MALWARE_FILES_PATTERN, Config::CACHE_FILES)))->scan()->fix();

        (new PatchFiles(Config::PATCHED_FILES))->scan()->fix();

        (new RemoveFilesByPattern(Config::INFECTED_JS_PATHS))
            ->setFilesize(Config::MALWARE_JS_FILE_SIZE)
            ->setFileLength(Config::MALWARE_JS_FILE_LENGTH)
            ->setFileExtension(Config::MALWARE_JS_FILE_EXTENSION)
            ->scan()
            ->fix();

        (new FilePermissions(Config::PERMISSION_DIRECTORIES))
            ->scan()
            ->fix();

        (new RemoveDirectories(Config::VULNERABLE_DIRECTORIES_PATTERN))
            ->setFolder(Config::VULNERABLE_DIRECTORY)
            ->setRecursive(false)
            ->scan()
            ->fix();

        (new RemoveDirectories(Config::VULNERABLE_DIRECTORIES_PATTERN))
        ->setFolder(Config::VULNERABLE_DIRECTORY)
            ->setRecursive(true)
            ->scan()
            ->fix();

        // Filesystem is safer and faster than sql
        Configuration::updateGlobalValue('PS_SMARTY_CACHING_TYPE', 'filesystem');

        (new ClearSmartyCache())->all();
    }

    private function renderAdminForm(): string
    {
        $forms = [
            (new ScannerForm($this->module))->getFields(),
            (new HelpForm($this->module))->getFields(),
        ];

        return (new Form(new HelperForm()))->render($forms, self::SUBMIT_NAME);
    }
}
