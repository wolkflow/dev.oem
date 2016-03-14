<?

use Bitrix\Highloadblock\HighloadBlockTable;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

// https://habrahabr.ru/post/190364/
// https://habrahabr.ru/sandbox/23506/

/**
 * Class PrintInvoiceComponent
 */
class PrintInvoiceComponent extends \CBitrixComponent
{
	
	/** 
	 * ��������� ��������.
	 */
    public function onPrepareComponentParams($arParams)
    {
		// ��� �����.
		$arParams['TEMPLATE']  = (string) $arParams['TEMPLATE'];
		
		// ID ������.
		$arParams['ORDER_ID'] = (int) $arParams['ORDER_ID'];
		
		// ���� � �����.
		$arParams['PATH'] = (string) $arParams['PATH'];
		
        return $arParams;
    }
	
	
	/**
	 * ���������� ����������.
	 */
	public function executeComponent()
    {
		if (!\Bitrix\Main\Loader::includeModule('wolk.core')) {
			ShowError('������ wolk.core �� ����������.');
			return;
		}

		if (!\Bitrix\Main\Loader::includeModule('iblock')) {
			ShowError('������ iblock �� ����������.');
			return;
		}

		if (!\Bitrix\Main\Loader::includeModule('sale')) {
			ShowError('������ sale �� ����������.');
			return;
		}
		
		// ������ ��������� ������.
		$invoices = [
			'uaz' 		=> 'Uaz',
			'malcorp' 	=> 'MALCORP',
			'distance' 	=> '���������',
		];
		
		if (!array_key_exists($this->arParams['TEMPLATE'], $invoices)) {
			return;
		}
		
		// �����.
		$this->arResult['ORDER']   = CSaleOrder::getByID($this->arParams['ORDER_ID']);
		$this->arResult['PROPS']   = Wolk\Core\Helpers\SaleOrder::getProperties($this->arParams['ORDER_ID']);
		$this->arResult['BASKETS'] = Wolk\Core\Helpers\SaleOrder::getBaskets($this->arParams['ORDER_ID']);
		$this->arResult['USER']    = CUser::getByID($this->arResult['ORDER']['USER_ID'])->Fetch();
		
		$event = CIBlockElement::getByID($this->arResult['PROPS']['eventId']['VALUE'])->GetNextElement();
		
		$this->arResult['EVENT'] = $event->getFields();
		$this->arResult['EVENT']['PROPS'] = $event->getProperties();
		
		/*
		foreach ($this->arResult['BASKETS'] as $basket) {
			if ($basket['TYPE'] == 0) {
				$stand = CIBlockElement::getByID($basket['PRODUCT_ID'])->GetNextElement();
				
				$this->arResult['STAND'] = $stand->getFields();
				$this->arResult['STAND']['PROPS'] = $stand->getProperties();
				
				break;
			}
		}
		*/
		
		// ����������� �������.
		$this->includeComponentTemplate($this->arParams['TEMPLATE']);
	}
	
}




