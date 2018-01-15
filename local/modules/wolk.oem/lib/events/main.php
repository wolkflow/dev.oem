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
     * Построение главного меню.
     */
    public function OnBuildGlobal_AddMainMenu(&$globals, &$modules)
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
                'sort'         => 50,
                'items_id'     => 'global_menu_wolk_oem',
                'help_section' => 'settings',
                'items'        => []
            ]
        ];
        
        global $USER;
        
        if (!$USER->IsAdmin()) {		
			$exclude = [
				'global_menu_settings'    => '*', 
				'global_menu_store'	      => '*', 
				'global_menu_marketing'   => '*',
				'global_menu_services'    => '*',
				'global_menu_statistics'  => '*',
				'global_menu_marketplace' => '*',
				'global_menu_wolk.core'   => '*',
				'global_menu_content'     => [
					//'menu_iblock_/events' => [
					//	'menu_iblock_/events/'.STANDS_IBLOCK_ID, 
					//	'menu_iblock_/events/'.STANDS_OFFERS_IBLOCK_ID,
					//],
					//'menu_iblock_/equipment' => '*',
					'menu_iblock' => '*',
				]
			];
			
			if (in_array(GROUP_PARTNERS_ID, $USER->getUserGroup())) {
				// ...
			}
			
			foreach ($globals as $g => $global) {
                if (array_key_exists($global['items_id'], $exclude) && $exclude[$global['items_id']] == '*') {
                    unset($globals[$g]);
                }
            }
			
            foreach ($modules as $m => $module) {
				if (array_key_exists($module['parent_menu'], $exclude)) {
					if ($exclude[$module['parent_menu']] == '*') {
						unset($modules[$m]);
					} else {
						if (array_key_exists($module['items_id'], $exclude[$module['parent_menu']])) {
							if ($exclude[$module['parent_menu']][$module['items_id']] == '*') {
								unset($modules[$m]);
							} else {
								foreach ($module['items'] as $s => $submodule) {
									if (in_array($submodule['items_id'], $exclude[$module['parent_menu']][$module['items_id']])) {
										unset($modules[$m]['items'][$s]);
									}
								}
							}
						}
					}
				}
            }
        }
		
        return $menu;
    }

	
    public function onBeforeUserUpdateHandler(&$fields)
    {
        global $USER;
		
        if (!$USER->IsAdmin()) {
			if (isset($fields['EMAIL'])) {
				$fields['LOGIN'] = $fields['EMAIL'];
			}
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
