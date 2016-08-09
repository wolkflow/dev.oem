<?php

namespace Wolk\Core\System\Migrations;

if (!\Bitrix\Main\Loader::includeModule('iblock')) {
    exit('Module iblock not installed');
}

/**
 * Класс для совершения действий с элементами инфоблока
 *
 * $migration_iblock_element = new IblockElement(
 *   'CP-5230',
 *    'Привязка поля документа - дата договора купли/продажи к шаблону документа покупки у клиента с id = '. $template->getID(),
 *    [
 *      'PROPERTY_VALUES' => [
 *           'FIELDS' => $fields_id,
 *       ],
 *    'ID' => $template->getID(),
 *    'IBLOCK_ID' => \Linemedia\Carsale\Documents\Template::IBLOCK_ID,
 *    ]
 *   );
 *
 * $migration_iblock_element->update($template->getID(), false, false);
 */
class IblockElement extends UpdateTool
{

    /**
     * Инициализирует объект, задаёт набор полей, выводит границу начала задачи
     *
     * @param string    $task_id идентификатор задачи
     * @param string    $migration_description  Описание сути миграции. Можно задать позже через setDescription.
     * @param array     $fields набор полей и свойств элемента инфоблока
     *
     */
    public function __construct($task_id, $migration_description, $fields = [])
	{
        parent::__construct($task_id, $migration_description, $fields);
    }


    /**
     * Проверяет существование элемента инфоблока по заданному ранее набору полей
     *
     * @return mixed - ID обнаруженного элемента инфоблока или false, если ничего не найдено
     *
     * @throws \Exception
     */
    public function isExist()
    {
        // Формируем из известных полей свойства фильтр
        $ar_filter = [];
        if (empty($this->fields["CODE"])) {
            throw new \Exception("Невозможно определить, существует ли элемент, так как не задан его символьный код");
        } else {
            $this->fields["CODE"] = strval($this->fields["CODE"]);
            $ar_filter["CODE"] = $this->fields["CODE"];
        }
        if (empty($this->fields["IBLOCK_ID"])) {
            if (empty($this->fields["IBLOCK_CODE"]) && empty($this->fields["IBLOCK_TYPE"])) {
                throw new \Exception("Невозможно определить, существует ли элемент, так как не задан код инфоблока");
            } else {
                $ar_filter["IBLOCK_CODE"] = $this->fields["IBLOCK_CODE"];
                $ar_filter["IBLOCK_TYPE"] = $this->fields["IBLOCK_TYPE"];
            }
        } else {
            $ar_filter["SECTION_ID"] = $this->fields["IBLOCK_SECTION_ID"];
            $ar_filter["IBLOCK_ID"] = $this->fields["IBLOCK_ID"];
            if ($this->fields["PROPERTY_VALUES"]) {
                $ar_filter["PROPERTY_TAXATION"] = $this->fields["PROPERTY_VALUES"]["TAXATION"];
                $ar_filter["PROPERTY_PAYMENT"] = $this->fields["PROPERTY_VALUES"]["PAYMENT"];
                $ar_filter["PROPERTY_CONTRAGENT"] = $this->fields["PROPERTY_VALUES"]["CONTRAGENT"];
            }
        }
        // Получилось что-нибудь найти по сформированному фильтру?
        $ar_element = \CIBlockElement::GetList([], $ar_filter)->Fetch();
        if (!$ar_element) {
            return false;
        }
        // Если свойство найдено, то запомним его ID и id инфоблока
        $this->id = $ar_element["ID"];
        $this->fields['IBLOCK_ID'] = $ar_element['IBLOCK_ID'];
        return $this->id;
    }


    /**
     * Добавляет элемент инфоблока
     *
     * @param bool $exit_on_success - работа скрипта завершается на успешном добавлении элемента?
     * @param bool $exit_if_exists - прерывать выполнение скрипта, если элемент не найден?
     * @param bool $exit_on_fail - прерывать выполнение скрипта, если произошла ошибка при добавлении?
     * @param bool $update_if_exists - изменить элемент если он существует
     * @return bool|int - ID добавленного элемента или false в случае неудачи
     */
    public function add($exit_on_success = false, $exit_if_exists = false, $exit_on_fail = true, $update_if_exists= false)
    {
        // Проверим, было ли уже выполнено добавление
        try {
            if ($this->isExist()) {
                // Если элемент уже существует, сообщим об отмене действия
                if ($update_if_exists) {
                    $this->status("Элемент " . $this->fields["CODE"] . " уже существует");
                    $this->update($this->id, $exit_on_success, $exit_on_fail);
                    return $this->id;
                } else {
                    $this->cancel("Элемент " . $this->fields["CODE"] . " уже существует", $exit_if_exists);
                }

                return $this->id;
            }
        } catch(\Exception $e) {
            // Если невозможно проверить, существует ли элемент, то дальше не идём
            $this->fail($e->getMessage(), $exit_on_fail);
        }
        // Добавим элемент
        $iblock_element = new \CIBlockElement();
        $this->id = $iblock_element->Add($this->fields);
        //Получилось?
        if (!$this->id) {
            $this->fail("Не удалось добавить элемент " . $this->fields["CODE"] . ": " . $iblock_element->LAST_ERROR, $exit_on_fail);
            return false;
        }
        // Если да, то сообщим об этом и вернём ID добавленного элемента
        $this->success("Элемент " . $this->fields["CODE"] . " успешно добавлен, ID=" . $this->id, $exit_on_success);
        return $this->id;
    }

    /**
     * Установить свойства элемента
     * @param $properties
     */
    public function setProperties($properties)
    {
        $this->fields['PROPERTY_VALUES'] = $properties;
    }

    /**
     * Изменяет элемент инфоблока
     * @param $element_id - идентификатор элемента
     * @param $exit_on_success - завершить если успешно
     * @param $exit_on_fail - завершить если ошибка
     * @return bool
     */
    public function update($element_id = false, $exit_on_success = false, $exit_on_fail=true)
    {
        $element_id = (int)$element_id;

        if (!$element_id) {
            if (!$this->isExist()) {
                $this->fail('Не удалось найти элемент ' . $this->fields["CODE"], $exit_on_fail);
                return false;
            }

            $element_id = $this->id;
        }

        if ($element_id <= 0) {
            $this->fail("Не удалось изменить элемент - не задан идентификатор элемента " , $exit_on_fail);
            return false;
        }
        if (empty($this->fields['IBLOCK_ID'])) {
            $this->fail("Не задан идентификатор инфоблока" , $exit_on_fail);
            return false;
        }

        $property_values = $this->fields['PROPERTY_VALUES'];
        $fields = $this->fields;
        unset($fields['PROPERTY_VALUES']);
        unset($fields['IBLOCK_ID']);
        unset($fields['IBLOCK_CODE']);
        unset($fields['ID']);
        try {
            if (!empty($fields)) {
                $iblock_element = new \CIBlockElement();
                if (!$iblock_element->Update($element_id, $fields)) {
                    $this->fail("Не удалось изменить поля элемента " . $this->fields["CODE"] . "(".$element_id."): " . $iblock_element->LAST_ERROR, $exit_on_fail);
                }
            }
            if (!empty($property_values)) {
                \CIBlockElement::SetPropertyValuesEx($element_id, $this->fields['IBLOCK_ID'], $property_values);
            }
            $this->success("Элемент " . $this->fields["CODE"] . "(".$element_id."): " . " успешно изменен", $exit_on_success);
            return true;
        } catch (\Exception $e) {
            $this->fail($e->getMessage(), $exit_on_fail);
            return false;
        }

    }
}
