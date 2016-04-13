<?

use Bitrix\Highloadblock\HighloadBlockTable;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

/**
 * Class MailUserComponent
 */
class MailUserComponent extends \CBitrixComponent
{
	
	/** 
	 * Установка настроек.
	 */
    public function onPrepareComponentParams($arParams)
    {
		// ID пользователя.
		$arParams['ID'] = (int) $arParams['ID'];
		
		// ID мероприятия.
		$arParams['EVENT'] = (string) $arParams['EVENT'];
		
		// Дополнительные поля.
		$arParams['FIELDS'] = (array) $arParams['FIELDS'];
		
        return $arParams;
    }
	
	
	/**
	 * Выполнение компонента.
	 */
	public function executeComponent()
    {
		if (!\Bitrix\Main\Loader::includeModule('wolk.core')) {
			ShowError('Модуль wolk.core не устанволен.');
			return;
		}

		if (!\Bitrix\Main\Loader::includeModule('iblock')) {
			ShowError('Модуль iblock не устанволен.');
			return;
		}
		
		// Пользователь.
		$this->arResult['USER'] = CUser::getByID($this->arParams['ID'])->Fetch();
		
		// Мероприятие.
		if (!empty($this->arParams['EVENT'])) {
			$this->arResult['EVENT'] = Wolk\Core\Helpers\IBlockElement::getByCode($this->arParams['EVENT']);
		}
		
		// Дополнительные поля.
		$this->arResult['FIELDS'] = $this->arParams['FIELDS'];
		
		// Подключение шаблона.
		// ob_clean();
		ob_start();
		
		$this->includeComponentTemplate();
		
		return ob_get_clean();
	}
	
}




