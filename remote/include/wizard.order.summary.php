<?php

define('NO_KEEP_STATISTIC',  true);
define('PULL_AJAX_INIT',     true);
define('PUBLIC_AJAX_MODE',   true);
define('NO_AGENT_STATISTIC', true);
define('NO_AGENT_CHECK',     true);
define('DisableEventsCheck', true);

use Bitrix\Main\Localization\Loc;
use Wolk\Core\Helpers\Text as TextHelper;

require ($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/prolog_before.php');


$request = \Bitrix\Main\Context::getCurrent()->getRequest();

$eid  = (int)    $request->get('eid');
$code = (string) $request->get('code');
$type = (string) $request->get('type');
$lang = mb_strtoupper(\Bitrix\Main\Context::getCurrent()->getLanguage());


Loc::loadLanguageFile($_SERVER['DOCUMENT_ROOT'] . '/local/components/wolk/wizard/templates/wizard/template.php');


// Мероприятие.
$event = new Wolk\OEM\Event($eid);

// Контекст конструктора.
$context = new Wolk\OEM\Context($eid, $type, $lang);

// Корзина.
$basket = new Wolk\OEM\Basket($code);

// Установка контекста.
$basket->setContext($context);

// Общая цена корзины.
$price = $basket->getPrice($context);

// Валюта.
$currency = $event->getCurrencyContext($context);

// Цены.
$prices = Wolk\OEM\Order::getFullPriceInfo($price, $event->getSurcharge(), $event->hasVAT());


?>

<div class="ordertotalcontainer__total">
	<?= Loc::getMessage('PRICE_TOTAL') ?>: 
	<span>
		<?= FormatCurrency($prices['PRICE'], $currency) ?>
	</span>
</div>

<? if ($prices['SURCHARGE_PRICE'] > 0) { ?>
	<div class="ordertotalcontainer__surcharge">
		<?= Loc::getMessage('SURCHRGE') ?>: 
		<span>
			<?= FormatCurrency($prices['SURCHARGE_PRICE'], $currency) ?>
		</span>
	</div>
	<div class="ordertotalcontainer__total">
		<?= Loc::getMessage('PRICE_TOTAL_WITH_VAT') ?>: 
		<span>
			<?= FormatCurrency($prices['SUMMARY'], $currency) ?>
		</span>
	</div>
<? } ?>

<div class="ordertaxcontainer__total">
	<?= Loc::getMessage('VAT') ?>:
	<span>
		<?= FormatCurrency($prices['VAT_PRICE'], $currency) ?>
	</span>
</div>

<div class="ordertotalcontainer__surchargetotal" v-show="totalPrice">
	<? if ($prices['SURCHARGE_PRICE'] > 0) { ?>
		<div class="ordertotalcontainer__surchargetotaltitle">
			<?= Loc::getMessage('PRICE_TOTAL_WITH_SURCHARGE') ?>:
		</div>
	<? } else { ?>
		<div class="ordertotalcontainer__surchargetotaltitle">
			<?= Loc::getMessage('PRICE_TOTAL_WITH_VAT') ?>:
		</div>
	<? } ?>
	<div class="ordertotalcontainer__surchargetotalcount">
		<?= FormatCurrency($prices['SUMMARY'], $currency) ?>
	</div>
</div>
