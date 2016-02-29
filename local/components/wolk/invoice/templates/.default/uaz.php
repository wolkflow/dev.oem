<? use Wolk\Core\Helpers\Text as TextHelper ?>

<div class="invoice">
	<div class="invoiceMessage invoiceHead">
		<p>Внимание! Оплата данного счета означает согласие с условиями поставки товара.</p>
		<p>Уведомление об оплате  обязательно, в противном случае не гарантируется наличие товара на складе.</p>
		<p>Товар отпускается по факту прихода денег на р/с Поставщика, самовывозом, при наличии доверенности и паспорта.</p>
	</div>
	
	<table class="invoiceTable">
		<tr>
			<td colspan="4" rowspan="2">
				<span class="bankName">КБ "МОСКОММЕРЦБАНК" (АО) Г. МОСКВА </span>
				<span class="thisHolder">Банк получателя</span>
			</td>
			<td>БИК:</td>
			<td>044599951</td>
		</tr>
		<tr>
			<td>Сч.№</td>
			<td>30101810500000000951</td>
		</tr>
		<tr>
			<td>ИНН</td>
			<td>7704619858</td>
			<td>КПП</td>
			<td>771401001</td>
			<td rowspan="2">Сч.№</td>
			<td rowspan="2">40702810300000001515</td>
		</tr>
		<tr>
			<td colspan="4" rowspan="2">
				<span class="bankName">AO "АйТиИМФ Экспо"</span>
				<span class="thisHolder">Получатель</span>
			</td>
		</tr>
	</table>

	<div class="invoiceTitle">
		Счет на оплату № <?= $arResult['PROPS']['BILL']['VALUE'] ?> 
		от <?= date('d') ?> <?= TextHelper::i18nmonth(date('n'), false) ?> <?= date('Y') ?>
	</div>

	<table class="invoiceDetails">
		<tr>
			<td>Поставщик:</td>
			<td>Акционерное общество "АйТиИМФ Экспо", ИНН 7704619858, КПП 771401001, 125167, Москва г, Ленинградский пр-кт, дом № 39, строение 80, тел.: (495) 649-87-75</td>
		</tr>
		<tr>
			<td>Покупатель:</td>
			<td><?= $arResult['USER']['UF_REQUISITES'] ?> </td>
		</tr>
	</table>

	<table class="invoiceItems__list">
		<thead>
			<tr>
				<th>Наименование</th>
				<th>Количество</th>
				<th>Ед.Изм.</th>
				<th>Цена</th>
				<th>Сумма</th>
			</tr>
		</thead>
		<tbody>
			<? foreach ($arResult['BASKETS'] as $basket) { ?>
				<tr>
					<td><?= $basket['NAME'] ?></td>
					<td><?= $basket['QUANTITY'] ?></td>
					<td></td>
					<td><?= number_format($basket['PRICE'], 2, ',', ' ') ?></td>
					<td><?= number_format($basket['SUMMARY_PRICE'], 2, ',', ' ') ?></td>
				</tr>
			<? } ?>
			<tr class="invoiceTotal">
				<td colspan="4">
					<p>Всего без НДС:</p>
					<p>НДС (18%):</p>
					<p>ВСЕГО С НДС (18%):</p>
				</td>
				<td colspan="2">
					<p><?= number_format($arResult['ORDER']['PRICE'] - $arResult['ORDER']['TAX_VALUE'], 2, ',', ' ') ?></p>
					<p><?= number_format($arResult['ORDER']['TAX_VALUE'], 2, ',', ' ') ?></p>
					<p><?= number_format($arResult['ORDER']['PRICE'], 2, ',', ' ') ?></p>
				</td>
			</tr>
			<tr>
				<td colspan="6">
					<?= TextHelper::mb_ucfirst(TextHelper::num2str($arResult['ORDER']['PRICE'])) ?>
					<?= TextHelper::decofnum($arResult['ORDER']['PRICE'], ['рубль', 'рубля', 'рублей'], false) ?>
				</td>
			</tr>
		</tbody>
	</table>

	<div class="invoiceSignature">
		<p>Руководитель <span></span></p>
		<p>Бухгалтер <span></span></p>
	</div>
</div>

