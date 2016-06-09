<?php namespace Wolk\OEM;

/*

$entity = new Wolk\OEM\EventStandPricesTable();

$connection = \Bitrix\Main\Application::getConnection(\Bitrix\Main\Data\ConnectionPool::DEFAULT_CONNECTION_NAME);

$table = $entity->getEntity()->getDBTableName();

$query  = "SHOW TABLES LIKE '" . $table . "';";
$result = $connection->query($query);

if ($result->getSelectedRowsCount() == 0) {
	$entity->getEntity()->createDbTable();
}

*/

use Bitrix\Main\Entity;

class EventStandPricesTable extends Entity\DataManager
{

    public static function getTableName()
    {
        return 'wolk_event_stands_prices';
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
            new Entity\IntegerField('STAND_ID'),
            new Entity\StringField('SITE_ID'),
            new Entity\FloatField('PRICE'),
        ];
    }
}