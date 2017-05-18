<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<? $this->setFrameMode(true); ?>

<? $component = $this->getComponent() ?>

<? use Bitrix\Main\Localization\Loc; ?>

<div class="breadcrumbs">
    <div class="breadcrumbs__container">
        <? foreach ($arResult['STEPS'] as $i => $step) { ?>
            <a href="<?= $component->getStepLink($i) ?>" class="breadcrumbs__button <?= ($step == $arResult['STEP']) ? ('active') : ('') ?>"> 
                <span class="breadcrumbs__buttoncontainer">
                    <?= ($i + 1) ?>. <?= Loc::getMessage('STEP_' . mb_strtoupper($step)) ?>
                </span>
            </a>
        <? } ?>
    </div>
</div>