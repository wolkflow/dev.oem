<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>

<? use Bitrix\Main\Localization\Loc; ?>

<div class="f4a-wrapper">
	<div class="f4a-header">
		<div class="f4a-date f4a-string">
			<span>Дата</span>
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
			</li>
			<li class="f4a-double">
				<span>Название фирмы <i>(заказчик)</i></span>
			</li>
			<li>
				<div class="col">
					<span>Павильон №</span>
				</div>
				<div class="col">
					<span>Зал №</span>
				</div>
				<div class="col">
					<span>Стенд № -</span>
				</div>
			</li>
			<li>
				<span>Габариты конструкции</span>
			</li>
			<li>
				<span>Материалы</span>
			</li>
			<li>
				<span>Вес конструкции</span>
			</li>
			<li class="f4a-double-p">
				<span class="width100p">Перечень и общий вес навешиваемого на конструкцию оборудования
					<i>(осветительной техники, рекламных носителей, декоративной облицовки)</i>
				</span>
			</li>
			<li>
				<span>Общий вес снаряженной конструкции</span>
			</li>
			<li>
				<span>Расчетное количество точек подвески</span>
			</li>
			<li>
				<span>Расчетная нагрузка на каждую точку подвески </span>
			</li>
			<li>
				<span>Расчетная высота подвески от пола <em>(по верхней точке конструкции)</em></span>
			</li>
			<li>
				<span><b>Ответственный за проектирование </b></span>
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