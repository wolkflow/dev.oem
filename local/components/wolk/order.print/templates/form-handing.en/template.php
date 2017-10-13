<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>

<? use Bitrix\Main\Localization\Loc; ?>

<?	// Проверка наличия заполненной формы в составе заказа.
	if (empty($arResult['FORM'])) { 
		return; 
	}
?>

<div class="f4a-wrapper">
	<div class="f4a-header">
		<div class="f4a-company">
			<div class="f4a-company_name">To: <?= $arResult['USER']['WORK_COMPANY'] ?></div>
			Information about the structure for hanging up inside the pavilion
			<span>(This letter should be submitted to the pavilion administration)</span>
		</div>
	</div>

	<div class="f4a-data">
		<ul>
			<li>
				<span>Exhibition</span>
				<?= $arResult['EVENT']['PROPS']['LANG_TITLE_' . $arResult['LANGUAGE']]['VALUE'] ?>
			</li>
			<li class="f4a-double">
				<span>Company name <i>(Customer)</i></span>
				<?= $arResult['USER']['WORK_COMPANY'] ?>
			</li>
			<li>
				<div class="col">
					<span>Pavilion №</span>
					<?= $arResult['FORM']['PAVILION'] ?>
				</div>
				<div class="col">
					<span>Hall №</span>
					<?= $arResult['FORM']['HALL'] ?>
				</div>
				<div class="col">
					<span>Stand № -</span>
					<?= $arResult['FORM']['STAND'] ?>
				</div>
			</li>
			<li>
				<span>Sizes of structure</span>
				<?= $arResult['FORM']['SIZE'] ?>
			</li>
			<li>
				<span>Material of structure</span>
				<?= $arResult['FORM']['MATERIAL'] ?>
			</li>
			<li>
				<span>Weight of the structure without any additional equipment, hanged up on it, kg </span>
				<?= $arResult['FORM']['WEIGHT'] ?>
			</li>
			<li class="f4a-double-p">
				<span class="width100p">List of the equipment, hanged up on the structure, please indicate weight of each item
					<i>(lightings, advertising equipment, decoration elements)</i>
				</span>
				<?= $arResult['FORM']['LIST'] ?>
			</li>
			<li>
				<span>The total weight of the structure with all hanged up equipment</span>
				<?= $arResult['FORM']['FULLWEIGHT'] ?>
			</li>
			<li>
				<span>Quantity of suspension points</span>
			</li>
			<li>
				<span>Weight per each suspension point, kg</span>
				<?= $arResult['FORM']['POINTWEIGHT'] ?>
			</li>
			<li>
				<span>Height of the suspension from the ground<em>(up to the top of structure)</em></span>
				<?= $arResult['FORM']['HEIGHT'] ?>
			</li>
			<li>
				<span><b>Person in charge of  the project of the structure</b></span>
				<?= $arResult['FORM']['PERSON_PROJECT'] ?>
			</li>
			<li>
				<span class="width100p"><b>Person in charge of mounting works <em>(Name, position in the company, mobile phone)</em></b></span>
				<?= $arResult['FORM']['PERSON_MOUNT'] ?>
			</li>
		</ul>
	</div>

	<div class="f4a-note">
		<p>
		    Customer (exhibitor or his stand-builder) responds for the quality and gathering (assembling) of the structure, for the stability of hanging points of  the  structure he wants to hang<br>
			Customer (exhibitor or his stand-builder) responds for the quality and operation of his own winches. It is forbidden to secure and hang up other elements of stand construction by means of already suspended construction.<br><br>
			Customer (exhibitor or his stand-builder) must not hang up any equipment on the suspended structure, if the structure is hung up already
		</p>
	</div>

	<p class="text-center f10"><b>With the order of the order for the suspension and removal of structures inside the pavilion is familiar, I agree.</b></p>

	<div class="f4a-bottom f4a-bottom_en">
		<div class="f4a-sign">
			<span>Legally binding signature and company stamp</span>
			<i>of the company (Customer)</i>
		</div>

		<div class="f4a-agreed">
			Согласовано<br>
			("APPROVED")<br>
			by ZAO "EXPOCONSTA"
		</div>
	</div>
</div>