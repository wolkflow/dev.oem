<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<? use Bitrix\Main\Localization\Loc; ?>
<? use Wolk\Core\Helpers\Text as TextHelper ?>
<? use Wolk\Oem\Basket; ?>

<? $fields = (!empty($basketitem)) ? ($basketitem->getFields()) : ([]) ?>

<div class="js-width-height-wrapper" data-pid="<?= $product->getID() ?>">
	<div class="serviceItem__row">
		<div class="serviceItem__left">
			<div class="serviceItem__left-child">
				<div class="serviceItem__subtitle mt0">
					<?= Loc::getMessage('WIDTH') ?>
					<span>(<?= Loc::getMessage('MEASURE_MM') ?>)</span>
				</div>
				<input type="text" class="js-width js-field-value itemCount__input styler ma0 w100" name="WIDTH" data-value="<?= $fields['WIDTH'] ?>" value="<?= $fields['WIDTH'] ?>" />
			</div>
			<div class="serviceItem__left-child">
				<div class="serviceItem__subtitle mt0">
					<?= Loc::getMessage('HEIGHT') ?>
					<span>(<?= Loc::getMessage('MEASURE_MM') ?>)</span>
				</div>
				<input type="text" class="js-height js-field-value itemCount__input styler ma0 w100" name="HEIGHT" data-value="<?= $fields['HEIGHT'] ?>" value="<?= $fields['HEIGHT'] ?>" />
			</div>
		</div>
		<div class="serviceItem__right">
			<div class="itemCount">
				<div class="serviceItem__subtitle">
					<?= Loc::getMessage('QUANTITY') ?>
				</div>
				<div class="js-quantity-dec itemCount__button itemCount__down"></div>
				<div class="js-quantity-inc itemCount__button itemCount__up"></div>
				<input type="text" class="js-quantity js-field-value itemCount__input styler" name="QUANTITY" data-value="<?= $fields['QUANTITY'] ?>" value="<?= $fields['QUANTITY'] ?>" />
			</div>
		</div>
	</div>
</div>

