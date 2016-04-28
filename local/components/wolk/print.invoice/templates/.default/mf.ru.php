<? use Wolk\Core\Helpers\Text as TextHelper ?>

<div class="invoice invoice2">
	<div class="invoiceHeader">
		<div class="invoiceHeader__left">
			<div class="invoiceHeaderTitle">Счет</div>
			<div class="invoiceHeaderDetail">
				<p>ООО "Мессе Франкфург РУС",</p>
				<p>125167, Москва, Ленинградский пр-т, 39, стр. 80</p>
				<p>Адрес для корреспонденции</p>
				<p>Тел. 649-87-75, факс 649-87-85</p>
			</div>
		</div>
		<div class="invoiceHeader__right">
			<img src="<?= $this->getFolder() ?>/images/invoiceLogo.png" />
		</div>
		<div class="clear"></div>
	</div>

	<div class="invoiceText">
		<p><?= $arResult['USER']['WORK_COMPANY'] ?></p>
		<p><?= $arResult['USER']['WORK_STREET'] ?></p>
	</div>
	<div class="invoiceTextRight">
		<p class="invoiceTb"><b>В платежном документе<br> обязательна ссылка на <br>№ и дату счета, № клиента</b></p>
		<p>Дата: <span><?= date('d.m.Y', $arResult['DATE']) ?></span></p>
		<p>Номер клиента: <span><?= $arResult['USER']['UF_CLIENT_NUMBER'] ?></span></p>
		<p>Номер счета: <span><?= $arResult['PROPS']['BILL']['VALUE'] ?></span></p>
	</div>

	<div class="invoiceParams">
		<p><span class="toleft">Выставка:</span> <?= $arResult['EVENT']['NAME'] ?></p>
		<p><span class="toleft">Место проведения:</span> <span class="red"><?= $arResult['EVENT']['PROPS']['LANG_LOCATION_RU']['VALUE'] ?></span></p>
		<? /*
		<p><span class="toleft">Дата:</span> <span class="red"><?= date('d.m.Y', strtotime($arResult['EVENT']['ACTIVE_FROM'])) ?> – <?= date('d.m.Y', strtotime($arResult['EVENT']['ACTIVE_TO'])) ?></span></p>
		*/ ?>
	</div>

	<div class="invoiceParams2">
		<div class="invoiceParams2__left">
			<p><span class="toleft">Аренда стенда:</span> <?= $arResult['PROPS']['standNum']['VALUE'] ?></p>
			<p><span class="toleft">Ширина, м:</span> <?= $arResult['PROPS']['width']['VALUE'] ?></p>
			<p><span class="toleft">Глубина:</span> <?= $arResult['PROPS']['depth']['VALUE'] ?></p>
		</div>
		<div class="clear"></div>
	</div>

	<table class="invoiceItems__table">
		<thead>
			<tr>
				<th>Наименование</th>
				<th>Цена</th>
				<th>Кол-во</th>
				<th>Сумма</th>
			</tr>
		</thead>
		<tbody>
			<? foreach ($arResult['BASKETS'] as $basket) { ?>
				<? if ($basket['SUMMARY_PRICE'] <= 0) continue ?>
				<tr>
					<td><?= $basket['NAME'] ?></td>
					<td><?= $basket['SURCHARGE_PRICE'] ?></td>
					<td><?= $basket['QUANTITY'] ?></td>
					<td><?= $basket['SURCHARGE_SUMMARY_PRICE'] ?></td>
				</tr>
			<? } ?>

			<tr class="invoiceItems__table-amount">
				<td colspan="2"></td>
				<td class="text-left">Всего без НДС:</td>
				<td><?= number_format($arResult['ORDER']['PRICE'] - $arResult['ORDER']['TAX_VALUE'], 2, ',', ' ') ?></td>
			</tr>
			<tr class="invoiceItems__table-amount">
				<td colspan="2"></td>
				<td class="text-left">НДС (18%):</td>
				<td><?= number_format($arResult['ORDER']['TAX_VALUE'], 2, ',', ' ') ?></td>
			</tr>
			<tr class="invoiceItems__table-total">
				<td colspan="2"></td>
				<td class="text-left">ВСЕГО С НДС:</td>
				<td><?= number_format($arResult['ORDER']['PRICE'], 2, ',', ' ') ?></td>
			</tr>
		</tbody>
	</table>
	

	<div class="invoiceAfter2">
		<div class="invoiceAfterLeft">
			<div class="invoiceStamp2"></div>
			<p class="attention"><b>Счет действительный в течении 5-ти банковских дней</b></p>
			<p>100% настоящего счета подлежит оплате по получению</p>
			<div class="regNum">
				<p>№ регистрации в Московской Регистрационной<br> палате 130.022</p>
				<p>ИНН 7705365187 / КПП / 771401001</p>
			</div>
			<p>ООО "Мессе Франкфург РУС"</p>
		</div>
		<div class="invoiceAfterRight">
			<p class="comname">ООО "Мессе Франкфург РУС"</p>
			<p>40702-810-1-0000-1-401-484</p>
			<p>АО "Райффайзенбанк" Москва</p>
			<p>129090, Москва, ул. Троицкая, 17/1</p>
			<p>БИК 044525700</p>
			<p>к/с 30101810200000000700</p>
		</div>
		<div class="clear"></div>
	</div>
</div>