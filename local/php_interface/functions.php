<?php


// Функция подключения языкового файла для включаемой области.
if (!function_exists('IncludeAreaLangFile')) {
    function IncludeAreaLangFile($file, $lang = null)
    {
		$lang = (string) $lang;
		if (empty($lang)) {
			$lang = \Bitrix\Main\Context::getCurrent()->getLanguage();
		}
        $basepath = $_SERVER['DOCUMENT_ROOT'] . SITE_TEMPLATE_PATH;
        $langfile = $basepath  . '/lang/' . $lang . str_replace($basepath, '' , $file);
        
        __IncludeLang($langfile);
    }
}

// Функция подключения языкового файла для шаблона с одним сайтом.
if (!function_exists('IncludeComponentTemplateLangFile')) {
    function IncludeComponentTemplateLangFile($file, $template, $lang = null)
    {
		$lang = (string) $lang;
		if (empty($lang)) {
			$lang = \Bitrix\Main\Context::getCurrent()->getLanguage();
		}
        $basepath = $_SERVER['DOCUMENT_ROOT'] . $template;
        $filename = str_replace($basepath, '', $file);
        $langfile = $basepath  . '/lang/' . $lang . $filename;
        
        __IncludeLang($langfile);
    }
}

// Функция подключения языкового файла для страницы.
if (!function_exists('IncludeFileLangFile')) {
    function IncludeFileLangFile($file, $lang = null)
    {
		$lang = (string) $lang;
		if (empty($lang)) {
			$lang = \Bitrix\Main\Context::getCurrent()->getLanguage();
		}
        $basepath = $_SERVER['DOCUMENT_ROOT'];
        $langfile = $basepath  . '/local/lang/' . $lang . str_replace($basepath, '' , $file);
        
        __IncludeLang($langfile);
    }
}
