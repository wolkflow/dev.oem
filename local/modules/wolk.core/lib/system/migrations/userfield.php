<?php

namespace Wolk\Core\System\Migrations;

/**
 * Класс для совершения действий пользовательскими полями
 *
 * Class UserField
 * @package Linemedia\Carsale\Migrations
 */
class UserField extends UserFieldBase
{
    /**
     * Идентификатор сущности
     */
    const ENTITY_ID = 'USER';


    /**
     * Получить идентификатор сущности
     *
     * @return string
     */
    public function getEntityId()
    {
        return static::ENTITY_ID;
    }
}