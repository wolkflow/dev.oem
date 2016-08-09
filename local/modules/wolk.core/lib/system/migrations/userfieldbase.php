<?php

namespace Wolk\Core\System\Migrations;

/**
 * Абстрактный класс для совершения действий пользовательскими полями
 *
 * Class UserFieldBase
 * @package Linemedia\Carsale\Migrations
 */
abstract class UserFieldBase extends UpdateTool
{

    /**
     * @var string $field_name - Название пользовательского поля
     */
    private $field_name;

    /**
     * Инициализирует объект, задаёт поля пользовательского поля, выводит границу начала задачи
     * @param string $task_id - идентификатор задачи.
     * @param string $migration_description Описание сути миграции. Можно задать позже через setDescription.
     * @param array $user_fields - поля пользовательского поля.
     */
    public function __construct($task_id, $migration_description, $user_fields = [])
    {
        $user_fields['ENTITY_ID'] = $this->getEntityId();
        parent::__construct($task_id, $migration_description, $user_fields);
    }

    /**
     * Ищет пользовательское поле по заданным ранее полям
     * @return bool
     * @throws \Exception
     */
    public function isExist()
    {
        if (isset($this->fields['FIELD_NAME'])) {
            $user_fields = \CUserTypeEntity::GetList([], [
                'ENTITY_ID'  => $this->getEntityId(),
                'FIELD_NAME' => $this->fields['FIELD_NAME']
            ]);

            $field = $user_fields->Fetch();
            if (empty($field)) {
                return false;
            }

            $this->id = $field['ID'];
            $this->field_name = $field['FIELD_NAME'];
            return true;
        } else {
            throw new \Exception('Невозможно определить, существует ли пользовательское поле, так как не задано FIELD_NAME');
        }
    }

    /**
     * Добавление пользовательского поля
     * @param bool $exit_on_success - работа скрипта завершается на успешном добавлении группы?
     * @param bool $exit_if_exists - прерывать выполнение скрипта, если группа найдена?
     * @param bool $exit_on_fail - прерывать выполнение скрипта, если произошла ошибка при добавлении?
     *
     * @return mixed - ID пользовательского поля, если оно существует или успешно добавлено, либо false в случае ошибки
     */
    public function add($exit_on_success = false, $exit_if_exists = false, $exit_on_fail = true)
    {
        try {
            if ($this->isExist()) {
                $this->cancel("Пользовательское поле $this->field_name уже существует", $exit_if_exists);
                return $this->id;
            }
        } catch(\Exception $e) {
            $this->fail($e->getMessage());
        }

        $user_type_entity = new \CUserTypeEntity();
        $user_type_entity_id = $user_type_entity->Add($this->fields);

        if (!$user_type_entity_id) {
            $this->fail("Ошибка добавления пользовательского поля $this->field_name", $exit_on_fail);
            return false;
        }

        $this->id = intval($user_type_entity_id);
        $this->success("Пользовательское поле $this->field_name успешно добавлено, ID=" . $this->id, $exit_on_success);
        return $this->id;
    }

    /**
     * Получить идентификатор сущности
     *
     * @return mixed
     */
    abstract public function getEntityId();
}