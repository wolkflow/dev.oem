<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<? use Bitrix\Main\Localization\Loc; ?>
<? use Wolk\Core\Helpers\Text as TextHelper; ?>
<? use Wolk\Oem\Basket; ?>

<? $proptmpid = uniqid() ?>
<? $params = (is_object($basketitem)) ? ($basketitem->getParams()) : ([]) ?>
<? $value  = $params[Basket::PARAM_FORM_HANGING_STRUCTURE] ?>

<div class="js-param-block" data-code="<?= Basket::PARAM_FORM_HANGING_STRUCTURE ?>">
    <div class="serviceItem__row">
        <div class="serviceItem__subtitle"><?= Loc::getMessage('company_name') ?></div>
        <input type="text" class="styler js-param-value" name="<?= Basket::PARAM_FORM_HANGING_STRUCTURE ?>.COMPANY" value="<?= (!empty($value)) ? ($value['COMPANY']) : ('') ?>" />
    </div>
    <div class="serviceItem__row">
        <div class="serviceItem__col-3">
            <div class="serviceItem__subtitle"><?= Loc::getMessage('pavillion') ?> №</div>
            <input type="text" class="styler js-param-value" name="<?= Basket::PARAM_FORM_HANGING_STRUCTURE ?>.PAVILION" value="<?= (!empty($value)) ? ($value['PAVILION']) : ('') ?>" />
        </div>
        <div class="serviceItem__col-3">
            <div class="serviceItem__subtitle"><?= Loc::getMessage('hall') ?> №</div>
            <input type="text" class="styler js-param-value" name="<?= Basket::PARAM_FORM_HANGING_STRUCTURE ?>.HALL" value="<?= (!empty($value)) ? ($value['STANDNUM']) : ('') ?>" />
        </div>
        <div class="serviceItem__col-3">
            <div class="serviceItem__subtitle"><?= Loc::getMessage('stand') ?> №</div>
            <input type="text" class="styler js-param-value" name="<?= Basket::PARAM_FORM_HANGING_STRUCTURE ?>.STAND" value="<?= (!empty($value)) ? ($value['STAND']) : ('') ?>" />
        </div>
    </div>
    <div class="serviceItem__row">
        <div class="serviceItem__col-3">
            <div class="serviceItem__subtitle"><?= Loc::getMessage('size') ?> <span>(mm)</span></div>
            <input type="text" class="styler js-param-value" placeholder="L x W x H" name="<?= Basket::PARAM_FORM_HANGING_STRUCTURE ?>.SIZE" value="<?= (!empty($value)) ? ($value['SIZE']) : ('') ?>" />
        </div>
        <div class="serviceItem__col-3">
            <div class="serviceItem__subtitle"><?= Loc::getMessage('material') ?></div>
            <input type="text" class="style js-param-value" name="<?= Basket::PARAM_FORM_HANGING_STRUCTURE ?>.MATERIAL" value="<?= (!empty($value)) ? ($value['MATERIAL']) : ('') ?>" />
        </div>
        <div class="serviceItem__col-3">
            <div class="serviceItem__subtitle">
                <?= Loc::getMessage('weight') ?> <span>(kg)</span>
                <i class="tip" title="Tip"></i>
            </div>
            <input type="text" class="styler js-param-value" name="<?= Basket::PARAM_FORM_HANGING_STRUCTURE ?>.WEIGHT" value="<?= (!empty($value)) ? ($value['WEIGHT']) : ('') ?>" />
        </div>
    </div>
    <div class="serviceItem__row">
        <div class="serviceItem__subtitle">
            <?= Loc::getMessage('List of the equipment placing on the structure') ?>
        </div>
        <textarea class="styler js-param-value" placeholder="<?= Loc::getMessage('placeholder_list_equipment') ?>" name="<?= Basket::PARAM_FORM_HANGING_STRUCTURE ?>.LIST"><?= (!empty($value)) ? ($value['LIST']) : ('') ?></textarea>
    </div>
    <div class="serviceItem__row">
        <div class="serviceItem__col-2">
            <div class="serviceItem__subtitle">
                <?= Loc::getMessage('material') ?>
            </div>
            <input type="text" class="styler js-param-value" name="<?= Basket::PARAM_FORM_HANGING_STRUCTURE ?>.ONMATERIAL" value="<?= (!empty($value)) ? ($value['ONMATERIAL']) : ('') ?>" />
        </div>
        <div class="serviceItem__col-2">
            <div class="serviceItem__subtitle">
                <?= Loc::getMessage('Weight per point') ?> <span>(kg)</span>
            </div>
            <input type="text" class="styler js-param-value" name="<?= Basket::PARAM_FORM_HANGING_STRUCTURE ?>.POINTWEIGHT" value="<?= (!empty($value)) ? ($value['POINTWEIGHT']) : ('') ?>" />
        </div>
    </div>
    <div class="serviceItem__row">
        <div class="serviceItem__col-2">
            <div class="serviceItem__subtitle">
                <?= Loc::getMessage('height') ?> <span>(mm)</span>
                <i class="tip" title="Tip"></i>
            </div>
            <input type="text" class="styler js-param-value" name="<?= Basket::PARAM_FORM_HANGING_STRUCTURE ?>.HEIGHT" value="<?= (!empty($value)) ? ($value['HEIGHT']) : ('') ?>" />
        </div>
        <div class="serviceItem__col-2">
            <div class="serviceItem__subtitle">
                <?= Loc::getMessage('Total weight') ?>  <span>(kg)</span>
                <i class="tip" title="Tip"></i>
            </div>
            <input type="text" class="styler js-param-value" name="<?= Basket::PARAM_FORM_HANGING_STRUCTURE ?>.FULLWEIGHT" value="<?= (!empty($value)) ? ($value['FULLWEIGHT']) : ('') ?>" />
        </div>
    </div>
    <div class="serviceItem__row">
        <div class="serviceItem__subtitle">
            <?= Loc::getMessage('Person in charge of the project of the structure') ?>
        </div>
        <input type="text" class="styler js-param-value" name="<?= Basket::PARAM_FORM_HANGING_STRUCTURE ?>.PERSON_PROJECT" value="<?= (!empty($value)) ? ($value['PERSON_PROJECT']) : ('') ?>" />
    </div>
    <div class="serviceItem__row">
        <div class="serviceItem__subtitle">
            <?= Loc::getMessage('Person in charge of mounting works') ?>
        </div>
        <input type="text" class="styler js-param-value" name="<?= Basket::PARAM_FORM_HANGING_STRUCTURE ?>.PERSON_MOUNT" value="<?= (!empty($value)) ? ($value['PERSON_MOUNT']) : ('') ?>" />
    </div>
    <div class="serviceItem__row">
        <div class="serviceItem__subtitle">
            <?= Loc::getMessage('Mobile phone') ?>
        </div>
        <input type="text" class="styler js-param-value" name="<?= Basket::PARAM_FORM_HANGING_STRUCTURE ?>.PHONE" value="<?= (!empty($value)) ? ($value['PHONE']) : ('') ?>" />
    </div>
</div>