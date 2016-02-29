<?
function BXIBlockAfterSave(&$arFields)
{
    if (isset($_REQUEST['CURRENCY_RU'])) {
        $currencyRu = $_REQUEST['useOnRU'] == 'EN' ? $_REQUEST['CURRENCY_EN'] : $_REQUEST['CURRENCY_RU'];
        CIblockElement::SetPropertyValuesEx($arFields['ID'], false, [
            'LANG_CURRENCY_RU' => $currencyRu
        ]);
    }

    if (isset($_REQUEST['CURRENCY_EN'])) {
        $currencyEn = $_REQUEST['useOnEN'] == 'RU' ? $_REQUEST['CURRENCY_RU'] : $_REQUEST['CURRENCY_EN'];
        CIblockElement::SetPropertyValuesEx($arFields['ID'], false, [
            'LANG_CURRENCY_EN' => $currencyEn
        ]);
    }

    if (isset($_REQUEST['eq']) && !empty($_REQUEST['eq'])) {
        \Bitrix\Main\Loader::includeModule('catalog');
        $eq = \Wolk\Core\Helpers\ArrayHelper::index($_REQUEST['eq'], 'ID');
        $stEq = \Wolk\Core\Helpers\ArrayHelper::index($_REQUEST['steq'], 'ID');
        $resultEq = $eq + $stEq;
        $curEq = \Wolk\OEM\EventEquipmentPricesTable::getList([
            'filter' =>
                [
                    'EVENT_ID' => $arFields['ID']
                ]
        ])->fetchAll();
        foreach ($curEq as $eq) {
            \Wolk\OEM\EventEquipmentPricesTable::delete($eq['ID']);
        }
        unset($eq);

        foreach ($resultEq as $eq) {
            $ruItem = [
                'EQUIPMENT_ID' => $eq['ID'],
                'EVENT_ID'     => $arFields['ID'],
                'PRICE'        => $_REQUEST['useOnRU'] == 'EN' ? $eq['PRICE_EN'] : $eq['PRICE_RU'],
                'SITE_ID'      => 'RU'
            ];
            if (!$ruItem['PRICE']) {
                $arRuPrice = GetCatalogProductPrice($ruItem['EQUIPMENT_ID'], 1);
                if ($arRuPrice['CURRENCY'] != $currencyRu) {
                    $arRuPrice['PRICE'] = CCurrencyRates::ConvertCurrency($arRuPrice['PRICE'] ?: 0, $arRuPrice['CURRENCY'], $currencyRu);
                }
                $ruItem['PRICE'] = $arRuPrice['PRICE'] ?: 0;
            }
            \Wolk\OEM\EventEquipmentPricesTable::add($ruItem);

            $enItem = [
                'EQUIPMENT_ID' => $eq['ID'],
                'EVENT_ID'     => $arFields['ID'],
                'PRICE'        => $_REQUEST['useOnEN'] == 'RU' ? $eq['PRICE_RU'] : $eq['PRICE_EN'],
                'SITE_ID'      => 'EN'
            ];
            if (!$enItem['PRICE']) {
                $arPrice = GetCatalogProductPrice($enItem['EQUIPMENT_ID'], 1);
                if ($arPrice['CURRENCY'] != $currencyEn) {
                    $arPrice['PRICE'] = CCurrencyRates::ConvertCurrency($arPrice['PRICE'] ?: 0, $arPrice['CURRENCY'], $currencyEn);
                }
                $enItem['PRICE'] = $arPrice['PRICE'] ?: 0;
            }
            \Wolk\OEM\EventEquipmentPricesTable::add($enItem);
        }
    }

    return $arFields;
}