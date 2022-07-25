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

namespace PrestaShop\Module\BlmVuln\web\form;

use PrestaShop\Module\BlmVuln\domain\service\scanner\PatchFiles;
use PrestaShop\Module\BlmVuln\domain\service\scanner\PatchModules;
use PrestaShop\Module\BlmVuln\domain\service\scanner\RemoveFiles;
use PrestaShop\Module\BlmVuln\domain\service\scanner\RemoveFilesByPattern;
use PrestaShop\Module\BlmVuln\domain\service\scanner\RestoreFiles;
use PrestaShop\Module\BlmVuln\resources\config\Config;
use PrestaShop\Module\BlmVuln\web\util\View;

final class ScannerForm extends AbstractForm
{
    /**
     * @return array{form: array<string, mixed>}
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
                        'html_content' => $this->infectedFiles(),
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

    private function infectedFiles(): string
    {
        $result = '';

        $modules = (new PatchModules(Config::PATCH_MODULES))->scan()->dryRun();

        $sections = [
            [
                $modules,
                $this->module->l('You are vulnerable. You must upgrade the following modules manually:', $this->className),
                $this->module->l('No vulnerable modules found'),
            ],
            [
                (new RestoreFiles(Config::INFECTED_FILES_PATTERN))->scan()->dryRun(),
                $this->module->l('The following files looks infected. They will be restored to its original state by running the cleaning process.', $this->className),
                $this->module->l('No infected was files found.', $this->className),
            ],
            [
                array_merge(
                    (new RemoveFiles(Config::MALWARE_FILES_PATTERN))->scan()->dryRun(),
                    (new RemoveFilesByPattern(Config::getPathsInfectedJsFiles()))
                        ->setFilesize(Config::FILE_SIZE)
                        ->setFileLength(Config::FILE_LENGTH)
                        ->setFileExtension(Config::FILE_EXTENSION)
                        ->scan()
                        ->dryRun()
                ),
                $this->module->l('The following files are malware. They will be removed by running the cleaning process:', $this->className),
                $this->module->l('No malware was found.', $this->className),
            ],
            [
                (new PatchFiles(Config::PATCHED_FILES))->scan()->dryRun(),
                $this->module->l('The following files need to get patched. They will get patched by running the cleaning process:', $this->className),
                $this->module->l('No patches required.', $this->className),
            ],
        ];

        foreach ($sections as $section) {
            if (!empty($section[0])) {
                $result .= View::displayAlertDanger($section[1] . '<br>' . implode('<br>', $section[0]));
            } else {
                $result .= View::displayAlertSuccess($section[2]);
            }
        }

        return $result . '<style>.bootstrap, .form-horizontal, .form-wrapper{max-width: 100% !important;}</style>';
    }
}
