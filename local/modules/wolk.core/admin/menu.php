<?php

return;

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

$module = 'wolk.core';

if (IsModuleInstalled($module)) {

    if (!\Bitrix\Main\Loader::includeModule($module)) {
        return;
    }
	
	$aMenu []= array(
        'parent_menu' 	=> 'global_menu_wolk.core',
        'section' 		=> $module,
        'sort' 			=> '1000',
        'url' 			=> 'wolk_core_index.php',
        'more_url' 		=> array(),
        'title' 		=> 'Проверка системы',
        'text' 			=> 'Проверка системы',
        'icon' 			=> 'wolk_core_menu_icon_check_system',
        'page_icon' 	=> 'wolk_core_page_icon_check_system',
        'module_id' 	=> $module,
        'items_id' 		=> 'menu_wolk.core_settings_item',
        'dynamic' 		=> false,
		'items'			=> array(),
    );
	
	
	/*
     * Событие для других модулей.
     */
    $events = GetModuleEvents('wolk.core', 'OnAfterAdminMenuBuild');
    while ($arEvent = $events->Fetch()) {
        try {
            ExecuteModuleEventEx($arEvent, array(&$aMenu));
        } catch (Exception $e) {
            throw $e;
        }
    }

    return $aMenu;
}