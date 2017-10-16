<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<? use Bitrix\Main\Localization\Loc; ?>
<? use Wolk\Core\Helpers\Text as TextHelper ?>
<? use Wolk\Oem\Basket; ?>

<? $value = (!empty($basketitem)) ? ($basketitem->getQuantity()) : ('0') ?>

<div class="js-square-wrapper js-product-element itemCount lamCount" data-pid="<?= $product->getID() ?>">
    <div class="serviceItem__subtitle">
        <?= Loc::getMessage('SQUARE') ?> (<?= Loc::getMessage('MEASURE_M2') ?>)
    </div>
    <input type="text" class="js-square itemCount__input styler" value="<?= $value ?>" />
</div>
