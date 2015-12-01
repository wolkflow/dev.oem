<?php

use \Bitrix\Main\Localization\Loc;

require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin_before.php');


Loc::loadMessages(__FILE__);
$am = \Bitrix\Main\Page\Asset::getInstance();

// jQuery
$am->addJs('https://yastatic.net/jquery/2.1.3/jquery.min.js');
$am->addJs('http://cdnjs.cloudflare.com/ajax/libs/vue/1.0.8/vue.min.js');
$am->addJs('js/script.js');

// Bootstrap.
$am->addCss('https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css');
$am->addJs('https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js');

$APPLICATION->SetTitle('Новое мероприятие');

require ($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin_after.php');
?>
 
...

<? require ($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/epilog_admin.php') ?>