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
			<span>Дата</span>
			<?= date('d.m.Y', strtotime($arResult['ORDER']['DATE_INSERT'])) ?>
		</div>
		<div class="f4a-company">
			<div class="f4a-company_name">
				<?= $arResult['USER']['WORK_COMPANY'] ?>
			</div>
			Информация о конструкции для навески внутри павильона
			<span>(С обязательным предъявлением администрации павильона)</span>
		</div>
	</div>

	<div class="f4a-data">
		<ul>
			<li>
				<span>Выставка</span>
				<?= $arResult['EVENT']['PROPS']['LANG_TITLE_' . $arResult['LANGUAGE']]['VALUE'] ?>
			</li>
			<li class="f4a-double">
				<span>Название фирмы <i>(заказчик)</i></span>
				<?= $arResult['FORM']['COMPANY'] ?>
			</li>
			<li>
				<div class="col">
					<span>Павильон №</span>
					<?= $arResult['FORM']['PAVILION'] ?>
				</div>
				<div class="col">
					<span>Зал №</span>
					<?= $arResult['FORM']['HALL'] ?>
				</div>
				<div class="col">
					<span>Стенд № -</span>
					<?= $arResult['FORM']['STAND'] ?>
				</div>
			</li>
			<li>
				<span>Габариты конструкции</span>
				<?= $arResult['FORM']['SIZE'] ?>
			</li>
			<li>
				<span>Материалы</span>
				<?= $arResult['FORM']['MATERIAL'] ?>
			</li>
			<li>
				<span>Вес конструкции</span>
			</li>
			<li class="f4a-double-p">
				<span class="width100p">Перечень и общий вес навешиваемого на конструкцию оборудования
					<i>(осветительной техники, рекламных носителей, декоративной облицовки)</i>
				</span>
				<?= $arResult['FORM']['LIST'] ?>
			</li>
			<li>
				<span>Общий вес снаряженной конструкции</span>
				<?= $arResult['FORM']['FULLWEIGHT'] ?>
			</li>
			<li>
				<span>Расчетное количество точек подвески</span>
			</li>
			<li>
				<span>Расчетная нагрузка на каждую точку подвески </span>
				<?= $arResult['FORM']['POINTWEIGHT'] ?>
			</li>
			<li>
				<span>Расчетная высота подвески от пола <em>(по верхней точке конструкции)</em></span>
				<?= $arResult['FORM']['PERSON_PROJECT'] ?>
			</li>
			<li>
				<span><b>Ответственный за проектирование </b></span>
				<?= $arResult['FORM']['PERSON_MOUNT'] ?>
			</li>
			<li>
				<span class="width100p"><b>Ответственный за монтаж и технику безопасности <em>(Фамилия, должность, телефон)</em></b></span>
			</li>
		</ul>
	</div>

	<div class="f4a-note">
		<p>Ответственность за сборку и прочность конструкции, а также за организацию точек крепления на конструкции несёт Фирма заказчик.<br>
			Ответственность за качество и эксплуатацию собственных лебедок несет Фирма заказчик.<br>
			Навеска дополнительного оборудования (осветительной техники, рекламных носителей, декоративной облицовки) на подвешенную конструкцию ЗАПРЕЩЕНА!  Использование уже подвешенной конструкции для организации страховки других элементов экспозиции ЗАПРЕЩЕНА!<br>
			Стоимость заказа на подвес,  полученного во время монтажа выставки,  увеличивается на 100%</p>
	</div>

	<p class="text-center f10"><b>C порядком выполнения заказа по подвесу и снятию конструкций внутри павильона ознакомлен, согласен.</b></p>

	<div class="f4a-bottom">
		<div class="f4a-sign">
			<span>Подпись руководителя</span>
			<i>предприятия (заказчика)</i>
		</div>

		<div class="f4a-mp">
			М.П.
		</div>

		<div class="f4a-agreed">
			Согласовано<br>
			ЗАО «Экспоконста»
		</div>
	</div>
</div>