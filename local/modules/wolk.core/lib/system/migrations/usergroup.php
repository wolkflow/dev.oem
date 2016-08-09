<?php
/* 
 * Пример использования: добавление новой группы пользователей
 *
 use Linemedia\Carsale\Migrations\UserGroup;
 $group = new UserGroup(
    "CP_1511",
    "Добавляем группу для каких-то лоботрясов",
    [
        "NAME"          => "CarPrice: новая группа пользователей",
        "DESCRIPTION"   => "Зачем нужна эта группа?",
        "C_SORT"        => 100, // По умолчанию 100
        "STRING_ID"     => "CARPRICE_GROUP",
    ]
 );
 $new_group_id = $group->add(); // Для дальнейших действий с группой
 $group->add(true);             // Для вывода информации об окончании и завершения работы скрипта
 * 
 */

/*
 * Пример использования: обновление существующей группы
 *
use Linemedia\Carsale\Migrations\UserGroup;
$group = new UserGroup(
    "CP_1511",
    "Меняем группу зачем-то",
    [
        "ID"    => GROUP_ID,
        "NAME"  => "Новое название"
    ]
);
$group->update();   // Для вывода информации об окончании и завершения работы скрипта
if ($group->update(false)) {
    // Действия при удачном обновлении группы
}
 * 
 */

namespace Wolk\Core\System\Migrations;

/**
 * Класс для совершения действий с группой пользователей
 *
 * @author Александр Акмурзин a.akmurzin@carprice.ru
 */
class UserGroup extends UpdateTool
{
    
    /** @var mixed ID, символьный идентификатор или наименование группы */
    public $identifier;
    
    
    /**
     * Инициализирует объект, задаёт поля группы пользователей, выводит границу начала задачи
     * 
     * @param string    $task_id - идентификатор задачи.
     * @param string    $migration_description  Описание сути миграции. Можно задать позже через setDescription.
     * @param array     $arGroupFields - поля группы пользователей.
     * 
     * @link http://dev.1c-bitrix.ru/api_help/main/reference/cgroup/
     */
    public function __construct($task_id, $migration_description, $arGroupFields = []) {
        parent::__construct($task_id, $migration_description, $arGroupFields);
    }
    
    
    /**
     * Ищет группу пользователей по заданным ранее полям и запоминает её ID
     * 
     * @return bool - группа найдена?
     * 
     * @throws \Exception
     */
    public function isExist()
    {
        // Поищем группу по имеющимся данным
        if (isset($this->fields["ID"])) {
            $this->identifier = intval($this->fields["ID"]);
            $rsGroup = \CGroup::GetById($this->fields["ID"]);
        } else {
            $arFilter = [];
            if (isset($this->fields["STRING_ID"])) {
                $this->identifier = strval($this->fields["STRING_ID"]);
                $arFilter["STRING_ID"] = $this->fields["STRING_ID"];
            } elseif (isset($this->fields["NAME"])) {
                $this->identifier = strval($this->fields["NAME"]);
                $arFilter["NAME"] = $this->fields["NAME"];
            } else {
                throw new \Exception("Невозможно определить, существует ли группа, так как не задано ни одно из полей: ID, NAME, STRING_ID");
            }
            $rsGroup = \CGroup::GetList($by, $order, $arFilter);
        }
        
        $arGroup = $rsGroup->Fetch();
        if (!$arGroup) {
            return false;
        }
        
        $this->id = $arGroup["ID"];
        return true;
    }
    
    
    /**
     * Проверяет наличие группы и добавляет её, если ничего не найдено
     * 
     * @param bool $exit_on_success - работа скрипта завершается на успешном добавлении группы?
     * @param bool $exit_if_exists - прерывать выполнение скрипта, если группа найдена?
     * @param bool $exit_on_fail - прерывать выполнение скрипта, если произошла ошибка при добавлении?
     * 
     * @return mixed - ID группы, если она существует или успешно добавлена, либо false в случае ошибки
     */
    public function add($exit_on_success = false, $exit_if_exists = false, $exit_on_fail = true)
    {
        // Проверим, было ли уже выполнено добавление
        try {
            if ($this->isExist()) {
                // Если группа уже существует, сообщим об отмене действия
                $this->cancel("Группа $this->identifier уже существует", $exit_if_exists);
                return $this->id;
            }
        } catch(\Exception $e) {
            // Если невозможно проверить, существует ли группа, то дальше не идём
            $this->fail($e->getMessage());
        }
        
        $group = new \CGroup();
        $group_id = $group->Add($this->fields);
        
        // Если не удалось создать группу, то надо сообщить об этом
        if (!$group_id) {
            $this->fail("Ошибка добавления группы $this->identifier: " . $group->LAST_ERROR, $exit_on_fail);
            return false;
        }
        
        $this->id = intval($group_id);
        $this->success("Группа $this->identifier была добавлена, ID=" . $this->id, $exit_on_success);
        return $this->id;
    }
    
    
    /**
     * Ищет группу по указанному ранее ID и обновляет её
     * 
     * @param boolean $exit_on_success - работа скрипта завершается после успешного выполнения?
     * @param boolean $exit_on_fail - прерывать работу скрипта при сбое в обновлении данных?
     * 
     * @return boolean - обновление выполнено успешно?
     */
    public function update($exit_on_success = true, $exit_on_fail = true)
    {
        // Проверим, существует ли группа, которую будем обновлять
        try {
            $this->isExist();
        } catch(\Exception $e) {
            // Если невозможно проверить, существует ли группа, то дальше не идём
            $this->fail($e->getMessage());
        }
        
        // Обновляем все заданные поля, кроме ID
        unset($this->fields["ID"]);
        $group = new \CGroup();
        if (!$group->Update($this->id, $this->fields)) {
            $this->fail("Ошибка обновления данных группы $this->identifier", $exit_on_fail);
            return false;
        }
        
        $this->success("Данные группы $this->identifier обновлены", $exit_on_success);
        return true;
    }

    /**
     * Проверяет наличие группы и удаляет её если группа найдена
     *
     * @param bool $exit_on_success - работа скрипта завершается при успешном удалении группы?
     * @param bool $exit_if_not_exists - прерывать выполнение скрипта, если группа не найдена?
     * @param bool $exit_on_fail - прерывать выполнение скрипта, если произошла ошибка при удалении?
     *
     * @return bool
     */
    public function delete($exit_on_success = false, $exit_if_not_exists = false, $exit_on_fail = true)
    {
        try {
            if (!$this->isExist()) {
                $this->cancel("Группа $this->identifier не существует", $exit_if_not_exists);
                return false;
            }

            $group = new \CGroup();
            $result = $group->Delete($this->id);

            // Если не удалось удалить группу, то надо сообщить об этом
            if (!$result) {
                $this->fail("Ошибка удаления группы $this->identifier: " . $group->LAST_ERROR, $exit_on_fail);
                return false;
            }

            $this->success("Группа $this->identifier была удалена", $exit_on_success);

        } catch(\Exception $e) {
            $this->fail($e->getMessage());
        }

        return true;
    }
}
