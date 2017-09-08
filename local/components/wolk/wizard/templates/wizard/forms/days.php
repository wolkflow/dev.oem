<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<? use Bitrix\Main\Localization\Loc; ?>
<? use Wolk\Core\Helpers\Text as TextHelper ?>
<? use Wolk\Oem\Basket; ?>

<div class="serviceItem__row">
    <div class="js-days-wrapper itemCount" data-pid="<?= $product->getID() ?>">
        <input type="hidden" name="dates" class="js-product-days-dates" value="" />
        <input type="hidden" name="times" class="js-product-days-times" value="" />
        
        <? // Установка даты // ?>
        <div class="serviceItem__left">
            <div class="setDateBlock">
                <div class="serviceItem__subtitle">
                    <?= Loc::getMessage('DATES') ?>
                </div>
                <input name="dates" class="setDate js-field-value js-days-datepicker" value="<?= (!empty($basketitem)) ? ($basketitem->getField('dates')) : ('') ?>" />
            </div>
        </div>
    </div>
</div>