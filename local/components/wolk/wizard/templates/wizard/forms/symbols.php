<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<? use Bitrix\Main\Localization\Loc; ?>
<? use Wolk\Core\Helpers\Text as TextHelper ?>
<? use Wolk\Oem\Basket; ?>

<div class="js-symbols-wrapper js-product-element itemCount" data-pid="<?= $product->getID() ?>" data-limit="<?= $arResult['EVENT']->getPayLimitSymbols() ?>">
    <div class="serviceItem__subtitle">
        <?= Loc::getMessage('TEXT') ?>
		<input type="hidden" class="js-product-element" value="<?= $product->getID() ?>" />
    </div>
</div>
