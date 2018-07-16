<?php


/** @var CMain $APPLICATION */
$APPLICATION->availableLang = [
    'ru' => 'Русский',
    'en' => 'English'
];



// Контекст.
$context = \Bitrix\Main\Context::getCurrent();


// Список возможных языков.
$APPLICATION->languages = array(
	'ru' => 'Русский',
	'en' => 'English',
);


// Смена языка.
if (!empty($_REQUEST['set_lang']) && array_key_exists($_REQUEST['set_lang'], $APPLICATION->languages)) {
	$language = strtolower(strval($_REQUEST['set_lang']));
	if (array_key_exists($language, $APPLICATION->languages)) {
		$context->setLanguage($language);
		setcookie('public_lang', $language, time() + 86400 * 365, '/');
		LocalRedirect($APPLICATION->getCurPageParam('', ['CODE', '?set_lang', '&set_lang', 'set_lang'], false));
	}
}

if ($language = (string) $_COOKIE['public_lang']) {
    if (array_key_exists($language, $APPLICATION->languages) && $language != $context->getLanguage()) {
        $context->setLanguage($language);
    }
}



/*
// Текущий выбранный язык.
$curlang = \Bitrix\Main\Context::getCurrent()->getLanguage();


if (isset($_GET['set_lang']) && array_key_exists($_GET['set_lang'], $APPLICATION->availableLang)) {
	$lang = strval($_GET['set_lang']);
	
    $APPLICATION->set_cookie('public_lang', $lang);
	\Bitrix\Main\Context::getCurrent()->setLanguage($lang);
	
    LocalRedirect($APPLICATION->GetCurPageParam('', ['CODE', '?set_lang', '&set_lang', 'set_lang'], false));
}


if ($cookieLang = $APPLICATION->get_cookie('public_lang')) {
    if (array_key_exists($cookieLang, $APPLICATION->availableLang) && $cookieLang != $curlang) {
        \Bitrix\Main\Context::getCurrent()->setLanguage($cookieLang);
    }
}
*/
