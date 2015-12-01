<?php
 
namespace Wolk\Core\Events;

IncludeModuleLangFile(__FILE__);


/**
 * Обработчик событий главного модуля.
 */
class Main
{
    /**
     * Добавление главного меню.
     */
    public function OnBuildGlobal_AddMainMenu()
    {
        $menu = array(
            'global_menu_wolk.core' => array(
                'menu_id' 		=> 'wolkcore',
                'icon' 			=> 'wolk.core',
                'page_icon' 	=> 'wolk.core',
                'index_icon' 	=> 'wolk.core',
                'text' 			=> GetMessage('WOLK_CORE_GLOBAL_MENU_TEXT'),
                'title' 		=> GetMessage('WOLK_CORE_GLOBAL_MENU_TITLE'),
                'url' 			=> 'wolk.core_index.php?lang='.LANGUAGE_ID,
                'sort' 			=> 1000,
                'items_id' 		=> 'global_menu_wolk_core',
                'help_section' 	=> 'settings',
                'items' 		=> array()
            )
        );
        
        return $menu;
    }
}