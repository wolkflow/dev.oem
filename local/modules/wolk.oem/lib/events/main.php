<?php
 
namespace Wolk\OEM\Events;

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
        $menu = array(
            'global_menu_wolk.oem' => array(
                'menu_id' => 'wolkoem',
                'icon' => 'wolk.oem',
                'page_icon' => 'wolk.oem',
                'index_icon' => 'wolk.oem',
                'text' => GetMessage('WOLK_OEM_GLOBAL_MENU_TEXT'),
                'title' => GetMessage('WOLK_OEM_GLOBAL_MENU_TITLE'),
                'url' => 'wolk.core_index.php?lang=' . LANGUAGE_ID,
                'sort' => 1100,
                'items_id' => 'global_menu_wolk_oem',
                'help_section' => 'settings',
                'items' => array()
            )
        );

        return $menu;
    }

    public function onBeforeUserUpdateHandler(&$fields)
    {
        global $USER;
        if (!$USER->IsAdmin()) {
            $fields['LOGIN'] = $fields['EMAIL'];
        }
    }

    public function onBeforeUserRegisterHandler(&$fields)
    {
        $fields['EMAIL'] = $fields['LOGIN'];
    }
}