<?php namespace Wolk\OEM;

use Bitrix\Main\Entity;

class EventsStandsSizesTable extends Entity\DataManager
{
	public static function getTableName() {
		return 'wolk_events_stands_sizes';
	}

	public static function getMap() {
		return [
			new Entity\IntegerField('ID', [
				'primary'      => true,
				'autocomplete' => true,
				'title'        => 'ID'
			]),
			new Entity\IntegerField('WIDTH', [
				'title' => 'Width'
			]),
			new Entity\IntegerField('DEPTH', [
				'title' => 'Depth'
			]),
			new Entity\IntegerField('ENUM_ID'),
			new Entity\ReferenceField(
				'SELECTED_STAND',
				'Bitrix\Iblock\PropertyEnumerationTable',
				['this.ENUM_ID' => 'ref.ID'],
				['join_type' => 'INNER']
			)
		];
	}
}