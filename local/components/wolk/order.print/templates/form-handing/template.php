<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>

<? use Bitrix\Main\Localization\Loc; ?>

<?	// Проверка наличия заполненной формы в составе заказа.
	if (empty($arResult['FORM'])) { 
		return; 
	}
?>

<div class="f4a-wrapper">
	<div class="f4a-header">
		<div class="f4a-date f4a-string">
			<span><?= Loc::getMessage('DATE') ?></span>
			<?= date('d.m.Y', strtotime($arResult['ORDER']['DATE_INSERT'])) ?>
		</div>
		<div class="f4a-company">
			<div class="f4a-company_name">
				<?= $arResult['USER']['WORK_COMPANY'] ?>
			</div>
			<?= Loc::getMessage('FORM_INFO') ?>
			<span><?= Loc::getMessage('FORM_INFO_NOTE') ?></span>
		</div>
	</div>

	<div class="f4a-data">
		<ul>
			<li>
				<span><?= Loc::getMessage('EXHIBITION') ?></span>
				<?= $arResult['EVENT']['PROPS']['LANG_TITLE_' . $arResult['LANGUAGE']]['VALUE'] ?>
			</li>
			<li class="f4a-double">
				<span><?= Loc::getMessage('COMPANY') ?><i><?= Loc::getMessage('CUSTOMER') ?></i></span>
				<?= $arResult['FORM']['COMPANY'] ?>
			</li>
			<li>
				<div class="col">
					<span><?= Loc::getMessage('PAVILION') ?></span>
					<?= $arResult['FORM']['PAVILION'] ?>
				</div>
				<div class="col">
					<span><?= Loc::getMessage('HALL') ?></span>
					<?= $arResult['FORM']['HALL'] ?>
				</div>
				<div class="col">
					<span><?= Loc::getMessage('STAND') ?></span>
					<?= $arResult['FORM']['STAND'] ?>
				</div>
			</li>
			<li>
				<span><?= Loc::getMessage('SIZE') ?></span>
				<?= $arResult['FORM']['SIZE'] ?>
			</li>
			<li>
				<span><?= Loc::getMessage('MATERIALS') ?></span>
				<?= $arResult['FORM']['MATERIAL'] ?>
			</li>
			<li>
				<span><?= Loc::getMessage('WEIGHT') ?></span>
				<?= $arResult['FORM']['WEIGHT'] ?>
			</li>
			<li class="f4a-double-p">
				<span class="width100p">
					<?= Loc::getMessage('LIST') ?>
					<i><?= Loc::getMessage('LIST_NOTE') ?></i>
				</span>
				<?= $arResult['FORM']['LIST'] ?>
			</li>
			<li>
				<span><?= Loc::getMessage('FULLWEIGHT') ?></span>
				<?= $arResult['FORM']['FULLWEIGHT'] ?>
			</li>
			<li>
				<span><?= Loc::getMessage('POINTS') ?></span>
				
			</li>
			<li>
				<span><?= Loc::getMessage('POINTWEIGHT') ?></span>
				<?= $arResult['FORM']['POINTWEIGHT'] ?>
			</li>
			<li>
				<span><?= Loc::getMessage('HEIGHT') ?><em><?= Loc::getMessage('HEIGHT_NOTE') ?></em></span>
				<?= $arResult['FORM']['HEIGHT'] ?>
			</li>
			<li>
				<span><b><?= Loc::getMessage('PERSON_PROJECT') ?></b></span>
				<?= $arResult['FORM']['PERSON_PROJECT'] ?>
			</li>
			<li>
				<span class="width100p"><b><?= Loc::getMessage('PERSON_MOUNT') ?><em><?= Loc::getMessage('PERSON_MOUNT_NOTE') ?></em></b></span>
				<?= $arResult['FORM']['PERSON_MOUNT'] ?>
			</li>
		</ul>
	</div>

	<div class="f4a-note">
		<p><?= Loc::getMessage('RESPONSIBILITY') ?></p>
	</div>

	<p class="text-center f10"><b><?= Loc::getMessage('ORDER') ?></b></p>

	<div class="f4a-bottom">
		<div class="f4a-sign">
			<span><?= Loc::getMessage('SIGNATURE') ?></span>
			<i><?= Loc::getMessage('CUSTOMER') ?></i>
		</div>

		<div class="f4a-mp">
			<?= Loc::getMessage('PLACE') ?>
		</div>

		<div class="f4a-agreed">
			<?= Loc::getMessage('AGREED') ?><br>
			<?= Loc::getMessage('PLC') ?>
		</div>
	</div>
</div>