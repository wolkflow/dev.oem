<?php

namespace Linemedia\Carsale\Migrations;

if (!\Bitrix\Main\Loader::includeModule('iblock')) {
    exit('Module iblock not installed');
}

/**
 * Класс для совершения действий с элементами инфоблока
 *
*/
class IblockSection extends UpdateTool
{

    /**
     * Инициализирует объект, задаёт набор полей, выводит границу начала задачи
     *
     * @param string    $task_id идентификатор задачи
     * @param string    $migration_description  Описание сути миграции. Можно задать позже через setDescription.
     * @param array     $fields набор полей секции инфоблока
     *
     */
    public function __construct($task_id, $migration_description, $fields = []) {
        parent::__construct($task_id, $migration_description, $fields);
    }


    /**
     * Проверяет существование секции инфоблока по заданному ранее набору полей
     *
     * @return int|false - ID обнаруженной секции инфоблока или false, если ничего не найдено
     *
     * @throws \Exception
     */
    public function isExist()
    {
        // Формируем из известных полей свойства фильтр
        $filter = [];
        if (empty($this->fields["CODE"])) {
            throw new \Exception("Невозможно определить, существует ли секция, так как не задан ее символьный код");
        } else {
            $this->fields["CODE"] = strval($this->fields["CODE"]);
            $filter["CODE"] = $this->fields["CODE"];
        }
        if (empty($this->fields["IBLOCK_ID"])) {
            if (empty($this->fields["IBLOCK_CODE"]) && empty($this->fields["IBLOCK_TYPE"])) {
                throw new \Exception("Невозможно определить, существует ли секция, так как не задан код инфоблока");
            } else {
                $filter["IBLOCK_CODE"] = $this->fields["IBLOCK_CODE"];
                $filter["IBLOCK_TYPE"] = $this->fields["IBLOCK_TYPE"];
            }
        } else {
            $filter["IBLOCK_ID"] = $this->fields["IBLOCK_ID"];
        }
        // Получилось что-нибудь найти по сформированному фильтру?
        $section = \CIBlockSection::GetList([], $filter)->Fetch();
        if (!$section) {
            return false;
        }
        // Если секция найдена, то запомним его ID и id инфоблока
        $this->id = $section["ID"];
        $this->fields['IBLOCK_ID'] = $section['IBLOCK_ID'];
        return $this->id;
    }


    /**
     * Добавляет секцию инфоблока
     *
     * @param bool $exit_on_success - работа скрипта завершается на успешном добавлении элемента?
     * @param bool $exit_if_exists - прерывать выполнение скрипта, если элемент не найден?
     * @param bool $exit_on_fail
     * @param bool $update_if_exists - изменить элемент если он существует
     * @return bool|int - ID добавленного элемента или false в случае неудачи
     * @internal param bool $exi t_on_fail - прерывать выполнение скрипта, если произошла ошибка при добавлении?
     */
    public function add($exit_on_success = false, $exit_if_exists = false, $exit_on_fail = true, $update_if_exists= false)
    {
        // Проверим, было ли уже выполнено добавление
        try {
            if ($this->isExist()) {
                // Если элемент уже существует, сообщим об отмене действия
                if ($update_if_exists) {
                    $this->status("Секция " . $this->fields["CODE"] . " уже существует");
                    $this->update($this->id, $exit_on_success, $exit_on_fail);
                    return $this->id;
                } else {
                    $this->cancel("Секция " . $this->fields["CODE"] . " уже существует", $exit_if_exists);
                }

                return $this->id;
            }
        } catch(\Exception $e) {
            // Если невозможно проверить, существует ли Секция, то дальше не идём
            $this->fail($e->getMessage(), $exit_on_fail);
        }
        // Добавим элемент
        $iblock_section = new \CIBlockSection();
        $this->id = $iblock_section->Add($this->fields);
        //Получилось?
        if (!$this->id) {
            $this->fail("Не удалось добавить секцию " . $this->fields["CODE"] . ": " . $iblock_section->LAST_ERROR, $exit_on_fail);
            return false;
        }
        // Если да, то сообщим об этом и вернём ID добавленного элемента
        $this->success("Секция " . $this->fields["CODE"] . " успешно добавлена, ID=" . $this->id, $exit_on_success);

        return $this->id;
    }

    /**
     * Изменяет элемент инфоблока
     * @param $section_id - идентификатор секции
     * @param $exit_on_success - завершить если успешно
     * @param $exit_on_fail - завершить если ошибка
     * @return bool
     */
    public function update($section_id, $exit_on_success = false, $exit_on_fail=true)
    {
        $section_id = (int)$section_id;
        if ($section_id <= 0) {
            $this->fail("Не удалось изменить секцию - не задан иденнтификатор секции " , $exit_on_fail);
            return false;
        }
        if (empty($this->fields['IBLOCK_ID'])) {
            $this->fail("Не задан идентификатор инфоблока" , $exit_on_fail);
            return false;
        }

        $fields = $this->fields;
        unset($fields['IBLOCK_ID']);
        unset($fields['IBLOCK_CODE']);
        unset($fields['ID']);
        try {
            if (!empty($fields)) {
                $iblock_element = new \CIBlockSection();
                if (!$iblock_element->Update($section_id, $fields)) {
                    $this->fail("Не удалось изменить поля элемента " . $this->fields["CODE"] . "(".$section_id."): " . $iblock_element->LAST_ERROR, $exit_on_fail);
                }
            }
            $this->success("Секция " . $this->fields["CODE"] . "(".$section_id."): " . " успешно изменена", $exit_on_success);
            return true;
        } catch (\Exception $e) {
            $this->fail($e->getMessage(), $exit_on_fail);
            return false;
        }

    }
}
