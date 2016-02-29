<?php
#Work with lang
/** @var CMain $APPLICATION */
$APPLICATION->availableLang = [
    'ru' => 'Русский',
    'en' => 'English'
];
$curLang = \Bitrix\Main\Context::getCurrent()->getLanguage();
if (isset($_GET['set_lang']) && array_key_exists($_GET['set_lang'], $APPLICATION->availableLang)) {
    $newLang = $_GET['set_lang'];
    $APPLICATION->set_cookie('public_lang', $newLang);
    LocalRedirect($APPLICATION->GetCurPageParam("", ['set_lang'], false));
}

if ($cookieLang = $APPLICATION->get_cookie('public_lang')) {
    if (array_key_exists($cookieLang, $APPLICATION->availableLang) && $cookieLang != $curLang) {
        \Bitrix\Main\Context::getCurrent()->setLanguage($cookieLang);
    }
}