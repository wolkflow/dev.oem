<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<? use Bitrix\Main\Localization\Loc; ?>
<? use Wolk\Core\Helpers\Text as TextHelper ?>
<? use Wolk\Oem\Basket; ?>

<div class="js-quantity-wrapper js-product-element itemCount" data-pid="<?= $product->getID() ?>">
    <div class="serviceItem__subtitle">
        <?= Loc::getMessage('QUANTITY') ?>
    </div>
    <div class="js-quantity-dec itemCount__button itemCount__down"></div>
    <div class="js-quantity-inc itemCount__button itemCount__up"></div>

    <? $value = (!empty($basketitem)) ? ($basketitem->getQuantity()) : ('0') ?>
    
    <input type="text" class="js-quantity itemCount__input styler" data-value="<?= $value ?>" value="<?= $value ?>" />
</div>
