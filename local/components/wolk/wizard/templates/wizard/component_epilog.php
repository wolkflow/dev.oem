<?php

$asset = \Bitrix\Main\Page\Asset::getInstance();

$asset->addCss('/assets/css/sketch.css');
$asset->addCss('/local/templates/.default/build/css/mdp.css');
$asset->addCss('/local/templates/.default/build/css/jquery-ui.css');
$asset->addCss('/local/templates/.default/build/css/pepper-ginder-custom.css');
$asset->addCss('/local/templates/.default/build/css/jquery-ui.multidatespicker.css');
//$asset->addCss('/local/templates/.default/build/css/pickmeup.css');

$asset->addJs('/local/templates/.default/javascripts/jquery-ui.min.js');
//$asset->addJs('/local/templates/.default/javascripts/jquery-ui.multidatespicker.js');
$asset->addJs('/local/templates/.default/build/js/jquery.fileupload.js');
$asset->addJs('/local/templates/.default/build/js/jquery.iframe-transport.js');
$asset->addJs('/local/templates/.default/build/js/jquery.tooltipster.min.js');
$asset->addJs('/local/templates/.default/build/js/jquery.airStickyBlock.min.js');
$asset->addJs('/local/templates/.default/build/js/jquery.inputmask.bundle.js');
$asset->addJs('/local/templates/.default/build/js/jquery-ui.multidatespicker.js');
//$asset->addJs('/local/templates/.default/build/js/jquery.pickmeup.min.js');


// Типы товаров.
$asset->addJs($this->GetTemplate()->getFolder().'/js/quantity.js');
$asset->addJs($this->GetTemplate()->getFolder().'/js/square.js');
$asset->addJs($this->GetTemplate()->getFolder().'/js/width-height.js');
$asset->addJs($this->GetTemplate()->getFolder().'/js/width-height-quantity.js');
$asset->addJs($this->GetTemplate()->getFolder().'/js/days.js');
// $asset->addJs($this->GetTemplate()->getFolder().'/js/hours.js');
$asset->addJs($this->GetTemplate()->getFolder().'/js/days-hours.js');
// $asset->addJs($this->GetTemplate()->getFolder().'/js/hours-quantity.js');
// $asset->addJs($this->GetTemplate()->getFolder().'/js/days-quantity.js');
$asset->addJs($this->GetTemplate()->getFolder().'/js/days-hours-quantity.js');
$asset->addJs($this->GetTemplate()->getFolder().'/js/symbols.js');

// Свойства товаров.
$asset->addJs($this->GetTemplate()->getFolder().'/js/props.js');



