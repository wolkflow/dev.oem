<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<? use Bitrix\Main\Localization\Loc; ?>
<? use Wolk\Core\Helpers\Text as TextHelper ?>
<? use Wolk\Oem\Basket; ?>

<div class="serviceItem__row">
    <div class="js-days-hours-wrapper itemCount" data-pid="<?= $product->getID() ?>">
        <input type="hidden" name="dates" class="js-product-days-hours-dates" value="" />
        <input type="hidden" name="times" class="js-product-days-hours-times" value="" />
        
        <? // Установка даты // ?>
        <div class="serviceItem__left">
            <div class="setDateBlock">
                <div class="serviceItem__subtitle">
                    <?= Loc::getMessage('DATES') ?>
                </div>
                <input name="dates" class="setDate js-field-value js-days-hours-datepicker" value="<?= (!empty($basketitem)) ? ($basketitem->getField('dates')) : ('') ?>" />
            </div>
        </div>
        
        <? // Установка времени // ?>
        <div class="serviceItem__right">
            <div class="itemCount">
                <div class="serviceItem__subtitle">
                    <?= Loc::getMessage('TIMES') ?>
                </div>
                <div class="setTime">
                    <select name="timemin" class="styler js-field-value js-days-hours-times js-days-hours-time-min">
                        <? for ($time = 8; $time <= 20; $time++) { ?>
                            <? $hour = str_pad($time, 2, '0', STR_PAD_LEFT).':00' ?>
                            <option value="<?= $hour ?>" <?= (!empty($basketitem) && $hour == $basketitem->getField('timemin')) ? ('selected') : ('') ?>>
                                <?= $hour ?>
                            </option>
                        <? } ?>
                    </select>
                    <span class="setTime__divider"></span>
                    <select name="timemax" class="styler js-field-value js-days-hours-times js-days-hours-time-max">
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