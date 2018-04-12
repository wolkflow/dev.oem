<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<? $this->setFrameMode(true); ?>

<? $lang = \Bitrix\Main\Context::getCurrent()->getLanguage(); ?>

<? $component = $this->getComponent() ?>

<? use Bitrix\Main\Localization\Loc; ?>
<? use Wolk\Core\Helpers\Text as TextHelper; ?>


<? if ($arResult['STEP'] != 'types') { ?>
	<? $stepstand = ($arResult['STEP'] == 'stands') ?>
	<? unset($arResult['STEPS'][array_search('types', $arResult['STEPS'])]); ?>

	<div class="breadcrumbs">
		<div class="breadcrumbs__container items-<?= count($arResult['STEPS']) ?>">
			<? foreach ($arResult['STEPS'] as $i => $step) { ?>
				<a href="<?= ($stepstand) ? ('javascript:void(0)') : ($component->getStepLink($i)) ?>" class="breadcrumbs__button js-step js-step-<?= $step ?> <?= ($step == $arResult['STEP']) ? ('active') : ('') ?>"> 
					<span class="breadcrumbs__buttoncontainer">
						<?= $i ?>. <?= Loc::getMessage('STEP_' . mb_strtoupper($step)) ?>
					</span>
				</a>
			<? } ?>
		</div>
	</div>


	<? // Даты наценки // ?>
	<div class="catalogdeadline" v-show="hasMargins">
		<div class="catalogdeadline__deadlinecontainer">
			<div class="catalogdeadline__deadlinetitle customizable_border">
				<?= Loc::getMessage('DEADLINE') ?>
				<span class="catalogdeadline__deadlinedate">
					<? $dates = $arResult['EVENT']->getMarginDates() ?>
					
					<? if (\Bitrix\Main\Context::getCurrent()->getLanguage() == LANG_RU) { ?>
						<? $date = strtotime(reset(array_keys($dates))) ?>
						<?= date('j', $date) ?>
						<?= TextHelper::i18nmonth(date('n', $date), false, \Bitrix\Main\Context::getCurrent()->getLanguage()) ?>,
						<?= date('Y', $date) ?>
					<? } else { ?>
						<? $date = strtotime(reset(array_keys($dates))) ?>
						<?= TextHelper::i18nmonth(date('n', $date), false, \Bitrix\Main\Context::getCurrent()->getLanguage()) ?>
						<?= date('j', $date) ?><sup><?= Loc::getMessage('WEEKDAY') ?></sup>,
						<?= date('Y', $date) ?>
					<? } ?>
				</span>
			</div>
			<div class="catalogdeadline__deadlinedescription">
				<? foreach ($dates as $date => $percent) { ?>
					<span>
						<?= Loc::getMessage('SURCHARGE', ['#PERCENT#' => $percent.'%', '#DATE#' => $date]) ?><br>
					</span>
				<? } ?>
			</div>
		</div>
		<div class="catalogdeadline__timetablebutton customizable" data-modal="#timetable">
			<?= Loc::getMessage('SCHEDULE') ?>
		</div>
	</div>
<? } ?>


<? // Подключение шага // ?>
<div id="js-wrapper-id" data-eid="<?= $arResult['CONTEXT']->getEventID() ?>" data-code="<?= $arResult['EVENT']->getCode() ?>" data-type="<?= $arResult['CONTEXT']->getType() ?>">
    <? include ($_SERVER['DOCUMENT_ROOT'] . $this->getFolder() . '/steps/' . $arResult['STEP'].'.php') ?>
</div>


<div class="hide">
	<? // Расписание. // ?>
    <div class="modal" id="timetable">
        <div class="modalClose arcticmodal-close"></div>
        <div class="modalTitle">
			<?= Loc::getMessage('EVENT_TIMETABLE') ?>
		</div>
        <?= $arResult['EVENT']->getSchedule() ?>
    </div>
</div>