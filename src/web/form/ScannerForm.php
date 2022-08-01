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

use PrestaShop\Module\BlmVuln\domain\service\scanner\FilePermissions;
use PrestaShop\Module\BlmVuln\domain\service\scanner\PatchModules;
use PrestaShop\Module\BlmVuln\domain\service\scanner\RemoveDirectories;
use PrestaShop\Module\BlmVuln\domain\service\scanner\RemoveFiles;
use PrestaShop\Module\BlmVuln\domain\service\scanner\RemoveFilesByPattern;
use PrestaShop\Module\BlmVuln\domain\service\scanner\RestoreFiles;
use PrestaShop\Module\BlmVuln\resources\config\Config;
use PrestaShop\Module\BlmVuln\web\util\View;

final class ScannerForm extends AbstractForm
{
    /**
     * @return array{form: array<string, string>}
     */
    public function getFields(): array
    {
        return [
            'form' => [
                'legend' => [
                    'title' => $this->module->l('Scanner', $this->className),
                    'icon' => 'icon-bug',
                ],
                'description' => sprintf(
                    $this->module->l('Read more about the vulnerability here: %s.', $this->className),
                    View::displayLink(
                        'https://build.prestashop.com/news/major-security-vulnerability-on-prestashop-websites/'
                    )
                ),
                'input' => [
                    [
                        'type' => 'html',
                        'label' => '',
                        'html_content' => $this->render(),
                        'col' => 12,
                        'name' => '',
                    ],
                ],
                'submit' => [
                    'title' => $this->module->l('Run the cleaning process', $this->className),
                ],
            ],
        ];
    }

    private function render(): string
    {
        $result = '';

        foreach ($this->getSections() as $section) {
            if (!empty($section[0])) {
                $result .= View::displayAlertDanger($section[1] . '<br>' . implode('<br>', $section[0]));
            } else {
                $result .= View::displayAlertSuccess($section[2]);
            }
        }

        $result .= $this->getStyle();

        return $result;
    }

    private function getSections(): array
    {
        return [
            [
                $this->getPatchModules(),
                $this->module->l('Your website is vulnerable. You must upgrade the following modules manually:', $this->className),
                $this->module->l('No vulnerable modules found.', $this->className),
            ],
            [
                $this->getInsecureFiles(),
                $this->module->l('The following files look infected or vulnerable. They will be either restored, patched or removed (no core files will be removed) by running the cleaning process:', $this->className),
                $this->module->l('No infected files was found.', $this->className),
            ],
            [
                $this->getInsecurePackages(),
                $this->module->l('The following packages can contain vulnerable files. They will be removed by running the cleaning process:', $this->className),
                $this->module->l('No vulnerable packages was found.', $this->className),
            ],
            [
                $this->getInsecureFilepermissions(),
                $this->module->l('The following filepermissions are insecure. They will be fixed by running the cleaning process:', $this->className),
                $this->module->l('No insecure filepermissions was found.', $this->className),
            ],
        ];
    }

    /**
     * @return string[]
     */
    private function getPatchModules(): array
    {
        return (new PatchModules(Config::VULNERABLE_MODULES))
            ->scan()
            ->dryRun();
    }

    /**
     * @return string[]
     */
    private function getInsecureFiles(): array
    {
        return array_merge(
            (new RestoreFiles(Config::POSSIBLE_INFECTED_FILES))
                ->setRoot(Config::ROOT_DIRECTORY)
                ->setPatchRoot(Config::PATCH_ROOT_DIRECTORY)
                ->scan()
                ->dryRun(),
            (new RemoveFiles(Config::MALWARE_FILES))
                ->setRoot(Config::ROOT_DIRECTORY)
                ->isRecursive(false)
                ->scan()
                ->dryRun(),
            (new RemoveFilesByPattern(Config::INFECTED_JS_PATHS))
                ->setFilesize(Config::MALWARE_JS_FILE_SIZE)
                ->setFileLength(Config::MALWARE_JS_FILE_LENGTHS)
                ->setFileExtension(Config::MALWARE_JS_FILE_EXTENSION)
                ->scan()
                ->dryRun()
        );
    }

    /**
     * @return string[]
     */
    private function getInsecurePackages(): array
    {
        return array_merge(
            (new RemoveDirectories([Config::ROOT_DIRECTORY]))
                ->setDirectory(Config::VULNERABLE_DIRECTORY)
                ->setRecursive(false)
                ->scan()
                ->dryRun(),
            (new RemoveDirectories(Config::VULNERABLE_DIRECTORIES))
                ->setDirectory(Config::VULNERABLE_DIRECTORY)
                ->setRecursive(true)
                ->scan()
                ->dryRun()
        );
    }

    /**
     * @return string[]
     */
    private function getInsecureFilepermissions(): array
    {
        return (new FilePermissions(Config::PERMISSION_DIRECTORIES))
            ->scan()
            ->dryRun();
    }

    private function getStyle(): string
    {
        return '<style>.bootstrap, .form-horizontal, .form-wrapper{max-width: 100% !important;}</style>';
    }
}
