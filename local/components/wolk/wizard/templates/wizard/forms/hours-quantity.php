<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<? use Bitrix\Main\Localization\Loc; ?>
<? use Wolk\Core\Helpers\Text as TextHelper ?>
<? use Wolk\Oem\Basket; ?>

<? $quanty = intval((!empty($basketitem)) ? ($basketitem->getField('QUANTITY')) : ('')) ?>

<div class="serviceItem__row">
    <div class="js-hours-quantity-wrapper" data-pid="<?= $product->getID() ?>">
        <input type="hidden" name="TIMES"    class="js-product-hours-quantity-times js-field-value" value="<? (!empty($basketitem)) ? ($basketitem->getField('TIMES')) : ('') ?>" />
        <input type="hidden" name="QUANTITY" class="js-product-hours-quantity-quantity js-field-value" value="<? (!empty($basketitem)) ? ($basketitem->getField('QUANTITY')) : ('') ?>" />

	    <? // Установка времени // ?>
	    <div class="setDatetime__right">
		    <div class="itemCount">
			    <div class="serviceItem__subtitle">
                    <?= Loc::getMessage('TIMES') ?>
			    </div>
			    <div class="setTime">
				    <select name="timemin" class="styler js-field-value js-hours-quantity-times js-hours-quantity-time-min">
                        <? for ($time = 8; $time <= 20; $time++) { ?>
                            <? $hour = str_pad($time, 2, '0', STR_PAD_LEFT).':00' ?>
						    <option value="<?= $hour ?>" <?= (!empty($basketitem) && $hour == $basketitem->getField('timemin')) ? ('selected') : ('') ?>>
                                <?= $hour ?>
						    </option>
                        <? } ?>
				    </select>
				    <span class="setTime__divider"></span>
				    <select name="timemax" class="styler js-field-value js-hours-quantity-times js-hours-quantity-time-max">
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
		
		
		<? // Установка количества // ?>
		<div class="itemCount">
			<div class="serviceItem__subtitle">
				<?= Loc::getMessage('QUANTITY') ?>
			</div>
			<div class="js-quantity-dec itemCount__button itemCount__down"></div>
			<div class="js-quantity-inc itemCount__button itemCount__up"></div>
			<input type="text" class="js-quantity js-hours-quantity-quantity itemCount__input styler" data-value="<?= $quanty ?>" value="<?= $quanty ?>" />
		</div>
    </div>
</div>