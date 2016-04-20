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
		
		$site = \CSite::GetByID(SITE_DEFAULT)->Fetch();
		
		$this->arResult['SERVER_NAME'] = $site['SERVER_NAME'];
		$this->arResult['LANGUAGE'] = strtoupper(\Bitrix\Main\Application::getInstance()->getContext()->getLanguage());
		
		// Пользователь.
		$this->arResult['USER'] = CUser::getByID($this->arParams['ID'])->Fetch();
		
		// Мероприятие.
		if (!empty($this->arParams['EVENT'])) {
			$this->arResult['EVENT'] = Wolk\Core\Helpers\IBlockElement::getByCode(EVENTS_IBLOCK_ID, $this->arParams['EVENT']);
			$this->arResult['EVENT']['LOGO'] = CFile::ResizeImageGet($this->arResult['EVENT']['PROPERTIES']['LANG_LOGO_'.$this->arResult['LANGUAGE']]['VALUE'], ['width' => 168, 'height' => 68], BX_RESIZE_IMAGE_PROPORTIONAL_ALT)['src'];
		}
		
		// Дополнительные поля.
		$this->arResult['FIELDS'] = $this->arParams['FIELDS'];
		
		// Подключение шаблона.
		ob_start();
		
		$this->includeComponentTemplate();
		
		return ob_get_clean();
	}
	
}




