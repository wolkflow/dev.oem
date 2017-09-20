<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<? use Bitrix\Main\Localization\Loc; ?>
<? use Wolk\Core\Helpers\Text as TextHelper ?>
<? use Wolk\Oem\Basket; ?>

<?php
/**
?>
<div class="serviceItem__row">
    <div class="js-days-wrapper itemCount" data-pid="<?= $product->getID() ?>">
        <input type="hidden" name="DATES" class="js-product-days-dates" value="" />
        <input type="hidden" name="TIMES" class="js-product-days-times" value="" />

        <? // Установка даты // ?>
        <div class="serviceItem__left">
            <div class="setDateBlock">
                <div class="serviceItem__subtitle">
                    <?= Loc::getMessage('DATES') ?>
                </div>
                <input name="DATES" class="setDate js-field-value js-days-datepicker" value="<?= (!empty($basketitem)) ? ($basketitem->getField('dates')) : ('') ?>" />
            </div>
        </div>
    </div>
</div>
*/
?>
<div class="serviceItem__row">
	<div class="js-days-wrapper itemCount" data-pid="<?= $product->getID() ?>">
		<input type="hidden" name="DATES" class="js-product-days-dates" value="" />
		<input type="hidden" name="TIMES" class="js-product-days-times" value="" />
		<? // Установка даты // ?>
		<div class="serviceItem__left">
			<div class="setDateBlock calendarPopupWrapper">
				<div class="serviceItem__subtitle">
					<?= Loc::getMessage('DATES') ?>
				</div>
				<div class="setDate"></div>
				<div class="calendarPopupBlock">
					<div class="calendarPopupContent">
						<div class="calendarPopupButtons">
							<label class="styler">
								<input type="checkbox" class="changeMode">
								<span></span>Date range
							</label>
							<a href="javascript:void(0)" class="calendarReset">Clear</a>
							<a href="javascript:void(0)" class="calendarSave">Ok</a>
						</div>
						<input type="hidden" class="start-date">
						<input type="hidden" class="end-date">
						<div class="calendar" data-min-date="0" data-max-date="10/02/2017"></div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
