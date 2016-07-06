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
			'mf.ru' 	=> 'MF (ru)',
			'mf.en' 	=> 'MF (en)',
			'itemf.ru'  => 'ITEMF (ru)',
			'itemf.en'  => 'ITEMF (en)',
		];
		
		if (!array_key_exists($this->arParams['TEMPLATE'], $invoices)) {
			return;
		}
		
		// �����.
		$this->arResult['ORDER']   = CSaleOrder::getByID($this->arParams['ORDER_ID']);
		$this->arResult['PROPS']   = Wolk\Core\Helpers\SaleOrder::getProperties($this->arParams['ORDER_ID']);
		$this->arResult['BASKETS'] = Wolk\Core\Helpers\SaleOrder::getBaskets($this->arParams['ORDER_ID']);
		$this->arResult['USER']    = CUser::getByID($this->arResult['ORDER']['USER_ID'])->Fetch();
		
		
		// ���� ��������� ������.
		$rate     = (!empty($this->arResult['PROPS']['RATE']['VALUE'])) 
					? (floatval($this->arResult['PROPS']['RATE']['VALUE'])) 
					: (1);
		$currency = (!empty($this->arResult['PROPS']['RATE_CURRENCY']['VALUE'])) 
					? (strval($this->arResult['PROPS']['RATE_CURRENCY']['VALUE'])) 
					: ($this->arResult['ORDER']['CURRENCY']);
		
		
		$event = CIBlockElement::getByID($this->arResult['PROPS']['eventId']['VALUE'])->GetNextElement();
		
		if ($event) {
			$this->arResult['EVENT'] = $event->getFields();
			$this->arResult['EVENT']['PROPS'] = $event->getProperties();
		}
		
		$this->arResult['DATE'] = (!empty($this->arResult['PROPS']['INVOICE_DATE']['VALUE'])) 
								? (strtotime($this->arResult['PROPS']['INVOICE_DATE']['VALUE'])) 
								: (time());
		
		// ���������� ������� � ��������� ����������.
		$count   = 0;
		$summary = 0;
		foreach ($this->arResult['BASKETS'] as &$basket) {
			if ($basket['SUMMARY_PRICE'] > 0) {
				$count   += $basket['QUANTITY'];
				$summary += $basket['SUMMARY_PRICE'];
			}
			$basket['PRICE'] *= $rate;
		}
		
		
		// ��������� ������� � ������� (��� �������).
		$this->arResult['ORDER']['BASKET_PRICE']  = $summary;
		$this->arResult['ORDER']['BASKET_PRICE'] *= $rate;
		
		// ��������� ������ ��� ������� � � ���������.
		$this->arResult['ORDER']['BASKET_TOTAL_PRICE']  = $this->arResult['ORDER']['PRICE'] - $this->arResult['ORDER']['TAX_VALUE'];
		$this->arResult['ORDER']['BASKET_TOTAL_PRICE'] *= $rate;
		
		if ($count > 0) {
			$overprice = ($this->arResult['ORDER']['BASKET_TOTAL_PRICE'] - $this->arResult['ORDER']['BASKET_PRICE']) / $count;
			
			foreach ($this->arResult['BASKETS'] as &$basket) {
				if ($basket['SUMMARY_PRICE'] > 0) {
					$basket['SURCHARGE_PRICE']  = $basket['PRICE'] + $overprice;
					$basket['SURCHARGE_PRICE'] *= $rate;
					
					$basket['SURCHARGE_SUMMARY_PRICE']  = $basket['SURCHARGE_PRICE'] * $basket['QUANTITY'];
					$basket['SURCHARGE_SUMMARY_PRICE'] *= $rate;
				}
			}
		}
		
		// ��������������� ����.
		$this->arResult['ORDER']['PRICE']     *= $rate;
		$this->arResult['ORDER']['TAX_VALUE'] *= $rate;
		
		
		// ��������������� ������.
		$this->arResult['ORDER']['CURRENCY'] = $currency;
		
		
		// ����������� �������.
		$this->includeComponentTemplate($this->arParams['TEMPLATE']);
	}
	
}




