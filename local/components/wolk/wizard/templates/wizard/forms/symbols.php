<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<? use Bitrix\Main\Localization\Loc; ?>
<? use Wolk\Core\Helpers\Text as TextHelper ?>
<? use Wolk\Oem\Basket; ?>

<div class="js-quantity-wrapper js-product-element itemCount" data-pid="<?= $product->getID() ?>">
    <div class="serviceItem__subtitle">
        <?= Loc::getMessage('TEXT') ?>
    </div>
</div>