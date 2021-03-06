<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<? use Bitrix\Main\Localization\Loc; ?>
<? use Wolk\Core\Helpers\Text as TextHelper; ?>
<? use Wolk\Oem\Basket; ?>

<? $proptmpid = uniqid() ?>
<? $params = (is_object($basketitem)) ? ($basketitem->getParams()) : ([]) ?>
<? $value  = $params[Basket::PARAM_FORM_HANGING_STRUCTURE] ?>

<? $required = (in_array(Basket::PARAM_FORM_HANGING_STRUCTURE, $arResult['SECTION_PARAMS'][$section->getID()]['PROPS']['REQUIRED'])) ?>

<div class="js-param-block js-param-form_hanging_structure <?= ($required) ? ('js-param-required') : ('') ?>" data-code="<?= Basket::PARAM_FORM_HANGING_STRUCTURE ?>">
    <br/>
	<button class="js-form-opener styler button-form customizable form_hanging_structure_button" title="<?= Loc::getMessage('FORM_SWITCHER') ?>">
		<?= Loc::getMessage('FORM_HANDING') ?>
	</button>
	<div class="js-form-wrapper form-wrapper">
		<div class="serviceItem__row">
			<div class="serviceItem__subtitle">
				<?= ($arResult['SECTION_PARAMS'][$section->getID()]['NAMES'][Basket::PARAM_FORM_HANGING_STRUCTURE]) ?: (Loc::getMessage('FORM_COMPANY')) ?>
			</div>
			<input type="text" class="styler js-param-value" name="<?= Basket::PARAM_FORM_HANGING_STRUCTURE ?>.COMPANY" value="<?= (!empty($value)) ? ($value['COMPANY']) : ('') ?>" />
		</div>
		<div class="serviceItem__row">
			<div class="serviceItem__col-3">
				<div class="serviceItem__subtitle">
					<?= Loc::getMessage('FORM_PAVILION') ?>
				</div>
				<input type="text" class="styler js-param-value" name="<?= Basket::PARAM_FORM_HANGING_STRUCTURE ?>.PAVILION" value="<?= (!empty($value)) ? ($value['PAVILION']) : ('') ?>" />
			</div>
			<div class="serviceItem__col-3">
				<div class="serviceItem__subtitle">
					<?= Loc::getMessage('FORMM_HALL') ?>
				</div>
				<input type="text" class="styler js-param-value" name="<?= Basket::PARAM_FORM_HANGING_STRUCTURE ?>.HALL" value="<?= (!empty($value)) ? ($value['STANDNUM']) : ('') ?>" />
			</div>
			<div class="serviceItem__col-3">
				<div class="serviceItem__subtitle">
					<?= Loc::getMessage('FORM_STAND') ?>
				</div>
				<input type="text" class="styler js-param-value" name="<?= Basket::PARAM_FORM_HANGING_STRUCTURE ?>.STAND" value="<?= (!empty($value)) ? ($value['STAND']) : ('') ?>" />
			</div>
		</div>
		<div class="serviceItem__row">
			<div class="serviceItem__col-3">
				<div class="serviceItem__subtitle">
					<?= Loc::getMessage('FORM_SIZE') ?>
					<span>(<?= Loc::getMessage('FORM_SIZE_DIMENSION') ?>)</span>
				</div>
				<input type="text" class="styler js-param-value" placeholder="<?= Loc::getMessage('FORM_SIZE_HOLDER') ?>" name="<?= Basket::PARAM_FORM_HANGING_STRUCTURE ?>.SIZE" value="<?= (!empty($value)) ? ($value['SIZE']) : ('') ?>" />
			</div>
			<div class="serviceItem__col-3">
				<div class="serviceItem__subtitle">
					<?= Loc::getMessage('FORM_MATERIAL') ?>
				</div>
				<input type="text" class="styler js-param-value" name="<?= Basket::PARAM_FORM_HANGING_STRUCTURE ?>.MATERIAL" value="<?= (!empty($value)) ? ($value['MATERIAL']) : ('') ?>" />
			</div>
			<div class="serviceItem__col-3">
				<div class="serviceItem__subtitle">
					<?= Loc::getMessage('FORM_WEIGHT') ?>
					<span>(<?= Loc::getMessage('FORM_WEIGHT_DIMENSION') ?>)</span>
				</div>
				<input type="text" class="styler js-param-value" name="<?= Basket::PARAM_FORM_HANGING_STRUCTURE ?>.WEIGHT" value="<?= (!empty($value)) ? ($value['WEIGHT']) : ('') ?>" />
			</div>
		</div>
		<div class="serviceItem__row">
			<div class="serviceItem__subtitle">
				<?= Loc::getMessage('FORM_LIST') ?>
			</div>
			<textarea class="styler js-param-value" placeholder="<?= Loc::getMessage('FORM_LIST_HOLDER') ?>" name="<?= Basket::PARAM_FORM_HANGING_STRUCTURE ?>.LIST"><?= (!empty($value)) ? ($value['LIST']) : ('') ?></textarea>
		</div>
		<div class="serviceItem__row">
			<div class="serviceItem__col-2">
				<div class="serviceItem__subtitle">
					<?= Loc::getMessage('FORM_MATERIAL') ?>
				</div>
				<input type="text" class="styler js-param-value" name="<?= Basket::PARAM_FORM_HANGING_STRUCTURE ?>.ONMATERIAL" value="<?= (!empty($value)) ? ($value['ONMATERIAL']) : ('') ?>" />
			</div>
			<div class="serviceItem__col-2">
				<div class="serviceItem__subtitle">
					<?= Loc::getMessage('FORM_WEIGHT_POINT') ?>
					<span>(<?= Loc::getMessage('FORM_WEIGHT_POINT_DIMENSION') ?>)</span>
				</div>
				<input type="text" class="styler js-param-value" name="<?= Basket::PARAM_FORM_HANGING_STRUCTURE ?>.POINTWEIGHT" value="<?= (!empty($value)) ? ($value['POINTWEIGHT']) : ('') ?>" />
			</div>
		</div>
		<div class="serviceItem__row">
			<div class="serviceItem__col-2">
				<div class="serviceItem__subtitle">
					<?= Loc::getMessage('FORM_HEIGHT') ?>
					<span>(<?= Loc::getMessage('FORM_HEIGHT_DIMENSION') ?>)</span>
				</div>
				<input type="text" class="styler js-param-value" name="<?= Basket::PARAM_FORM_HANGING_STRUCTURE ?>.HEIGHT" value="<?= (!empty($value)) ? ($value['HEIGHT']) : ('') ?>" />
			</div>
			<div class="serviceItem__col-2">
				<div class="serviceItem__subtitle">
					<?= Loc::getMessage('FORM_WEIGHT_TOTAL') ?>
					<span>(<?= Loc::getMessage('FORM_WEIGHT_TOTAL_DIMENSION') ?>)</span>
				</div>
				<input type="text" class="styler js-param-value" name="<?= Basket::PARAM_FORM_HANGING_STRUCTURE ?>.FULLWEIGHT" value="<?= (!empty($value)) ? ($value['FULLWEIGHT']) : ('') ?>" />
			</div>
		</div>
		<div class="serviceItem__row">
			<div class="serviceItem__subtitle">
				<?= Loc::getMessage('FORM_ASSIGNEE_PROJECT') ?>
			</div>
			<input type="text" class="styler js-param-value" name="<?= Basket::PARAM_FORM_HANGING_STRUCTURE ?>.PERSON_PROJECT" value="<?= (!empty($value)) ? ($value['PERSON_PROJECT']) : ('') ?>" />
		</div>
		<div class="serviceItem__row">
			<div class="serviceItem__subtitle">
				<?= Loc::getMessage('FORM_ASSIGNEE_MOUNTING') ?>
			</div>
			<input type="text" class="styler js-param-value" name="<?= Basket::PARAM_FORM_HANGING_STRUCTURE ?>.PERSON_MOUNT" value="<?= (!empty($value)) ? ($value['PERSON_MOUNT']) : ('') ?>" />
		</div>
		<div class="serviceItem__row">
			<div class="serviceItem__subtitle">
				<?= Loc::getMessage('FORM_PHONE') ?>
			</div>
			<input type="text" class="styler js-param-value" name="<?= Basket::PARAM_FORM_HANGING_STRUCTURE ?>.PHONE" value="<?= (!empty($value)) ? ($value['PHONE']) : ('') ?>" />
		</div>
	</div>
</div>