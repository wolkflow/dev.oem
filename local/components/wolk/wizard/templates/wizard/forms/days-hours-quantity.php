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
    <div class="js-days-hours-quantity-wrapper" data-pid="<?= $product->getID() ?>">
        <input type="hidden" name="DATES"    class="js-product-days-hours-quantity-dates js-field-value" value="<? (!empty($basketitem)) ? ($basketitem->getField('DATES')) : ('') ?>" />
        <input type="hidden" name="TIMES"    class="js-product-days-hours-quantity-times js-field-value" value="<? (!empty($basketitem)) ? ($basketitem->getField('TIMES')) : ('') ?>" />
        <input type="hidden" name="QUANTITY" class="js-product-days-hours-quantity-quantity js-field-value" value="<? (!empty($basketitem)) ? ($basketitem->getField('QUANTITY')) : ('') ?>" />

	    <? // Установка времени // ?>
	    <div class="setDatetime__right">
		    <div class="itemCount">
			    <div class="serviceItem__subtitle">
                    <?= Loc::getMessage('TIMES') ?>
			    </div>
			    <div class="setTime">
				    <select name="timemin" class="styler js-field-value js-days-hours-quantity-times js-days-hours-quantity-time-min">
                        <? for ($time = 8; $time <= 20; $time++) { ?>
                            <? $hour = str_pad($time, 2, '0', STR_PAD_LEFT).':00' ?>
						    <option value="<?= $hour ?>" <?= (!empty($basketitem) && $hour == $basketitem->getField('timemin')) ? ('selected') : ('') ?>>
                                <?= $hour ?>
						    </option>
                        <? } ?>
				    </select>
				    <span class="setTime__divider"></span>
				    <select name="timemax" class="styler js-field-value js-days-hours-quantity-times js-days-hours-quantity-time-max">
                        <? for ($time = 8; $time <= 20; $time++) { ?>
                            <? $hour = str_pad($time, 2, '0', STR_PAD_LEFT).':00' ?>
						    <option value="<?= $hour ?>" <?= ((!empty($basketitem) && $hour == $basketitem->getField('timemax')) || $hour == '20:00') ? ('selected') : ('') ?>>
                                <?= $hour ?>
						    </option>
                        <? } ?>
				    </select>
			    </div>
		    </div>
	    </div>

        <? // Установка даты // ?>
        <div class="setDatetime">
            <div class="setDateBlock calendarPopupWrapper js-calendar-wrap">
				<div class="serviceItem__subtitle">
					<?= Loc::getMessage('DATES') ?>
				</div>
				<div class="setDate customizable"></div>
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
						<? $begin  = $arResult['EVENT']->getDateBegin() ?>
						<? $begin  = (!empty($begin)) ? (date('m/d/y', strtotime($begin))) : (date('d.m.y', strtotime('-3 days'))) ?>
						<? $finish = $arResult['EVENT']->getDateFinish() ?>
						<? $finish = (!empty($finish)) ? (date('m/d/y', strtotime($finish))) : (date('d.m.y', strtotime('+1 year'))) ?>
						<div class="calendar" data-date-min="<?= $begin ?>" data-date-max="<?= $finish ?>"></div>
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
			<div class="js-quantity-dec itemCount__button itemCount__down customizable"></div>
			<div class="js-quantity-inc itemCount__button itemCount__up customizable"></div>
			<input type="text" class="js-quantity js-days-hours-quantity-quantity itemCount__input styler" data-value="<?= $quanty ?>" value="<?= $quanty ?>" />
		</div>
    </div>
</div>