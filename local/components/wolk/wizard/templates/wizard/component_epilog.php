<?php

$asset = \Bitrix\Main\Page\Asset::getInstance();

// Свойства товаров.
$asset->addJs($this->GetTemplate()->getFolder().'/js/props.js');

// Типы товаров.
$asset->addJs($this->GetTemplate()->getFolder().'/js/quantity.js');



$asset->addCss('/assets/css/sketch.css');
$asset->addCss('/local/templates/.default/build/css/mdp.css');
$asset->addCss('/local/templates/.default/build/css/jquery-ui.css');
$asset->addCss('/local/templates/.default/build/css/pepper-ginder-custom.css');
$asset->addCss('/local/templates/.default/build/css/pickmeup.css');

$asset->addJs('/local/templates/.default/javascripts/jquery-ui.min.js');
$asset->addJs('/local/templates/.default/build/js/jquery.fileupload.js');
$asset->addJs('/local/templates/.default/build/js/jquery.iframe-transport.js');
$asset->addJs('/local/templates/.default/build/js/jquery.tooltipster.min.js');
$asset->addJs('/local/templates/.default/build/js/jquery.airStickyBlock.min.js');
$asset->addJs('/local/templates/.default/build/js/jquery.inputmask.bundle.js');
$asset->addJs('/local/templates/.default/build/js/jquery.pickmeup.min.js');
