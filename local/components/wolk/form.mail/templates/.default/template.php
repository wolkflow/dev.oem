<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<? $this->setFrameMode(true) ?>

<div class="bl_contacts_form">
	<div class="bl_contacts_header">Задать вопрос</div>
	
	<? if (!empty($arResult['ERRORS'])) { ?>
        <div class="form-error"><?= implode('<br/>', $arResult['ERRORS']) ?></div>
		<br/>
    <? } ?>
    <? if (!empty($arResult['MESSAGE'])) { ?>
        <div class="form-success"><?= $arResult['MESSAGE'] ?></div>
		<br/>
    <? } ?>
	
	<form method="post">
        <input type="hidden" name="<?= $arParams['FORM'] ?>" value="<?= $arParams['FORM'] ?>" />
		
		<input type="text" class="input_contacts_form" placeholder="ФИО" name="NAME" value="<?= $arResult['DATA']['NAME'] ?>" />
		<input type="text" class="input_contacts_form" placeholder="E-mail" name="EMAIL" value="<?= $arResult['DATA']['EMAIL'] ?>" />
		
		<textarea placeholder="Сообщение" class="textarea_contacts_form" name="MESSAGE"><?= $arResult['DATA']['MESSAGE'] ?></textarea>
		<input type="submit" class="but" value="Отправить" />
	</form>
</div>