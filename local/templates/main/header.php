<!DOCTYPE html>
<html>
<?
/**
 * @var CMain $APPLICATION
 */
use Bitrix\Main\Localization\Loc;
Loc::loadLanguageFile(__FILE__);
?>
<head>
    <meta charset="utf-8" />
	<link rel="shortcut icon" href="/favicon.ico" />
	
    <? $am = \Bitrix\Main\Page\Asset::getInstance(); ?>
    <?  // Подключение скриптов и стилей.
        $am->addJs('/local/templates/.default/build/js/vendor.js');
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
        $APPLICATION->ShowHead(); 
    ?>
    <style>
        <? $APPLICATION->ShowViewContent('custom_color_styles') ?>
    </style>
	<script type="text/javascript" src="/local/templates/.default/javascripts/designer.js"></script>
    <script>
        if (typeof window.devicePixelRatio != 'undefined' && window.devicePixelRatio > 2) {
            var meta = document.getElementById("viewport");
            meta.setAttribute('content', 'width=device-width, initial-scale=' + (2 / window.devicePixelRatio));
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
	
	<?  // Янекдс.Метрика.
		$APPLICATION->IncludeComponent('bitrix:main.include', '', array(
			'AREA_FILE_SHOW' => 'file',
			'PATH' => SITE_TEMPLATE_PATH.'/include/counters/yandex-metrica.php',
		));
	?>
	
	<?  // Google.Analytics.
		$APPLICATION->IncludeComponent('bitrix:main.include', '', array(
			'AREA_FILE_SHOW' => 'file',
			'PATH' => SITE_TEMPLATE_PATH.'/include/counters/google-analitycs.php',
		));
	?>
</head>
<body>
<? $APPLICATION->ShowPanel(); ?>
<div class="headersection">
    <div class="layout">
        <div class="headersection__button customizable"><?= Loc::getMessage('language') ?>
            <div class="headersection__languagedropdown">
                <? $langs = $APPLICATION->availableLang; ?>
                
                <? if (strpos($APPLICATION->GetCurPage(false), '/events/') !== false) { ?>
                    <?  // Получение кода элемента.
                        $engine = new CComponentEngine();
                    
                        $arVariables = array();
                        $arUrlTemplates = CComponentEngine::MakeComponentUrlTemplates(array(), array('element' => '#ELEMENT_CODE#/'));

                        $componentPage = $engine->guessComponentPath(
                            '/events/',
                            $arUrlTemplates,
                            $arVariables
                        );
                        
                        $langs = [];
                        
                        CModule::IncludeModule('iblock');
                        $event = CIBlockElement::GetList([], ['IBLOCK_ID' => EVENTS_IBLOCK_ID, 'CODE' => strval($arVariables['ELEMENT_CODE'])])->GetNextElement();
						if ($event) {
							$props = $event->getProperties();
							foreach ($props as $code => $prop) { 
								if (strpos($code, 'LANG_ACTIVE_') !== false) {
									if ($prop['VALUE'] == 'Y') {
										$key = strtolower(str_replace('LANG_ACTIVE_', '', $code));
										$langs[$key] = $APPLICATION->availableLang[$key];
									}
								}
							}
						}
                    ?>
                <? } ?>
                
                <? foreach ($langs as $key => $name) { ?>
                    <a
                        href="<?= $APPLICATION->GetCurPageParam('set_lang='.$key, ['set_lang'], false) ?>"
                        class="<? if ($key == \Bitrix\Main\Context::getCurrent()->getLanguage()) { ?>active <? } ?>headersection__languagedropdownbutton customizable"
                    >
                        <?= $name ?>
                    </a>
                <? } ?>
            </div>
        </div>
        <? if ($USER->IsAuthorized()) { ?>
			<div class="headersection__button customizable">
				<a class="customizable" href="/personal/profile/">
					<?= Loc::getMessage("AUTH_PROFILE") ?>
				</a>
			</div>
        <? } ?>
        <div class="headersection__button customizable">
            <? $APPLICATION->IncludeComponent("bitrix:system.auth.form", "login", [
                "REGISTER_URL" => "/registration/",    // Страница регистрации
                "PROFILE_URL"  => "/personal/profile/",    // Страница профиля
            ],
                false
            ); ?>
        </div>
        
        <div class="headersection__logocontainer">
            <a 
                href="<? $APPLICATION->ShowViewContent('EVENT_LINK') ?>" 
                class="headersection__logo" 
                style="background-image:url('<? $APPLICATION->ShowViewContent('EVENT_LOGO') ?>')"
            ></a>
        </div>
        <? /*
        <div class="headersection__logocontainer">
            <a href="<? $APPLICATION->ShowViewContent('EVENT_LINK') ?>" class="headersection__logo"></a>
        </div>
        */ ?>
    </div>
</div>
<div class="pagecontainer">
    <div class="layout" id="vue-container" data-sticky_parent>
        <? if (\Bitrix\Main\Context::getCurrent()->getServer()->get('HTTP_X_REQUESTED_WITH') == 'XMLHttpRequest') { ?>
            <? $APPLICATION->RestartBuffer() ?>
            <div class="modal placeOrder placeOrder__login" id="placeLogin">
            <div class="modalClose arcticmodal-close"></div>
            <div class="modalTitle"><? $APPLICATION->ShowTitle(false) ?></div>
        <? } ?>
		