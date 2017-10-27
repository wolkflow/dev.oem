<?php


/** @var CMain $APPLICATION */
$APPLICATION->availableLang = [
    'ru' => 'Русский',
    'en' => 'English'
];

// Текущий выбранный язык.
$curlang = \Bitrix\Main\Context::getCurrent()->getLanguage();

if (isset($_GET['set_lang']) && array_key_exists($_GET['set_lang'], $APPLICATION->availableLang)) {
    $APPLICATION->set_cookie('public_lang', strval($_GET['set_lang']));
    LocalRedirect($APPLICATION->GetCurPageParam('', ['set_lang'], false));
}

if ($cookieLang = $APPLICATION->get_cookie('public_lang')) {
    if (array_key_exists($cookieLang, $APPLICATION->availableLang) && $cookieLang != $curlang) {
        \Bitrix\Main\Context::getCurrent()->setLanguage($cookieLang);
    }
}