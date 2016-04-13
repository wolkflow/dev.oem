<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>

<? $context  = \Bitrix\Main\Application::getInstance()->getContext(); ?>
<? $language = strtoupper($context->getLanguage()); ?>

<?= $arResult['ELEMENT']['PROPERTIES']['LANG_MANAGER_CONTACTS_'.$language]['~VALUE']['TEXT'] ?>