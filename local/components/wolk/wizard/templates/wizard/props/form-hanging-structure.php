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
        <input v-model="fields.companyName" type="text" class="styler">
    </div>
    <div class="serviceItem__row">
        <div class="serviceItem__col-3">
            <div class="serviceItem__subtitle"><?= Loc::getMessage('pavillion') ?> №</div>
            <input v-model="fields.pavilionNum" type="text" class="styler">
        </div>
        <div class="serviceItem__col-3">
            <div class="serviceItem__subtitle"><?= Loc::getMessage('hall') ?> №</div>
            <input v-model="fields.hallNum" type="text" class="styler">
        </div>
        <div class="serviceItem__col-3">
            <div class="serviceItem__subtitle"><?= Loc::getMessage('stand') ?> №</div>
            <input v-model="fields.standNum" type="text" class="styler">
        </div>
    </div>
    <div class="serviceItem__row">
        <div class="serviceItem__col-3">
            <div class="serviceItem__subtitle"><?= Loc::getMessage('size') ?> <span>(mm)</span></div>
            <input v-model="fields.size" type="text" class="styler" placeholder="L x W x H">
        </div>
        <div class="serviceItem__col-3">
            <div class="serviceItem__subtitle"><?= Loc::getMessage('material') ?></div>
            <input v-model="fields.material" type="text" class="styler">
        </div>
        <div class="serviceItem__col-3">
            <div class="serviceItem__subtitle">
                <?= Loc::getMessage('weight') ?> <span>(kg)</span>
                <i class="tip" title="Tip"></i>
            </div>
            <input v-model="fields.weight" type="text" class="styler">
        </div>
    </div>
    <div class="serviceItem__row">
        <div class="serviceItem__subtitle">
            <?= Loc::getMessage('List of the equipment placing on the structure') ?>
        </div>
        <textarea v-model="fields.listOfTheEquipmentPlacingOnTheStructure" class="styler" placeholder="<?= Loc::getMessage('placeholder_list_equipment') ?>"></textarea>
    </div>
    <div class="serviceItem__row">
        <div class="serviceItem__col-2">
            <div class="serviceItem__subtitle">
                <?= Loc::getMessage('material') ?>
            </div>
            <input v-model="fields.material2" type="text" class="styler" />
        </div>
        <div class="serviceItem__col-2">
            <div class="serviceItem__subtitle">
                <?= Loc::getMessage('Weight per point') ?> <span>(kg)</span>
            </div>
            <input v-model="fields.weightPerPoint" type="text" class="styler">
        </div>
    </div>
    <div class="serviceItem__row">
        <div class="serviceItem__col-2">
            <div class="serviceItem__subtitle">
                <?= Loc::getMessage('height') ?> <span>(mm)</span>
                <i class="tip" title="Tip"></i>
            </div>
            <input v-model="fields.height" type="text" class="styler">
        </div>
        <div class="serviceItem__col-2">
            <div class="serviceItem__subtitle">
                <?= Loc::getMessage('Total weight') ?>  <span>(kg)</span>
                <i class="tip" title="Tip"></i>
            </div>
            <input v-model="fields.totalWeight" type="text" class="styler">
        </div>
    </div>
    <div class="serviceItem__row">
        <div class="serviceItem__subtitle">
            <?= Loc::getMessage('Person in charge of the project of the structure') ?>
        </div>
        <input v-model="fields.personInChargeOfTheProjectOfTheStructure" type="text" class="styler" />
    </div>
    <div class="serviceItem__row">
        <div class="serviceItem__subtitle">
            <?= Loc::getMessage('Person in charge of mounting works') ?>
        </div>
        <input v-model="fields.personInChargeOfMountingWorks" type="text" class="styler" />
    </div>
    <div class="serviceItem__row">
        <div class="serviceItem__subtitle">
            <?= Loc::getMessage('Mobile phone') ?>
        </div>
        <input v-model="fields.mobilePhone" type="text" class="styler" />
    </div>
</div>