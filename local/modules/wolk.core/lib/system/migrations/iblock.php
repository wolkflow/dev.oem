<?php
namespace Linemedia\Carsale\Migrations;

/**
 * Класс для совершения операций с инфоблоками
 *
 * Class Iblock
 * @package Linemedia\Carsale\Migrations
 */
class Iblock extends UpdateTool
{

    /**
     * Инициализирует объект, задаёт набор полей, выводит границу начала задачи
     *
     * @param string    $task_id                Идентификатор задачи.
     * @param string    $migration_description  Описание сути миграции.
     * @param array     $fields                 Набор полей инфоблока.
     *
     * @throws MigrationException
     */
    public function __construct($task_id, $migration_description, $fields = [])
    {
        if (!\Bitrix\Main\Loader::includeModule(MODULE_ID_IBLOCK)) {
            throw new MigrationException('Module iblock not installed');
        }
        parent::__construct($task_id, $migration_description, $fields);
    }


    /**
     * Проверяет существование инфоблока по заданному ранее набору полей
     * @return mixed - ID обнаруженного инфоблока или false, если ничего не найдено
     *
     * @throws MigrationException
     */
    public function isExist()
    {
        // Сразу вернём результат, если уже искали
        if ($this->id) {
            return $this->id;
        }

        // Проверим заданные поля и составим фильтр
        if (empty($this->fields["CODE"])) {
            throw new MigrationException("Невозможно определить, существует ли инфоблок, так как не задан его символьный код");
        }

        $iblock = \Linemedia\Carsale\Helpers\IBlock::getIblock($this->fields['CODE']);
        if (empty($iblock)) {
            /*
             * Получаем id инфоблока по его символьному коду и типу,
             * если были заданы в конструкторе миграции
             */
            $filter = ['CODE' => $this->fields['CODE']];

            if (empty($this->fields["IBLOCK_TYPE_ID"])) {
                throw new MigrationException('Не указан тип инфоблока');
            }

            $filter['TYPE'] = $this->fields['IBLOCK_TYPE_ID'];

            $iblock = \CIBlock::GetList(['id' => 'asc'], $filter)->Fetch();
        }

        if (!$iblock["ID"]) {
            return false;
        }

        // Если инфоблок найден запоминаем его id
        $this->id = $iblock["ID"];
        $this->fields["NAME"] = $iblock["NAME"];
        $this->fields["IBLOCK_ID"] = $iblock["ID"];
        return $this->id;
    }


    /**
     * Изменяет поля инфоблока
     * @param array    $iblock_fields массив со значениями полей инфоблока
     * @param bool    $exit_on_success завершить если успешно
     * @param bool    $exit_on_fail завершить если ошибка
     *
     * @return bool
     */
    public function updateFields($iblock_fields, $exit_on_success = false, $exit_on_fail = true)
    {
        $iblock_id = $this->isExist();

        if ($iblock_id) {
            try {
                if (!empty($iblock_fields)) {
                    \CAllIBlock::SetFields($iblock_id, $iblock_fields);
                }

                $this->success(
                    "Инфоблок {$this->fields['NAME']} ({$iblock_id}) успешно изменен",
                    $exit_on_success
                );
                return true;
            } catch (MigrationException $e) {
                $this->fail($e->getMessage(), $exit_on_fail);
                return false;
            }
        } else {
            $this->fail(
                'Не удалось изменить поля - не найден инфоблок с указанным символьным кодом',
                $exit_on_fail
            );
            return false;
        }
    }

    /**
     * Добавление инфоблока
     *
     * @param bool $exit_on_success
     * @param bool $exit_if_exists
     * @param bool $exit_on_fail
     * @param bool $update_if_exists
     *
     * @return bool|int ID добавленного инфоблока или false в случае ошибки
     */
    public function add($exit_on_success = false, $exit_if_exists = false, $exit_on_fail = true, $update_if_exists = false)
    {
        // Проверим, было ли уже выполнено добавление
        try {
            $iblock_id = $this->isExist();
            if ($iblock_id) {
                // Если инфоблок уже существует, сообщим об отмене действия
                if ($update_if_exists) {
                    $this->status("Инфоблок " . $this->fields["NAME"] . "(" . $iblock_id ."): " . " уже существует");
                    $this->update($exit_on_success, $exit_on_fail);
                    return $this->id;
                } else {
                    $this->cancel("Инфоблок " . $this->fields["NAME"] . "(" . $iblock_id ."): " . " уже существует", $exit_if_exists);
                }
                return $this->id;
            }
        } catch (\Exception $e) {
            // Если невозможно проверить, существует ли инфоблок, то дальше не идём
            $this->fail($e->getMessage(), $exit_on_fail);
        }
        // Добавим ИБ
        $iblock = new \CIBlock();
        $this->id = $iblock->Add($this->fields);
        //Получилось?
        if (!$this->id) {
            $this->fail("Не удалось добавить инфоблок " . $this->fields["CODE"] . ": " . $iblock->LAST_ERROR, $exit_on_fail);
            return false;
        }
        // Если да, то сообщим об этом и вернём ID добавленного ИБ
        $this->success("Инфоблок " . $this->fields["CODE"] . " успешно добавлен, ID=" . $this->id, $exit_on_success);
        return $this->id;
    }

    /**
     * Изменение заданных параметров инфоблока
     *
     * @param array         $params
     * @param bool|false    $exit_on_success
     * @param bool|true     $exit_on_fail
     *
     * @return bool
     *
     * @throws \Linemedia\Carsale\Exception
     */
    public function updateParams(array $params, $exit_on_success = false, $exit_on_fail = true)
    {
        // Проверим, существует ли обновляемый инфоблок
        $iblock_id = $this->isExist();
        if (!$iblock_id) {
            $this->fail("Инфоблок " . $this->fields["CODE"] . " не найден", $exit_on_fail);
            return false;
        }

        // Попробуем обновить его параметры
        try {
            $iblock = new \CIBlock();
            if (!$iblock->Update($iblock_id, $params)) {
                $this->fail("Не удалось изменить инфоблок " . $this->fields["CODE"] . "(" . $iblock_id . "): " . $iblock->LAST_ERROR, $exit_on_fail);
                return false;
            }
            $this->success("Инфобок " . $this->fields["CODE"] . "(" . $iblock_id . "): " . " успешно изменен", $exit_on_success);
            return true;
        } catch (\Exception $e) {
            $this->fail($e->getMessage(), $exit_on_fail);
            return false;
        }

        return false;
    }


    /**
     * Обновляет параметры инфоблока заданными в конструкторе данными
     *
     * @param bool|false    $exit_on_success
     * @param bool|true     $exit_on_fail
     *
     * @return bool
     */
    protected function update($exit_on_success = false, $exit_on_fail = true)
    {
        return $this->updateParams($this->fields, $exit_on_success, $exit_on_fail);
    }


    /**
     * Обновляет тип инфоблока
     *
     * @param string        $new_type
     * @param bool|false    $exit_on_success
     * @param bool|true     $exit_on_fail
     *
     * @return bool
     */
    public function updateType($new_type, $exit_on_success = false, $exit_on_fail = true)
    {
        return $this->updateParams(['IBLOCK_TYPE_ID' => $new_type], $exit_on_success, $exit_on_fail);
    }


    /**
     * Добавляет новый тип инфоблоков
     *
     * @param array     $iblock_type_fields
     * @param bool|true $add_iblock
     *
     * @return int|bool ID созданного инфоблока или успешность добавления типа
     *
     * @throws MigrationException
     */
    public function addType($iblock_type_fields, $add_iblock = true)
    {
        if (!self::isTypeExists($iblock_type_fields['ID'])) {
            $iblock_type = new \CIBlockType();
            if (!$iblock_type->Add($iblock_type_fields)) {
                $this->fail('Не удалось добавить тип инфоблоков: ' . $iblock_type->LAST_ERROR);
                return false;
            }

            $this->success('Тип инфоблоков ' . $iblock_type_fields['LANG']['ru']['NAME'] . ' успешно добавлен');
        } else {
            $this->cancel('Тип инфоблока уже существует', false);
        }

        if ($add_iblock) {
            return $this->add();
        }

        return true;
    }


    /**
     * Проверяет существование типа инфоблока
     *
     * @param string $iblock_type_id
     *
     * @return bool
     */
    protected static function isTypeExists($iblock_type_id)
    {
        return (bool) \CIBlockType::GetByID($iblock_type_id)->Fetch();
    }
}
