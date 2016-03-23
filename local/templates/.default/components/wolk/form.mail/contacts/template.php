<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<? $this->setFrameMode(true) ?>

<? use Bitrix\Main\Localization\Loc ?>

<? if (!empty($arResult['ERRORS'])) { ?>
	<script>
		$(document).ready(function() {
			$('#js-contacts-link-id').trigger('click');
		});
	</script>
<? } ?>

<form method="post">
	<input type="hidden" name="<?= $arParams['FORM'] ?>" value="<?= $arParams['FORM'] ?>" />
	
	<? if (!empty($arResult['ERRORS'])) { ?>
        <div class="form-error"><?= implode('<br/>', $arResult['ERRORS']) ?></div>
    <? } ?>
    <? if (!empty($arResult['MESSAGE'])) { ?>
        <div class="form-success"><?= $arResult['MESSAGE'] ?></div>
    <? } ?>
	
	<div class="contactUs__left">
		<div class="formRow">
			<div class="formCol">
				<label for="uName"><?= loc::getMessage('NAME') ?></label>
				<input type="text" class="styler" id="uName" name="NAME" value="<?= $arResult['DATA']['NAME'] ?>" />
			</div>
			<div class="formCol">
				<label for="uPhone"><?= loc::getMessage('PHONE') ?></label>
				<input type="text" class="styler" id="uPhone" name="PHONE" value="<?= $arResult['DATA']['PHONE'] ?>" />
			</div>
		</div>
		<div class="formRow">
			<div class="formCol">
				<label for="ucName"><?= loc::getMessage('COMPANY') ?></label>
				<input type="text" class="styler" id="ucName" name="COMPANY" value="<?= $arResult['DATA']['COMPANY'] ?>" />
			</div>
			<div class="formCol">
				<label for="standNum"><?= loc::getMessage('STAND') ?></label>
				<input type="text" class="styler" id="standNum" name="STAND" value="<?= $arResult['DATA']['STAND'] ?>" />
			</div>
		</div>
		<div class="formRow">
			<label for="uMail"><?= loc::getMessage('EMAIL') ?></label>
			<input type="text" id="uMail" class="styler" name="EMAIL" value="<?= $arResult['DATA']['EMAIL'] ?>" />
		</div>
		<div class="formRow">
			<label for="uMessage"><?= loc::getMessage('MESSAGE') ?></label>
			<textarea id="uMessage" name="MESSAGE"><?= $arResult['DATA']['MESSAGE'] ?></textarea>
		</div>
		<div class="formRow">
			<p class="formNote">* <?= loc::getMessage('ALL_FIELDS_REQUIRED') ?></p>
		</div>
		<div class="formRow">
			<input type="submit" class="styler modalSend" value="<?= loc::getMessage('SEND') ?>" />
		</div>
	</div>
</form>