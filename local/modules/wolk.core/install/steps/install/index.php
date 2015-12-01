<?php

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();


/*
 * Сохранённые в сессии данные предыдущих шагов установщика.
 */
$this->install_settings = (array) $_SESSION['wolk_core_module_install_settings'];


// Предустановка опций модуля.
if (!$this->presetOption()) {
    ShowError(GetMessage('WOLK_CORE_ERROR_INSTALL_OPTIONS'));
    exit();
}

// Установка событий.
if (!$this->InstallEvents()) {
    ShowError(GetMessage('WOLK_CORE_ERROR_INSTALL_EVENTS'));
    exit();
}

// Установка файлов.
if (!$this->InstallFiles()) {
    ShowError(GetMessage('WOLK_CORE_ERROR_INSTALL_FILES'));
    exit();
}


// Установка таблицы БД.
if (!$this->InstallDB()) {
    ShowError(GetMessage('WOLK_CORE_ERROR_INSTALL_DATABASE'));
    exit();
}

// Установка почтовых шаблонов.
if (!$this->InstallMessageTemplates()) {
	ShowError(GetMessage('WOLK_CORE_ERROR_INSTALL_MESSAGES'));
	exit();
}

// Установка правил обработки адресов.
if (!$this->InstallRewrites()) {
	ShowError(GetMessage('WOLK_CORE_ERROR_INSTALL_MESSAGES'));
	exit();
}


/*
 * Регистрация модуля.
 */
RegisterModule($this->MODULE_ID);


// Установка агентов.
if (!$this->InstallAgents()) {
    ShowError(GetMessage('WOLK_CORE_ERROR_INSTALL_AGENTS'));
    exit();
}

