<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<? use Bitrix\Main\Localization\Loc; ?>
<? use Wolk\Core\Helpers\Text as TextHelper ?>
<? use Wolk\Oem\Basket; ?>

<? $value = (!empty($basketitem)) ? ($basketitem->getQuantity()) : ('0') ?>

<div class="js-quantity-wrapper itemCount" data-pid="<?= $product->getID() ?>">
    <div class="serviceItem__subtitle">
        <?= Loc::getMessage('QUANTITY') ?>
    </div>
    <div class="js-quantity-dec itemCount__button itemCount__down customizable"></div>
    <div class="js-quantity-inc itemCount__button itemCount__up customizable"></div>
	
    <input type="text" class="js-quantity itemCount__input styler" data-value="<?= $value ?>" value="<?= $value ?>" />
</div>
