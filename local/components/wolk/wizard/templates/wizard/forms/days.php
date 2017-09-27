<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<? use Bitrix\Main\Localization\Loc; ?>
<? use Wolk\Core\Helpers\Text as TextHelper ?>
<? use Wolk\Oem\Basket; ?>

<? $dates = (!empty($basketitem)) ? ($basketitem->getField('DATES')) : ([]) ?>
<? $range = (strpos($dates, '-') !== false) ?>

<div class="serviceItem__row">
	<div class="js-days-wrapper itemCount" data-pid="<?= $product->getID() ?>">
		<input type="hidden" name="DATES" class="js-product-days-dates js-field-value" value="<?= $dates ?>" />
		
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
								<input type="checkbox" class="changeMode js-calendar-mode" data-checked="<?= ($range) ? ('1') : ('0') ?>" />
								<span></span><?= Loc::getMessage('DATERANGE') ?>
							</label>
							<a href="javascript:void(0)" class="calendarReset js-calendar-reset">
								<?= Loc::getMessage('CANCEL') ?>
							</a>
							<a href="javascript:void(0)" class="calendarSave js-calendar-save">OK</a>
						</div>
						<?  // Установка дат.
							$mindate = '';
							$maxdate = '';
							if ($range) {
								list($mindate, $maxdate) = array_map('trim', explode('-', $dates));
							}
						?>
						<input type="text" class="min-date" hidden value="<?= $mindate ?>" />
						<input type="text" class="max-date" hidden value="<?= $maxdate ?>" />
						<div class="calendar" data-date-min="0" data-date-max="15.11.17"></div>
					</div>
				</div>
				<div class="dates"><?= $dates ?></div>
			</div>
		</div>
	</div>
</div>
