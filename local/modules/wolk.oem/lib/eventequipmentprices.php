<?php namespace Wolk\OEM;

use Bitrix\Main\Entity;

class EventEquipmentPricesTable extends Entity\DataManager
{

    public static function getTableName()
    {
        return 'wolk_event_equipment_prices';
    }


    public static function getMap()
    {
        return [
            new Entity\IntegerField('ID', [
                'primary'      => true,
                'autocomplete' => true,
                'title'        => 'ID'
            ]),
            new Entity\IntegerField('EVENT_ID'),
            new Entity\IntegerField('EQUIPMENT_ID'),
            new Entity\StringField('SITE_ID'),
            new Entity\FloatField('PRICE'),
        ];
    }
}