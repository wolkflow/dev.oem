<?php

namespace Wolk\OEM\Events;

use Bitrix\Main\Web\Json;
use Bitrix\Main\Localization\Loc;

IncludeModuleLangFile(__FILE__);


/**
 * ���������� ������� �������� ������.
 */
class Main
{
    /**
     * ���������� �������� ����.
     */
    public function OnBuildGlobal_AddMainMenu()
    {
        $menu = [
            'global_menu_wolk.oem' => [
                'menu_id'      => 'wolkoem',
                'icon'         => 'wolk.oem',
                'page_icon'    => 'wolk.oem',
                'index_icon'   => 'wolk.oem',
                'text'         => GetMessage('WOLK_OEM_GLOBAL_MENU_TEXT'),
                'title'        => GetMessage('WOLK_OEM_GLOBAL_MENU_TITLE'),
                'url'          => 'wolk.core_index.php?lang=' . LANGUAGE_ID,
                'sort'         => 1100,
                'items_id'     => 'global_menu_wolk_oem',
                'help_section' => 'settings',
                'items'        => []
            ]
        ];

        return $menu;
    }

	
    public function onBeforeUserUpdateHandler(&$fields)
    {
        global $USER;
        if (!$USER->IsAdmin()) {
            $fields['LOGIN'] = $fields['EMAIL'];
        }
    }
	
	
	public function OnAfterUserRegister($fields)
	{
		global $APPLICATION;
		
		$html = $APPLICATION->IncludeComponent(
			'wolk:mail.user',
			'confirmation',
			['ID' => $fields['USER_ID'], 'EVENT' => $_SESSION['REGEVENT'], 'FIELDS' => $fields]
		);
		
		// Отправка сообщения о подтвеерждении регистрации.
		$event = new \CEvent();
		$event->Send('CONFIRMATION', SITE_DEFAULT, ['EMAIL' => $fields['EMAIL'], 'HTML' => $html, 'THEME' => Loc::getMessage('MESSAGE_THEME_CONFIRMATION')]);
	}
	

    public function onBeforeUserRegisterHandler(&$fields)
    {
		global $APPLICATION;
		
        if (isset($_REQUEST['userData']) && $data = Json::decode($_REQUEST['userData'])) {
            if ($data['email_confirm'] != $fields['LOGIN']) {
                $APPLICATION->ThrowException('Email confirmation does not match');
                return false;
            }
        } elseif (isset($_REQUEST['REGISTER']['EMAIL_CONFIRM'])) {
            if ($_REQUEST['REGISTER']['EMAIL_CONFIRM'] != $fields['LOGIN']) {
                $APPLICATION->ThrowException('Email confirmation does not match');
                return false;
            }
            $fields['EMAIL'] = $fields['LOGIN'];
        }
    }
}
