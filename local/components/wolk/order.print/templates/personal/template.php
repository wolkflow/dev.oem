<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>

<? use Bitrix\Main\Localization\Loc; ?>

<? $lang = strtoupper(\Bitrix\Main\Context::getCurrent()->getLanguage()) ?>

<div class="personal-order-print">
	<h1>Заказ №<?= $arResult['ORDER']['ID'] ?> от <?= date('d.m.Y', strtotime($arResult['ORDER']['DATE_INSERT'])) ?></h1>

	<section>
		<h2>Информация</h2>
		<div>
			Выставка <b><?= $arResult['EVENT']['PROPS']['LANG_TITLE_' . $lang]['VALUE'] ?></b>
		</div>
		<div>
			Место проведения: <b><?= $arResult['EVENT']['PROPS']['LANG_LOCATION_RU']['VALUE'] ?></b>
		</div>
		<div>
			Компания <b><?= $arResult['USER']['WORK_COMPANY'] ?></b>
		</div>
		<? if (!empty($arResult['STAND'])) { ?>
			<div>
				Стенд
				<b>
					<?= $arResult['STAND']['NAME'] ?> 
					<?= $arResult['ORDER']['PROPS']['WIDTH']['VALUE'] ?>&times;<?= $arResult['ORDER']['PROPS']['DEPTH']['VALUE'] ?>
				</b>
			</div>
		<? } ?>
		<div>
			Павильон №<b><?= $arResult['ORDER']['PROPS']['PAVILION']['VALUE'] ?></b>
		</div>
		<div>
			Стенд №<b><?= $arResult['ORDER']['PROPS']['STANDNUM']['VALUE'] ?></b>
		</div>
	</section>

	<section>
		<h2>Оборудование и услуги</h2>

		<table width="100%" border="1" cellpadding="5">
			<thead>
				<tr>
					<th>Наименование</th>
					<th>Количество</th>
					<th>Цена</th>
					<th>Стоимость</th>
				</tr>
			</thead>
			<tbody>
				<? foreach ($arResult['BASKETS'] as $basket) { ?>
					<tr>
						<td>
							<?= $basket['NAME'] ?>
						</td>
						<td>
							<?= $basket['QUANTITY'] ?>
						</td>
						<td>
							<?= FormatCurrency($basket['PRICE'], $arResult['RATE_CURRENCY']) ?>
						</td>
						<td>
							<?= FormatCurrency($basket['PRICE'] * $basket['QUANTITY'], $arResult['RATE_CURRENCY']) ?>
						</td>
					</tr>
				<? } ?>
			</tbody>
		</table>
		
		<br/>
		
		<table class="summary" width="100%" cellpadding="5">
			<? if (!empty($arResult['PRICES']['SURCHARGE'])) { ?>
				<tr>
					<td align="right">
						Наценка:
						<b><?= FormatCurrency($arResult['PRICES']['SURCHARGE'], $arResult['RATE_CURRENCY']) ?></b>
						(<?= $arResult['ORDER']['PROPS']['SURCHARGE']['VALUE'] ?>%)
					</td>
				<tr>
			<? } ?>
			<tr>
				<td align="right">
					Всего к оплате:
					<b><?= FormatCurrency($arResult['PRICES']['TOTAL'], $arResult['RATE_CURRENCY']) ?></b>
				</td>
			<tr>
			</tr>
				<td align="right">
					В том числе НДС:
					<b><?= FormatCurrency($arResult['PRICES']['TAX'], $arResult['RATE_CURRENCY']) ?></b>
				</td>
			</tr>
		</table>
	</section>
	
	<? if (!empty($arResult['ORDER']['PROPS']['SKETCH_FILE']['VALUE'])) { ?>
		<section>
			<h2>Схема стенда</h2>
		
			<img class="sketch" src="<?= CFile::getPath($arResult['ORDER']['PROPS']['SKETCH_FILE']['VALUE']) ?>" />
		</section>
	<? } ?>
</div>

<script type="text/javascript">
    $(document).ready(function () {
		setTimeout(function() {window.print();}, 1000);
    });
</script>