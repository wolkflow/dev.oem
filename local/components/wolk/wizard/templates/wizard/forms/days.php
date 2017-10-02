<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<? use Bitrix\Main\Localization\Loc; ?>
<? use Wolk\Core\Helpers\Text as TextHelper ?>
<? use Wolk\Oem\Basket; ?>

<? $field = (!empty($basketitem)) ? ($basketitem->getField('DATES')) : ('') ?>
<? $range = (strpos($field, '-') !== false) ?>
<?	// Массив дат.
	if ($range) {
		$dates = array_map('trim', array_filter(explode('-', $field)));
	} else {
		$dates = array_map('trim', array_filter(explode(',', $field)));
	}
	$dates = array_map(function($item) { return (date('m/d/Y', strtotime($item))); }, $dates);
?>

<div class="serviceItem__row">
	<div class="js-days-wrapper itemCount" data-pid="<?= $product->getID() ?>">
		<input type="hidden" name="DATES" class="js-product-days-dates js-field-value" value="<?= $field ?>" />
		
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
								list($mindate, $maxdate) = $dates;
							}
						?>
						<input type="text" class="min-date" hidden value="<?= $mindate ?>" />
						<input type="text" class="max-date" hidden value="<?= $maxdate ?>" />
						<? $finish = $arResult['EVENT']->getDateFinish() ?>
						<? $finish = (!empty($finish)) ? (date('m/d/y', strtotime($finish . ' +3 days'))) : (date('d.m.y', strtotime('+1 year'))) ?>
						<div class="calendar" data-date-min="<?= strtotime('-3 days') ?>" data-date-max="<?= $finish ?>"></div>
					</div>
				</div>
				<div class="dates js-calendar-dates" data-field="<?= implode(', ', $dates) ?>" data-dates='<?= json_encode($dates) ?>'>
					<?= $field ?>
				</div>
			</div>
		</div>
	</div>
</div>
