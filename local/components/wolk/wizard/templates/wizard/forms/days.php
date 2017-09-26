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
			<div class="setDateBlock calendarPopupWrapper js-calendar-wrap">
				<div class="serviceItem__subtitle">
					<?= Loc::getMessage('DATES') ?>
				</div>
				<div class="setDate"></div>
				<div class="calendarPopupBlock js-calendar-popup">
					<div class="calendarPopupContent js-calendar-content">
						<div class="calendarPopupButtons">
							<label class="styler">
								<input type="checkbox" class="changeMode js-calendar-mode" />
								<span></span><?= Loc::getMessage('DATERANGE') ?>
							</label>
							<a href="javascript:void(0)" class="calendarReset js-calendar-reset">
								<?= Loc::getMessage('CANCEL') ?>
							</a>
							<a href="javascript:void(0)" class="calendarSave js-calendar-save">OK</a>
						</div>
						<input type="text" class="start-date" hidden />
						<input type="text" class="end-date" hidden />
						<div class="calendar" date-min="0" date-max="10/02/2017"></div>
					</div>
				</div>
				<div class="dates"></div>
			</div>
		</div>
	</div>
</div>
