<?php
/** @var array $arResult */
use Bitrix\Sale\Internals\OrderPropsValueTable;
use Wolk\Core\Helpers\ArrayHelper;

$ordersIds = ArrayHelper::getColumn($arResult['ORDERS'], 'ORDER.ID');
$indexedOrders = ArrayHelper::index($arResult['ORDERS'], 'ORDER.ID');

$result = \Bitrix\Sale\Internals\StatusTable::getList([
    'select' =>
        [
            'ID',
            'NAME' => 'Bitrix\Sale\Internals\StatusLangTable:STATUS.NAME'
        ],
    'filter' =>
        [
            '=Bitrix\Sale\Internals\StatusLangTable:STATUS.LID' => \Bitrix\Main\Context::getCurrent()->getLanguage()
        ]
]);

while ($row = $result->fetch()) {
    $arResult['STATUSES'][$row['ID']] = $row['NAME'];
}

#$arResult['STATUSES'] = \Bitrix\Sale\Helpers\Admin\Blocks\OrderStatus::getStatusesList($GLOBALS['USER']->GetID());

$propsValues = OrderPropsValueTable::getList([
    'filter' =>
        [
            'ORDER_ID' => $ordersIds,
            'CODE'     => 'eventId'
        ]
])->fetchAll();

foreach ($propsValues as $propValue) {
    $indexedOrders[$propValue['ORDER_ID']]['EVENT_ID'] = $propValue['VALUE'];
}

foreach ($indexedOrders as $order) {
	$order['ORDER']['PROPS'] = Wolk\Core\Helpers\SaleOrder::getProperties($order['ORDER']['ID']);
    $groupedOrders[$order['EVENT_ID']][] = $order;
}

$eventsIds = ArrayHelper::getColumn($propsValues, 'VALUE');

$events = \Bitrix\Iblock\ElementTable::getList([
    'filter' =>
        [
            'ID' => $eventsIds
        ]
])->fetchAll();

foreach ($events as &$event) {
    $event['ORDERS'] = $groupedOrders[$event['ID']];
}
unset($event);

$arResult['EVENTS'] = $events;