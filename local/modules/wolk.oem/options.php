<?php

// Необходимые классы.
use Bitrix\Main\Localization\Loc, Bitrix\Main\Loader;


// Проверка доступности модуля.
if (!$USER->IsAdmin()) {
	return;
}


// ID модуля.
$moduleID = 'wolk.oem';


// Путь к модулю.
$modulepath = $_SERVER['DOCUMENT_ROOT'].'/local/modules/'.$moduleID;
 
 
// Подключение модуля.
Bitrix\Main\Loader::includeModule($moduleID);


// Языковые константы.
Loc::loadMessages(__FILE__);



/*
 * СОхранение настроек модуля.
 */
if (!empty($_POST) && $_POST['UPDATE'] == 'Y') {
    
	// Событие "Сохранение настроек".
	$event = new \Bitrix\Main\Event($moduleID, 'OnBeforeOptionsSave', array('SELF' => $this, 'VALUES' => &$vals));
	$event->send();
	
    
    /*
     * Сохранение значений опций модуля.
     */
	foreach (glob('./options/save/*.php') as $file) {
		include ($modulepath.'/options/save/'.$file);
	}
    
	
    // Событие "Сохранение настроек".
    $event = new \Bitrix\Main\Event($moduleID, 'OnAfterOptionsSave', array('SELF' => $this, 'VALUES' => &$vals));
	$event->send();
}


/*
 * Разделы страницы настроек.
 */
$tabs = array(
    array(
        'DIV'   => 'api',
        'TAB'   => Loc::getMessage('DWAI_CORE_OPTIONS_TAB_API_TEXT'),
        'ICON'  => 'api',
        'TITLE' => Loc::getMessage('DWAI_CORE_OPTIONS_TAB_API_TITLE')
    )
);



// Событие "Добавление раздела настроек".
$event = new \Bitrix\Main\Event($moduleID, 'OnOptionsTabsInsert', array('SELF' => $this, 'VALUES' => &$tabs));
$event->send();


/*
 * Инициализируем разделы.
 */
$tabcontrol = new CAdmintabControl('tabControl', $tabs);
$tabcontrol->Begin();

?>
<form method="POST" enctype="multipart/form-data" action="<?= $APPLICATION->GetCurPage() ?>?mid=<?= htmlspecialchars($moduleID) ?>&lang=<?= LANG ?>&mid_menu=1">
	<?= bitrix_sessid_post() ?>
	<input type="hidden" name="UPDATE" value="Y" />
	
	<?
		// Настройки модуля.
		foreach (glob('./options/*.php') as $file) {
			$tabcontrol->BeginNextTab();
			include ($modulepath.'/options/'.$file);
			$tabcontrol->EndTab();
		}
		
		// Событие "Добавление раздела настроек".
		$event = new \Bitrix\Main\Event($moduleID, 'OnOptionsTabsShow', array($tabcontrol));
		$event->send();
		
		$tabcontrol->Buttons();
	?>
    
	<input type="submit" name="save"  value="<?= GetMessage('DWAI_CORE_OPTIONS_BUTTON_SAVE') ?>" />
    <input type="reset"  name="reset" value="<?= GetMessage('DWAI_CORE_OPTIONS_BUTTON_RESET') ?>" />
    
    <? $tabcontrol->End(); ?>
</form>
