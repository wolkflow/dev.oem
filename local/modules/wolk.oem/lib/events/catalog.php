<?php namespace Wolk\OEM\Events;

class Catalog
{
	public static function onGetOptimalPriceHandler($intProductID, $quantity, $arUserGroups, $renewal, $arPrices, $siteID, $arDiscountCoupons) {
		$res = \CIBlockElement::GetByID($intProductID);
		if($obElement = $res->GetNextElement(false, false)) {
			$arOffer = $obElement->GetFields();
			$arOffer['PROPS'] = $obElement->GetProperties();

			$res = \CIBlockElement::GetByID($arOffer['PROPS']['CML2_LINK']['VALUE']);
			if($obElement = $res->GetNextElement(false, false)) {
				$arStand = $obElement->GetFields();
				$arPrice = \CPrice::GetBasePrice($arStand['ID']);
				$resultPrice = array_merge($arPrice, [
					'PRICE' => ($arOffer['PROPS']['WIDTH']['VALUE'] * $arOffer['PROPS']['DEPTH']['VALUE']) * $arPrice['PRICE']
				]);
			}
		}

		return [
			'PRICE' => $arPrice,
		    'RESULT_PRICE' => $resultPrice
		];
	}
}