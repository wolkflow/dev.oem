<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<? $this->setFrameMode(true); ?>

<? use Bitrix\Main\Localization\Loc; ?>
<? use Wolk\Core\Helpers\Text as TextHelper ?>

<? $lang = \Bitrix\Main\Context::getCurrent()->getLanguage() ?>

<? $stand = $arResult['EVENT']->getPreselectStand() ?>

<div id="step1">
    <div class="standspagetop" id="preselect">
        <? if (!empty($stand)) { ?>
            <div class="pagedescription">
                <?= Loc::getMessage('PRESELECT_STAND_NOTE') ?>
            </div>
        <? } ?>
        
        <? // Выбор стандартного стенда // ?>
        <div>
            <div class="pagetitle">
                <?= Loc::getMessage('CURRENT_STAND') ?>
            </div>
            <? if (!empty($stand)) { ?>
                <div class="standspagetop__currentstandcontainer customizable_border">
                    <div class="standspagetop__currentstanddescription">
                        <p>
                            <?= $stand->getDescription($lang) ?>
                        </p>
                        <? $equipments = $stand->getEquipments() ?>
                        <? if (count($equipments)) { ?>
                            <ul>
                                <?= Loc::getMessage('INCLUDES') ?>:
                                <? foreach ($equipments as $equipment) { ?> 
                                    <li>
                                        {{ eq.COUNT }} &times; {{ eq.NAME }}
                                    </li>
                                <? } ?>
                            </ul>
                        <? } ?>
                    </div>
                    <img :src="selectedStand.PREVIEW_PICTURE" class="standspagetop__photo" />
                    <a href="<?= $arResult['LINKS']['NEXT'] ?>" class="standspagetop__continuebutton customizable js-wizard-next-step">
                        <?= Loc::getMessage('CONTINUE') ?>
                    </a>
                </div>
            <? } ?>
        </div>
        
    </div>
</div>

<hr/>

<pre>
<? print_r($arResult) ?>
</pre>


