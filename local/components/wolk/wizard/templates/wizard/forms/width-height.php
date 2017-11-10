<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<? use Bitrix\Main\Localization\Loc; ?>
<? use Wolk\Core\Helpers\Text as TextHelper ?>
<? use Wolk\Oem\Basket; ?>

<? $fields = (!empty($basketitem)) ? ($basketitem->getFields()) : ([]) ?>

<div class="js-width-height-wrapper js-product-element" data-pid="<?= $product->getID() ?>">
	<div class="serviceItem__row">
		<div class="serviceItem__left">
			<div class="serviceItem__left-child">
				<div class="serviceItem__subtitle">
					<?= Loc::getMessage('WIDTH') ?>
					<span>(<?= Loc::getMessage('MEASURE_MM') ?>)</span>
				</div>
				<input type="text" class="js-width js-field-value itemCount__input styler ma0 w100" name="WIDTH" value="<?= (int) $fields['WIDTH'] ?>" />
			</div>
			<div class="serviceItem__left-child">
				<div class="serviceItem__subtitle">
					<?= Loc::getMessage('HEIGHT') ?>
					<span>(<?= Loc::getMessage('MEASURE_MM') ?>)</span>
				</div>
				<input type="text" class="js-height js-field-value itemCount__input styler ma0 w100" name="HEIGHT" value="<?= (int) $fields['HEIGHT'] ?>" />
			</div>
		</div>
	</div>
</div>

