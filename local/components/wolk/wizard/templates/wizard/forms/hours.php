<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<? use Bitrix\Main\Localization\Loc; ?>
<? use Wolk\Core\Helpers\Text as TextHelper ?>
<? use Wolk\Oem\Basket; ?>

<div class="serviceItem__row">
    <div class="js-hours-wrapper itemCount" data-pid="<?= $product->getID() ?>">
        <input type="hidden" name="TIMES" class="js-product-days-hours-times js-field-value" value="<? (!empty($basketitem)) ? ($basketitem->getField('TIMES')) : ('') ?>" />
                
        <? // Установка времени // ?>
        <div class="setDatetime__right">
            <div class="itemCount">
                <div class="serviceItem__subtitle">
                    <?= Loc::getMessage('TIMES') ?>
                </div>
                <div class="setTime">
                    <select name="timemin" class="styler js-field-value js-hours-times js-hours-time-min">
                        <? for ($time = 8; $time <= 20; $time++) { ?>
                            <? $hour = str_pad($time, 2, '0', STR_PAD_LEFT).':00' ?>
                            <option value="<?= $hour ?>" <?= (!empty($basketitem) && $hour == $basketitem->getField('timemin')) ? ('selected') : ('') ?>>
                                <?= $hour ?>
                            </option>
                        <? } ?>
                    </select>
                    <span class="setTime__divider"></span>
                    <select name="timemax" class="styler js-field-value js-hours-times js-hours-time-max">
                        <? for ($time = 8; $time <= 20; $time++) { ?>
                            <? $hour = str_pad($time, 2, '0', STR_PAD_LEFT).':00' ?>
                            <option value="<?= $hour ?>" <?= (!empty($basketitem) && $hour == $basketitem->getField('timemax')) ? ('selected') : ('') ?>>
                                <?= $hour ?>
                            </option>
                        <? } ?>
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>