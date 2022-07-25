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

namespace PrestaShop\Module\BlmVuln\domain\service\form;

use Configuration;
use Language;
use PrestaShop\Module\BlmVuln\domain\service\util\ContextService;
use PrestaShop\Module\BlmVuln\resources\config\Config;
use PrestaShop\Module\BlmVuln\resources\config\Field;
use Tools;

final class Form implements FormInterface
{
    private $helperForm;

    public function __construct($helperForm)
    {
        $this->helperForm = $helperForm;
    }

    public function render(array $forms, string $submitName): string
    {
        $this->helperForm->show_toolbar = false;

        $this->helperForm->default_form_language = ContextService::getLanguage()->id;

        $this->helperForm->allow_employee_form_lang = Configuration::get('PS_BO_ALLOW_EMPLOYEE_FORM_LANG', 0);

        $this->helperForm->name_controller = Config::CONTROLLER_NAME;

        $this->helperForm->submit_action = $submitName;

        $this->helperForm->currentIndex = ContextService::getLink()->getAdminLink(
            Config::CONTROLLER_NAME,
            false,
            false
        );

        $this->helperForm->token = Tools::getAdminTokenLite(Config::CONTROLLER_NAME);

        $this->helperForm->tpl_vars = [
            'fields_value' => $this->getConfigFormValues(),
            'languages' => ContextService::getController()->getLanguages(),
            'id_language' => ContextService::getLanguage()->id,
        ];

        return $this->helperForm->generateForm($forms);
    }

    /**
     * @return array<string,mixed>
     */
    private function getConfigFormValues(): array
    {
        $languages = Language::getLanguages(false);

        $result = [];

        foreach (Field::getFieldValues() as $key => $fieldValue) {
            if ($fieldValue) {
                $confKey = Configuration::get($key);

                if ($confKey) {
                    $fields = json_decode($confKey, true);

                    foreach ($languages as $language) {
                        $idLang = $language['id_lang'];

                        $result[$key][$idLang] = $fields[$idLang];
                    }
                }
            } else {
                $result[$key] = Configuration::get($key);
            }
        }

        return $result;
    }
}
