<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<? use Bitrix\Main\Localization\Loc; ?>
<? use Wolk\Core\Helpers\Text as TextHelper ?>
<? use Wolk\Oem\Basket; ?>

<? $quanty = intval((!empty($basketitem)) ? ($basketitem->getField('QUANTITY')) : ('')) ?>
<? $field  = (!empty($basketitem)) ? ($basketitem->getField('DATES')) : ('') ?>
<? $range  = (strpos($field, '-') !== false) ?>
<?	// Массив дат.
	if ($range) {
		$dates = array_map('trim', array_filter(explode('-', $field)));
	} else {
		$dates = array_map('trim', array_filter(explode(',', $field)));
	}
	$dates = array_map(function($item) { return (date('m/d/Y', strtotime($item))); }, $dates);
?>

<div class="serviceItem__row">
    <div class="js-days-quantity-wrapper" data-pid="<?= $product->getID() ?>">
        <input type="hidden" name="DATES"    class="js-product-days-quantity-dates js-field-value" value="<? (!empty($basketitem)) ? ($basketitem->getField('DATES')) : ('') ?>" />
        <input type="hidden" name="QUANTITY" class="js-product-days-quantity-quantity js-field-value" value="<? (!empty($basketitem)) ? ($basketitem->getField('QUANTITY')) : ('') ?>" />
		
        <? // Установка даты // ?>
        <div class="setDatetime">
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
        
		
		<? // Установка количества // ?>
		<div class="itemCount">
			<div class="serviceItem__subtitle">
				<?= Loc::getMessage('QUANTITY') ?>
			</div>
			<div class="js-quantity-dec itemCount__button itemCount__down"></div>
			<div class="js-quantity-inc itemCount__button itemCount__up"></div>
			<input type="text" class="js-quantity js-days-quantity-quantity itemCount__input styler" data-value="<?= $quanty ?>" value="<?= $quanty ?>" />
		</div>
    </div>
</div>