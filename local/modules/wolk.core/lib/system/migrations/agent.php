<?php

/*
 * Пример добавления нового агента
 *
use Linemedia\Carsale\Migrations\Agent;
$agent = new Agent(
    'CP-6441',
    'Добавление агента',
    [
        'NAME'          => '\Linemedia\Carsale\Agents\Agent::event()',
        'MODULE'        => 'linemedia.carsale',
        'PERIOD'        => 'N',
        'INTERVAL'      => 86400,
        'DATE_CHECK'    => '',
        'ACTIVE'        => 'Y',
        'NEXT_EXEC'     => '',
        'SORT'          => 100
    ]
);
$agent->add();
 *
 */

/*
 * Пример обновления существующего агента
 * Поиск агента осуществляется по имени и названию модуля
 *
use Linemedia\Carsale\Migrations\Agent;
$tmpl = new Agent(
    'CP-6441',
    'Обновление агента',
    [
        'NAME'          => '\Linemedia\Carsale\Agents\Agent::event()',
        'MODULE'        => 'linemedia.carsale',
        'INTERVAL'      => 3600,
        'SORT'          => 500
    ]
);
$agent->update();
 *
 */

namespace Linemedia\Carsale\Migrations;

use CAgent;
use Exception;
use ReflectionMethod;

/**
 * Класс миграции агентов
 *
 * Class Agent
 * @package Linemedia\Carsale\Migrations
 */
class Agent extends UpdateTool
{
    /**
     * Инициализирует объект, задаёт набор полей, выводит границу начала задачи
     *
     * @param string    $task_id                Идентификатор задачи
     * @param string    $migration_description  Описание сути миграции. Можно задать позже через setDescription.
     * @param array     $agent_fields           Набор полей
     *
     * @link http://dev.1c-bitrix.ru/api_help/main/reference/ceventmessage/
     */
    public function __construct($task_id, $migration_description, $agent_fields = [])
    {
        parent::__construct($task_id, $migration_description, $agent_fields);
    }

    /**
     * Регистрация нового агента
     *
     * @param bool|true $exit_on_success
     * @param bool|true $exit_if_exists
     * @param bool|true $exit_on_fail
     */
    public function add($exit_on_success = true, $exit_if_exists = true, $exit_on_fail = true)
    {
        $this->checkFields($this->fields);

        if ($this->isExist()) {
            $this->fail('Агент с таким именем уже зарегестирован в системе.', $exit_if_exists);
        }

        try {
            $agent_id = CAgent::AddAgent(
                $this->fields['NAME'],
                $this->fields['MODULE'],
                $this->fields['PERIOD'] ?: 'N',
                $this->fields['INTERVAL'] ?: 86400,
                $this->fields['DATE_CHECK'] ?: '',
                $this->fields['ACTIVE'] ?: 'Y',
                $this->fields['NEXT_EXEC'] ?: '',
                $this->fields['SORT'] ?: 100
            );

            if ($agent_id) {
                $this->success('Агент успешно добавлен.', $exit_on_success);
            } else {
                $this->fail('Произошла ошибка при добавлении агента', $exit_on_fail);
            }
        } catch (Exception $e) {
            $this->fail($e->getMessage(), $exit_on_fail);
        }
    }

    /**
     * Обновление существующего агента
     *
     * @param bool|true $exit_on_success
     * @param bool|true $exit_if_not_exists
     * @param bool|true $exit_on_fail
     */
    public function update($exit_on_success = true, $exit_if_not_exists = true, $exit_on_fail = true)
    {
        $this->checkFields($this->fields);

        $id = $this->isExist();

        if (!$this->isExist()) {
            $this->fail('Агент с таким именем не зарегестирован в системе.', $exit_if_not_exists);
        }

        $agent = $this->getAgent();
        $id = $agent['ID'];
        $fields = $this->createUpdateFields();

        try {
            if (CAgent::Update($id, $fields)) {
                $this->success('Агент успешно обновлен.', $exit_on_success);
            } else {
                $this->fail('Произошла ошибка при обновлении агента', $exit_on_fail);
            }
        } catch (Exception $e) {
            $this->fail($e->getMessage(), $exit_on_fail);
        }
    }

    /**
     * Проверка заданных полей на корректность
     *
     * @param array $agent_fields
     */
    private function checkFields($agent_fields)
    {
        if (empty($agent_fields['NAME'])) {
            $this->fail('Не указано имя метода');
        }
        $this->checkMethod($agent_fields['NAME']);
        if (empty($agent_fields['MODULE'])) {
            $this->fail('Не указано имя модуля');
        }
    }

    /**
     * Проверка корректности указанного метода
     *
     * @param string $method_name
     */
    private function checkMethod($method_name)
    {
        list($class, $method) = explode('::', $method_name);

        $method = str_replace(['()', ';'], '', $method);

        try {
            $reflection_method = new ReflectionMethod($class, $method);
        } catch (Exception $e) {
            $this->fail('Метод ' . $method_name . ' не существует');
        }

        if (!$reflection_method->isStatic()) {
            $this->fail('Метод ' . $method_name . ' не является статическим');
        }
    }

    /**
     * Подготовка полей для обновления агента
     *
     * @return array
     */
    private function createUpdateFields()
    {
        $fields = [
            "NAME" => $this->fields['NAME'],
            "MODULE_ID" => $this->fields['MODULE'],
        ];

        if ($this->fields['PERIOD']) {
            $fields["IS_PERIOD"] = $this->fields['PERIOD'];
        }
        if ($this->fields['INTERVAL']) {
            $fields["AGENT_INTERVAL"] = $this->fields['INTERVAL'];
        }
        if ($this->fields['ACTIVE']) {
            $fields["ACTIVE"] = $this->fields['ACTIVE'];
        }
        if ($this->fields['SORT']) {
            $fields["SORT"] = $this->fields['SORT'];
        }

        return $fields;
    }

    /**
     * Проверяет существование агента по заданному имени и модулю
     *
     * @return bool
     */
    public function isExist()
    {
        $res_agent = CAgent::GetList([],
            [
                'NAME' => $this->fields['NAME'],
                'MODULE' => $this->fields['MODULE']
            ]
        );

        $agent = $res_agent->fetch();

        if ($agent) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Поиск существование агента по заданному имени и модулю
     *
     * @return mixed массив полей найденного агента или false
     */
    private function getAgent()
    {
        $res_agent = CAgent::GetList([],
            [
                'NAME' => $this->fields['NAME'],
                'MODULE' => $this->fields['MODULE']
            ]
        );

        return $res_agent->fetch();
    }
}