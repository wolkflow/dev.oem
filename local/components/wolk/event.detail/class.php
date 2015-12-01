<?

use Bitrix\Main\Loader;
use Wolk\Core\Helpers\ArrayHelper;
use Wolk\OEM\Components\BaseListComponent;

class EventDetailComponent extends BaseListComponent
{
    public function getResult()
    {
        Loader::includeModule('iblock');
        if (!$this->arParams['EVENT_ID']) {
            throw new \Bitrix\Main\ArgumentException('Мероприятие не найдено');
        }
        $offersFilter = [];
        if ($this->arParams['WIDTH'] && $this->arParams['DEPTH'] && $this->arParams['TYPE']) {
            $offersFilter = [
                'PROPERTY_WIDTH' => $this->arParams['WIDTH'],
                'PROPERTY_DEPTH' => $this->arParams['DEPTH'],
                'PROPERTY_TYPE'  => $this->arParams['TYPE'],
            ];
        }
        $curLang = strtoupper(\Bitrix\Main\Context::getCurrent()->getLanguage());
        $res = \CIBlockElement::GetList([], [
            'ID'                               => $this->arParams['EVENT_ID'],
            "!PROPERTY_LANG_{$curLang}_ACTIVE" => false
        ]);
        if ($event = $res->GetNextElement()) {
            $arStands = [];
            $this->arResult['EVENT'] = $event->GetFields();
            $props = $event->GetProperties();
            $this->arResult['EVENT']['PROPS'] = ArrayHelper::except($props, ['STANDS']);
            if (!empty($props['STANDS']['VALUE'])) {
                $obStandOffers = CIBlockElement::getList([], [
                        'IBLOCK_ID' => STANDS_OFFERS_IBLOCK_ID,
                        'ACTIVE'    => 'Y'
                    ] + $offersFilter);
                while ($obStandOffer = $obStandOffers->GetNextElement(false, false)) {
                    $arStandOffer = $obStandOffer->GetFields();
                    $arStandOffer['PROPS'] = $obStandOffer->GetProperties();
                    $arStandOffer += CCatalogProduct::GetOptimalPrice($arStandOffer['ID']);
                    $arStandOffers[$arStandOffer['PROPS']['CML2_LINK']['VALUE']] = $arStandOffer;
                    $standsIds[] = $arStandOffer['PROPS']['CML2_LINK']['VALUE'];
                    $equipmentIds[] = $arStandOffer['PROPS']['EQUIPMENT']['VALUE'];
                }

                if (!empty($standsIds)) {
                    $obEquipment = CIBlockElement::GetList([], [
                        'IBLOCK_ID' => EQUIPMENT_IBLOCK_ID,
                        'ACTIVE'    => 'Y',
                        'ID'        => $equipmentIds
                    ], false, false, ['ID', 'IBLOCK_ID', 'NAME', 'PREVIEW_PICTURE', 'PROPERTY_*']);
                    while ($equipmentItem = $obEquipment->Fetch()) {
                        $equipmentItem['PREVIEW_PICTURE'] = CFile::ResizeImageGet(
                            $equipmentItem['PREVIEW_PICTURE'], ['width' => 420, 'height' => 270], BX_RESIZE_IMAGE_EXACT
                        )['src'];
                        $arEquipment[$equipmentItem['ID']] = $equipmentItem;
                    }

                    foreach ($arStandOffers as &$arStandOffer) {
                        foreach ($arStandOffer['PROPS']['EQUIPMENT']['VALUE'] as $num => $val) {
                            $arStandOffer['EQUIPMENT'][] = $arEquipment[$val] + ['COUNT' => $arStandOffer['PROPS']['EQUIPMENT']['DESCRIPTION'][$num]];
                        }
                    }
                    unset($arStandOffer);

                    $obStands = CIBlockElement::GetList([], [
                        'IBLOCK_ID' => STANDS_IBLOCK_ID,
                        'ACTIVE'    => 'Y',
                        'ID'        => $standsIds
                    ]);
                    while ($obStand = $obStands->GetNextElement(false, false)) {
                        $arStand = $obStand->GetFields();
                        $arStand['PREVIEW_PICTURE'] = CFile::ResizeImageGet(
                            $arStand['PREVIEW_PICTURE'], ['width' => 420, 'height' => 270], BX_RESIZE_IMAGE_EXACT
                        )['src'];
                        $arStand['PROPS'] = $obStand->GetProperties();
                        $arStand['OFFER'] = $arStandOffers[$arStand['ID']];
                        $arStands[$arStand['ID']] = $arStand;
                    }
                }
            }
            $this->arResult['ITEMS'] = $arStands;

//
//			$equipmentIds = array_unique($equipmentIds);
//
//			$equipment = $this->getEquipment($equipmentIds);
//			$stands = $this->getStands($standsIds);
//
//			foreach($this->arResult['EVENT']['STANDS'] as $standId => &$stand) {
//				$stand += $stands[$standId];
//				foreach($stand['EQUIPMENT'] as $eqId => $count) {
//					$stand['EQUIPMENT'][$eqId] = $equipment[$eqId];
//					$stand['EQUIPMENT'][$eqId]['COUNT'] = $count;
//				}
//			}
//			unset($stand);
//			if($props['PRESELECT']) {
//				$props['PRESELECT'] = $this->arResult['EVENT']['STANDS'][$props['PRESELECT']['VALUE']];
//			}
//			$this->arResult['EVENT'] += ArrayHelper::except($props, ['STANDS']);
//		}
        }

//	protected function getEquipment($ids) {
//		$result = [];
//		$res = \CIBlockElement::GetList([], [
//			'IBLOCK_ID' => EQUIPMENT_IBLOCK_ID,
//			'ID'        => $ids
//		]);
//		while($obOption = $res->GetNextElement()) {
//			$arOption = $obOption->GetFields();
//			if($arOption['PREVIEW_PICTURE']) {
//				$arOption['PREVIEW_PICTURE'] = CFile::ResizeImageGet(
//					$arOption['PREVIEW_PICTURE'], ['width' => 420, 'height' => 270], BX_RESIZE_IMAGE_EXACT
//				)['src'];
//			}
//			$arOption['PROPS'] = $obOption->GetProperties();
//			$result[$arOption['ID']] = $arOption;
//		}
//
//		return $result;
//	}

//	protected function getStands($ids) {
//		$result = [];
//		$res = \CIBlockElement::GetList([], [
//			'IBLOCK_ID' => STANDS_IBLOCK_ID,
//			'ID'        => $ids
//		]);
//		while($obStand = $res->GetNextElement()) {
//			$arStand = $obStand->GetFields();
//			$arStand['PREVIEW_PICTURE'] = CFile::ResizeImageGet(
//				$arStand['PREVIEW_PICTURE'], ['width' => 420, 'height' => 270], BX_RESIZE_IMAGE_EXACT
//			)['src'];
//			$arStand['PROPS'] = $obStand->GetProperties();
//			$result[$arStand['ID']] = $arStand;
//		}
//
//		return $result;
    }
}