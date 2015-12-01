<?
/**
 * @var CMain $APPLICATION
 */
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<? CJsCore::Init('jquery');
	$am = \Bitrix\Main\Page\Asset::getInstance();
    $am->addJs('/local/templates/.default/build/vendor.js');
	$am->addJs('/local/templates/.default/javascripts/application.js');
	$am->addCss('/local/templates/.default/build/vendor.css');
	$am->addCss('/local/templates/.default/fontsquirrel/stylesheet.css');
	$am->addCss('/local/templates/.default/build/main.css');
	$APPLICATION->SHowHead(); ?>
</head>
<body>
<? $APPLICATION->ShowPanel(); ?>
<div class="headersection">
	<div class="layout">
		<div class="headersection__button">language
			<div class="headersection__languagedropdown"><a href=""
			                                                class="active headersection__languagedropdownbutton">English</a><a
					href="" class="headersection__languagedropdownbutton">русский</a></div>
		</div>
		<div class="headersection__button">
       <?$APPLICATION->IncludeComponent("bitrix:system.auth.form", "login", Array(
	"REGISTER_URL" => "/registration/",	// Страница регистрации
		"PROFILE_URL" => "/personal/profile/",	// Страница профиля
	),
	false
     );?>
		</div>
		<div class="headersection__logocontainer"><a href="/" class="headersection__logo"></a></div>
	</div>
</div>
<div class="pagecontainer">
	<div class="layout">