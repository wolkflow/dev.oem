<?php

namespace Wolk\Core\System\Migrations;

/**
 * Класс для совершения действий пользовательскими полями.
 */
class UserFieldSection extends UserFieldBase
{
    private $iblock_id;

    /**
     * Идентификатор сущности
     */
    const ENTITY_ID = 'IBLOCK_%s_SECTION';

    /**
     * Инициализирует объект, задаёт поля пользовательского поля, выводит границу начала задачи
     * @param string $task_id - идентификатор задачи.
     * @param string $migration_description Описание сути миграции. Можно задать позже через setDescription.
     * @param array $user_fields - поля пользовательского поля.
     * @param $iblock_id
     * @throws \Linemedia\Carsale\Exception
     */
    public function __construct($task_id, $migration_description, $user_fields = [], $iblock_id)
    {
        if (!$iblock_id) {
            throw new \Exception('Не задан идетификатор инфоблока');
        }

        $this->iblock_id = $iblock_id;

        parent::__construct($task_id, $migration_description, $user_fields);
    }

    /**
     * Получить идентификатор сущности
     *
     * @return string
     */
    public function getEntityId()
    {
        return sprintf(static::ENTITY_ID, $this->iblock_id);
    }

    /**
     * Получить id инфоблока
     * @return int
     */
    public function getIblockId()
    {
        return $this->iblock_id;
    }

    /**
     * Установить id инфоблока
     *
     * @param int $iblock_id
     */
    public function setIblockId($iblock_id)
    {
        $this->iblock_id = $iblock_id;
    }
}