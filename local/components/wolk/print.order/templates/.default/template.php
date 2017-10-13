<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>

<div class="order-print">
	
	<div>
		<h1><?= $arResult['EVENT']['NAME'] ?></h1>
		<img src="<?= $arResult['EVENT']['LOGO'] ?>" />
	</div>
	<hr/>
	
	<div>
		<h2>Заказ №<?= $arResult['ORDER']['ID'] ?> от <?= date('d.m.Y', strtotime($arResult['ORDER']['DATE_INSERT'])) ?></h2>
	</div>
	<hr/>
	
	<div class="order">
		<h3>Компания</h3>
		<table width="300px">
			<tbody>
				<tr>
					<td>Название</td>
					<td><?= $arResult['USER']['WORK_COMPANY'] ?></td>
				</tr>
				<tr>
					<td>Контактное лицо</td>
					<td><?= implode(' ', [$arResult['USER']['NAME'], $arResult['USER']['LAST_NAME']]) ?></td>
				</tr>
				<tr>
					<td>Телефон</td>
					<td><?= $arResult['USER']['PERSONAL_PHONE'] ?></td>
				</tr>
				<tr>
					<td>E-mail</td>
					<td><?= $arResult['USER']['EMAIL'] ?></td>
				</tr>
			</tbody>
		</table>
	</div>
	<hr/>
	
	<div class="order">
		<h3>Параметры стенда</h3>
		<table width="300px">
			<tbody>
				<tr>
					<td>Номер стенда</td>
					<td><?= $arResult['PROPS']['STANDNUM']['VALUE'] ?></td>
				</tr>
				<tr>
					<td>Ширина стенда (м)</td>
					<td><?= $arResult['PROPS']['WIDTH']['VALUE'] ?></td>
				</tr>
				<tr>
					<td>Глубина стенда (м)</td>
					<td><?= $arResult['PROPS']['DEPTH']['VALUE'] ?></td>
				</tr>
				<tr>
					<td>Полщадь стенда (м<sup>2</sup>)</td>
					<td><?= ($arResult['PROPS']['WIDTH']['VALUE'] * $arResult['PROPS']['DEPTH']['VALUE']) ?></td>
				</tr>
			</tbody>
		</table>
	</div>
	
	<div class="baskets">
		<table class="invoiceItems__table">
			<thead>
				<tr>
					<th>Изображение</th>
					<th>Наименование</th>
					<th>Цена</th>
					<th>Количество</th>
					<th>Сумма</th>
				</tr>
			</thead>
			<tbody>
				<? foreach ($arResult['BASKETS'] as $basket) { ?>
					<? if ($basket['PRICE'] <= 0) continue ?>
					<tr>
						<td>
							 <? if (!empty($basket['ITEM']['PREVIEW_PICTURE'])) { ?>
								<img src="<?= $basket['ITEM']['IMAGE'] ?>" width="60" height="60" />
							<? } else { ?>
								<div class="no-photo">Нет картинки</div>
							<? } ?>
						</td>
						<td><?= $basket['NAME'] ?></td>
						<td><?= number_format($basket['PRICE'], 2, ',', ' ') ?></td>
						<td><?= intval($basket['QUANTITY']) ?></td>
						<td>
							<?= number_format($basket['SUMMARY_PRICE'], 2, ',', ' ') ?>
						</td>
					</tr>
				<? } ?>
			</tbody>
			<tfoot>
				<tr class="invoiceItems__table-amount">
					<td colspan="3"></td>
					<td class="text-left">Всего без НДС:</td>
					<td><?= number_format($arResult['PRICES']['BASKET'], 2, ',', ' ') ?></td>
				</tr>
				<tr class="invoiceItems__table-amount">
					<td colspan="3"></td>
					<td class="text-left">НДС (18%):</td>
					<td><?= number_format($arResult['PRICES']['VAT'], 2, ',', ' ') ?></td>
				</tr>
				<tr class="invoiceItems__table-total">
					<td colspan="3"></td>
					<td class="text-left">ВСЕГО С НДС:</td>
					<td>
						<?= number_format($arResult['PRICES']['FINAL'], 2, ',', ' ') ?>
					</td>
				</tr>
			</tfoot>
		</table>
	</div>
	
	<div>
		<h2>Данные заказа</h2>
		<? foreach ($arResult['BASKETS'] as $basket) { ?>
			<? if ($basket['ITEM']['CODE'] == 'FASCIA_NAME') { ?>
				<li>
					Надпись на фриз
					(<i><?= $basket['PROPS']['FASCIA_COLOR']['VALUE'] ?> &ndash; <?= $arResult['COLORS'][$basket['PROPS']['FASCIA_COLOR']['VALUE']]['UF_NUM'] ?></i>):
					&laquo;<b><?= $basket['PROPS']['FASCIA_TEXT']['VALUE'] ?></b>&raquo;
				</li>
			<? } ?>
		<? } ?>
	</div>
	
	<div class="sketch">
		<h2>Расположение элементов на стенде</h2>
		<img src="<?= CFile::GetPath($arResult['PROPS']['SKETCH_FILE']['VALUE_ORIG']) ?>" />
	</div>
</div>

<script type="text/javascript">
    $(document).ready(function () {
		setTimeout(function() {window.print();}, 1000);
    });
</script>
