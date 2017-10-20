<?php

use Bitrix\Main\Localization\Loc;

Loc::loadMessages(__FILE__);

$module = 'wolk.oem';

if (IsModuleInstalled($module)) {

    if (!\Bitrix\Main\Loader::includeModule($module)) {
        return;
    }
	/*
	$aMenu []= array(
        'parent_menu' 	=> 'global_menu_wolk.oem',
        'section' 		=> $module,
        'sort' 			=> '1000',
        'url' 			=> 'wolk_oem_event_create.php',
        'more_url' 		=> array(),
        'title' 		=> 'Создание нового мероприятия',
        'text' 			=> 'Создать мероприятие',
        'icon' 			=> 'wolk_oem_menu_icon_check_system',
        'page_icon' 	=> 'wolk_oem_page_icon_check_system',
        'module_id' 	=> $module,
        'items_id' 		=> 'menu_wolk.oem_settings_item',
        'dynamic' 		=> false,
		'items'			=> array(),
    );
	*/
    
    $aMenu []= array(
        'parent_menu' 	=> 'global_menu_wolk.oem',
        'section' 		=> $module,
        'sort' 			=> '1000',
        'url' 			=> 'wolk_oem_order_form.php',
        'more_url' 		=> array('wolk_oem_order_form.php'),
        'title' 		=> 'Форма заказа',
        'text' 			=> 'Форма заказа',
        'icon' 			=> 'wolk_oem_menu_icon_check_system',
        'page_icon' 	=> 'wolk_oem_page_icon_check_system',
        'module_id' 	=> $module,
        'items_id' 		=> 'menu_wolk.oem_settings_item',
        'dynamic' 		=> false,
		'items'			=> array(),
    );
    
	$aMenu []= array(
        'parent_menu' 	=> 'global_menu_wolk.oem',
        'section' 		=> $module,
        'sort' 			=> '1100',
        'url' 			=> 'wolk_oem_order_list.php',
        'more_url' 		=> array('wolk_oem_order_index.php'),
        'title' 		=> 'Список заказов',
        'text' 			=> 'Список заказов',
        'icon' 			=> 'wolk_oem_menu_icon_check_system',
        'page_icon' 	=> 'wolk_oem_page_icon_check_system',
        'module_id' 	=> $module,
        'items_id' 		=> 'menu_wolk.oem_settings_item',
        'dynamic' 		=> false,
		'items'			=> array(),
    );
	
	$aMenu []= array(
        'parent_menu' 	=> 'global_menu_wolk.oem',
        'section' 		=> $module,
        'sort' 			=> '1100',
        'url' 			=> 'wolk_oem_event_list.php',
        'more_url' 		=> array('wolk_oem_event_index.php'),
        'title' 		=> 'Список выставок',
        'text' 			=> 'Список выставок',
        'icon' 			=> 'wolk_oem_menu_icon_check_system',
        'page_icon' 	=> 'wolk_oem_page_icon_check_system',
        'module_id' 	=> $module,
        'items_id' 		=> 'menu_wolk.oem_settings_item',
        'dynamic' 		=> false,
		'items'			=> array(),
    );
	
	
	/*
     * Событие для других модулей.
     */
    $events = GetModuleEvents('wolk.oem', 'OnAfterAdminMenuBuild');
    while ($arEvent = $events->Fetch()) {
        try {
            ExecuteModuleEventEx($arEvent, array(&$aMenu));
        } catch (Exception $e) {
            throw $e;
        }
    }

    return $aMenu;
}