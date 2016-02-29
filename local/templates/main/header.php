<?
/**
 * @var CMain $APPLICATION
 */
use Bitrix\Main\Localization\Loc;
Loc::loadLanguageFile(__FILE__);
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <? $am = \Bitrix\Main\Page\Asset::getInstance(); ?>
    <? $am->addJs('/local/templates/.default/build/js/vendor.js');
    $am->addJs('/local/templates/.default/javascripts/application.js');
    $am->addCss('/local/templates/.default/build/css/vendor.css');
    $am->addCss('/local/templates/.default/fontsquirrel/stylesheet.css');
    $am->addCss('/local/templates/.default/build/css/main.css');
    $am->addCss('/local/templates/.default/build/css/jquery.formstyler.css');
    $am->addCss('/local/templates/.default/build/css/style.css');
    $am->addJs('/local/templates/.default/build/js/jquery.formstyler.js');
    $am->addJs("/local/templates/.default/build/js/jquery.arcticmodal-0.3.min.js");
    $am->addJs("/local/templates/.default/build/js/slick.js");
    $am->addJs('/local/templates/.default/build/js/jquery.inputmask.bundle.js');
    $am->addJs('/local/templates/.default/build/js/jquery.tooltipster.min.js');
    $am->addJs('/local/templates/.default/build/js/sticky-kit.min.js');
    $am->addJs("/local/templates/.default/build/js/script.js");
    $APPLICATION->ShowHead(); ?>
    <style>
        <?$APPLICATION->ShowViewContent('custom_color_styles')?>
    </style>
    <script>
        window.addEventListener("touchmove", function (event) {
            event.preventDefault();
        }, false);
        if (typeof window.devicePixelRatio != 'undefined' && window.devicePixelRatio > 2) {
            var meta = document.getElementById("viewport");
            meta.setAttribute('content', 'width=device-width, initial-scale=' + (2 / window.devicePixelRatio) + ', user-scalable=no');
        }
    </script>
	<script>
		function stickyCall() {
			$("[data-sticky_column]").stick_in_parent({
				parent: "[data-sticky_parent]"
			});
		}
		$(window).on('load', function(){
			stickyCall();
		});
	</script>
</head>
<body>
<? $APPLICATION->ShowPanel(); ?>
<div class="headersection">
    <div class="layout">
        <div class="headersection__button customizable"><?= Loc::getMessage('language') ?>
            <div class="headersection__languagedropdown">
                <?foreach($APPLICATION->availableLang as $key => $name):?>
                <a href="<?=$APPLICATION->GetCurPageParam('set_lang='.$key, ['set_lang'], false)?>" class="<?if($key == \Bitrix\Main\Context::getCurrent()->getLanguage()):?>active <?endif;?>headersection__languagedropdownbutton">
                    <?=$name?>
                </a>
                <?endforeach;?>
            </div>
        </div>
        <?if($USER->IsAuthorized()):?>
        <div class="headersection__button customizable">
            <a class="customizable" href="/personal/profile/">
                <?=Loc::getMessage("AUTH_PROFILE")?>
            </a>
        </div>
        <?endif;?>
        <div class="headersection__button customizable">
            <? $APPLICATION->IncludeComponent("bitrix:system.auth.form", "login", [
                "REGISTER_URL" => "/registration/",    // Страница регистрации
                "PROFILE_URL"  => "/personal/profile/",    // Страница профиля
            ],
                false
            ); ?>
        </div>
        <div class="headersection__logocontainer">
            <a href="<? $APPLICATION->ShowViewContent('EVENT_LINK') ?>" class="headersection__logo"
               style="background-image:url('<? $APPLICATION->ShowViewContent('EVENT_LOGO') ?>')"></a>
        </div>
    </div>
</div>
<div class="pagecontainer">
    <div class="layout" id="vue-container" data-sticky_parent>
        <?if(\Bitrix\Main\Context::getCurrent()->getServer()->get('HTTP_X_REQUESTED_WITH') == 'XMLHttpRequest') {
            $APPLICATION->RestartBuffer();?>
            <div class="modal placeOrder placeOrder__login" id="placeLogin">
            <div class="modalClose arcticmodal-close"></div>
            <div class="modalTitle"><?$APPLICATION->ShowTitle(false)?></div>
        <?}?>