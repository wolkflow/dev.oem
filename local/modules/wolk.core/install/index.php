<?php

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

 
// ID модуля.
$moduleID  = 'wolk.core';

// Путь к модулю.
$modulepath = $_SERVER['DOCUMENT_ROOT'].'/local/modules/'.$moduleID;

/*
 * AJAX-запрос.
 */
if (isset($_REQUEST['AJAX']) && $_REQUEST['id'] == $sModuleId) {
    ob_end_clean();
    include ($modulepath.'/install/ajax.php');
    exit();
}


/*
 * Языковые константы.
 */
global $MESS;
$strPath2Lang = str_replace("\\", "/", __FILE__);
$strPath2Lang = substr($strPath2Lang, 0, strlen($strPath2Lang) - 18);
@include(GetLangFileName($strPath2Lang.'/lang/', '/install/index.php'));
IncludeModuleLangFile($strPath2Lang.'/install/index.php');


/*
 * Описание класса модуля.
 */
class wolk_core extends CModule
{
    // Настройки модуля.
    var $MODULE_ID              = 'wolk.core'; // Без var не пускает в маркетплейс.
    
    public $MODULE_VERSION      = '';
    public $MODULE_VERSION_DATE = '';
    public $MODULE_NAME         = '';
    public $MODULE_DESCRIPTION  = '';
    
    public $MODULE_GROUP_RIGHTS = 'Y';
    public $PARTNER_NAME        = '';
    public $PARTNER_URI         = '';
    
	protected $MODULE_PATH		= '/local/modules/';
	
    
    // Шаги установки / удаления модуля.
	protected static $steps = array(
		'install' => array(
			'index',
			'finish',
		),
		'uninstall' => array(
			'index',
			'finish',
		),
	);
	
	// Текущий шаг.
	protected $step = null;
	
	// Настройки.
    protected $settings = array();
    
    
    /*
     * Массив всех регистрируемых событий.
     */
    protected $mevents = array(
		// Глобальное меню.
        array(
            'main',
            'OnBuildGlobalMenu',
            'wolk.core',
            '\Wolk\Core\Events\Main',
            'OnBuildGlobal_AddMainMenu',
			10000
        ),
		
		// Дополнительные свойства инфоблоков.
		array(
            'iblock',
            'OnIBlockPropertyBuildList',
            'wolk.core',
            'Wolk\Core\System\Exprops\IBlock\CheckBox',
            'GetUserTypeDescription',
			750
        ),
		array(
            'iblock',
            'OnIBlockPropertyBuildList',
            'wolk.core',
            'Wolk\Core\System\Exprops\IBlock\Currency',
            'GetUserTypeDescription',
			750
        ),
		array(
            'iblock',
            'OnIBlockPropertyBuildList',
            'wolk.core',
            'Wolk\Core\System\Exprops\IBlock\UserGroup',
            'GetUserTypeDescription',
			750
        ),
    );
    
    
    
    /**
     * Инициализация модуля.
     */
    public function wolk_core()
    {
        global $APPLICATION, $DOCUMENT_ROOT;
        
        $this->MODULE_NAME           = GetMessage('WOLK_CORE_MODULE_NAME');
        $this->MODULE_DESCRIPTION    = GetMessage('WOLK_CORE_MODULE_DESC');
        
        // Версия модуля из файла version.php
        $arModuleVersion = array();
        $path = str_replace("\\", "/", __FILE__);
        $path = substr($path, 0, strlen($path) - strlen('/index.php'));
        include($path.'/version.php');
        
        if (is_array($arModuleVersion) && array_key_exists("VERSION", $arModuleVersion)) {
            $this->MODULE_VERSION      = $arModuleVersion['VERSION'];
            $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
        }
		
        // Параметры модуля.
        $this->MODULE_ID    = 'wolk.core';
        $this->PARTNER_NAME = 'WolkFolw';
        $this->PARTNER_URI  = 'http://wolkflow.ru/';
        
        // Загрузка необходимых классов.
        include_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/linemedia.catalogs/lib/filehelper.php');
    }
	
    
    /**
     * Установка модуля.
     */
    public function DoInstall()
    {
        global $APPLICATION, $DOCUMENT_ROOT;
        
        // Добавим jquery и стили главного модуля.
        $APPLICATION->AddHeadScript('http://yandex.st/jquery/2.1.0/jquery.min.js');
        
        
        // Модуль уже установлен.
        if (IsModuleInstalled($this->MODULE_ID)) {
            return false;
        }
        
        // Сессия неправильная.
        if (!check_bitrix_sessid()) {
            return false;
        }
        
        // Шаг установщика.
        if (isset($_REQUEST['step'])) {
            $this->step = strval($_REQUEST['step']);
        }
        
        // Выбираем шаг.
		if (empty($this->step)) {
			$this->step = reset(self::$steps['install']);
		}
		
        switch ($this->step) {
			// Начало установки.
            case 'index':
				include ($DOCUMENT_ROOT . $this->MODULE_PATH . $this->MODULE_ID . '/install/steps/install/' . $this->step . '.php');
				// $APPLICATION->IncludeAdminFile(GetMessage('WOLK_CORE_MODULE_INSTALL_STEP_INDEX'), $DOCUMENT_ROOT . $this->MODULE_PATH . $this->MODULE_ID . '/install/steps/install/' . $this->step . '.php');
                break; 
            
			// Завершение установки.
            case 'finish':
                // include ($DOCUMENT_ROOT . $this->MODULE_PATH . $this->MODULE_ID . '/steps/install/' . $step . '-save.php');
                // $APPLICATION->IncludeAdminFile(GetMessage('WOLK_CORE_MODULE_INSTALL_STEP_FINISH'), $DOCUMENT_ROOT . $this->MODULE_PATH . $this->MODULE_ID . '/install/steps/install/' . $this->step . '.php');
                break;
                
            default:
                $APPLICATION->ThrowException('Incorrect install step.');
                break;
        }
        return;
    }

    
    /**
     * Предустановленные настройки модуля.
     */
    public function presetOption()
    {
        return true;
    }
    
    
    /**
     * Удаление модуля.
     */
    public function DoUninstall()
    {
        global $APPLICATION, $DOCUMENT_ROOT;
        
        // Сессия неправильная.
        if (!check_bitrix_sessid()) {
            return false;
        }
        
        // Шаг установщика.
        if (isset($_REQUEST['step'])) {
            $this->step = strval($_REQUEST['step']);
        }
        
        // Выбираем шаг.
		if (empty($this->step)) {
			$this->step = reset(self::$steps['install']);
		}
		
        switch ($this->step) {
			// Начало установки.
            case 'index':
				include ($DOCUMENT_ROOT . $this->MODULE_PATH . $this->MODULE_ID . '/install/steps/uninstall/' . $this->step . '.php');
                // $APPLICATION->IncludeAdminFile(GetMessage('WOLK_CORE_MODULE_UNINSTALL_STEP_INDEX'), $DOCUMENT_ROOT . $this->MODULE_PATH . $this->MODULE_ID . '/install/steps/uninstall/' . $this->step . '.php');
                break; 
            
			// Завершение установки.
            case 'finish':
                // include ($DOCUMENT_ROOT . $this->MODULE_PATH . $this->MODULE_ID . '/steps/uninstall/' . $step . '-save.php');
                // $APPLICATION->IncludeAdminFile(GetMessage('WOLK_CORE_MODULE_UNINSTALL_STEP_FINISH'), $DOCUMENT_ROOT . $this->MODULE_PATH . $this->MODULE_ID . '/install/steps/uninstall/' . $this->step . '.php');
                break;
                
            default:
                $APPLICATION->ThrowException('Incorrect install step.');
                break;
        }
        return true;
    }
    
    
    /**
     * Установка событий.
     *
     * @return bool
     */
    public function InstallEvents()
    {
        foreach ($this->mevents as $event) {
            RegisterModuleDependences($event[0], $event[1], $event[2], $event[3], $event[4]);
        }
        return true;
    }
    
    
    /**
     * Удаление событий.
     *
     * @return bool
     */
    public function UnInstallEvents()
    {
        foreach ($this->mevents as $event) {
            UnRegisterModuleDependences($event[0], $event[1], $event[2], $event[3], $event[4]);
        }
        return true;
    }
    
    
    /**
     * Установка агентов.
     *
     * @return bool
     */
    public function InstallAgents()
    {
        return true;
    }
    
    
    /**
     * Удаление агентов.
     *
     * @return bool
     */
    public function UnInstallAgents()
    {
        return true;
    }
    
    
    /**
     * Установка таблиц БД.
     *
     * @return bool
     */
    public function InstallDB()
    {
        global $DB, $DOCUMENT_ROOT;
        
		// Путь к установочным файлам SQL.
		$path = $DOCUMENT_ROOT . $this->MODULE_PATH . $this->MODULE_ID . '/install/db/mysql/install/';
		
		foreach (glob($path . '*.sql') as $file) {
			$errors = $DB->RunSQLBatch($path . $file.'.sql');
			if (!empty($errors)) {
				return false;
			}
		}
        return true;
    }
    
    
    /**
     * Удаление таблиц БД.
     *
     * @return bool
     */
    public function UnInstallDB()
    {
        global $DB, $DOCUMENT_ROOT;
		
		// Путь к установочным файлам SQL.
		$path = $DOCUMENT_ROOT . $this->MODULE_PATH . $this->MODULE_ID . '/install/db/mysql/uninstall/';
		
		foreach (glob($path . '*.sql') as $file) {
			$errors = $DB->RunSQLBatch($path . $file.'.sql');
			if (!empty($errors)) {
				return false;
			}
		}
        return true;
    }
    
    
    /**
     * Установка файлов.
     */
    public function InstallFiles()
    {
        // Копируем файлы административной части.
        CopyDirFiles(
            $_SERVER['DOCUMENT_ROOT'].$this->MODULE_PATH.$this->MODULE_ID.'/install/admin', 
            $_SERVER['DOCUMENT_ROOT'].'/bitrix/admin/', true
        );
        
        // Административные стили и иконки.
        CopyDirFiles(
            $_SERVER['DOCUMENT_ROOT'].$this->MODULE_PATH.$this->MODULE_ID.'/install/themes',
            $_SERVER['DOCUMENT_ROOT'].'/bitrix/themes/', true, true
        );
        
        // Административные скрипты.
        CopyDirFiles(
            $_SERVER['DOCUMENT_ROOT'].$this->MODULE_PATH.$this->MODULE_ID.'/install/js',
            $_SERVER['DOCUMENT_ROOT'].'/bitrix/js/', true, true
        );
        
        // Компоненты.
        CopyDirFiles(
            $_SERVER['DOCUMENT_ROOT'].$this->MODULE_PATH.$this->MODULE_ID.'/install/components',
            $_SERVER['DOCUMENT_ROOT'].'/bitrix/components/', true, true
        );
        
        
        // Создание папки для кеша картинок.
        mkdir($_SERVER['DOCUMENT_ROOT'].'/icache/');
        
        return true;
    }
    
    
    /**
     * Удаление файлов.
     */
    public function UnInstallFiles()
    {
        DeleteDirFiles(
            $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/'.$this->MODULE_ID.'/install/admin',
            $_SERVER['DOCUMENT_ROOT'].'/bitrix/admin'
        );
        
        return true;
    }
    
    
    /**
     * Добавление почтовых шаблонов.
     */
    public function InstallMessageTemplates()
    {
    	// Установка типов почтовых событий.
    	include 'mails/ru/types.php';
    
    	foreach ($arTypes as $arTypeLangs) {
    		foreach ($arTypeLangs as $arType) {
    			$type = new \CEventType();
    			$type->Add($arType);
    		}
    	}
    
    	// Установка почтовых шаблонов.
    	include 'mails/ru/templates.php';
    
    	$rsSites = \CSite::GetList($b = "sort", $o = "asc", array());
    	while ($arSite = $rsSites->Fetch()) {
    		foreach ($arTemplates as $arTemplate) {
    			$arTemplate['LID'] = $arSite['ID'];
    
    			$message = new \CEventMessage();
    			$message->Add($arTemplate);
    		}
    	}
    
    	return true;
    }
    
    
    /**
     * Удаление почтовых шаблонов.
     */
    public function UninstallMessageTemplates()
    {
    	// Удаление типов почтовых событий.
    	include 'messages/ru/types.php';
    	foreach ($arTypes as $arTypeCode => $arTypeLangs) {
    		\CEventType::Delete($arTypeCode);
    	}
    
    	// Удаление почтовых шаблонов.
    	$templates = CEventMessage::GetList($b = "id", $o = "asc", array('TYPE_ID' => implode(' | ', array_keys($arTypes))));
    	while ($template = $templates->Fetch()) {
    		\CEventMessage::Delete($template['ID']);
    	}
    	
    	return true;
    }
	
    
    /**
     * Добавление правил обработки адресов.
     */
    public function InstallRewrites()
    {
    	include 'rewrites/rewrites.php';
    	
    	foreach ($arRewrites as $arRewrite) {
    		\CUrlRewriter::Add($arRewrite);
    	}
    	return true;
    }
    
    
    /**
     * Удаление правил обработки адресов.
     */
    public function UninstallRewrites()
    {
    	include 'rewrites/rewrites.php';
    	
    	foreach ($arRewrites as $arRewrite) {
    		\CUrlRewriter::Delete($arRewrite);
    	}
    	return true;
    }
}

