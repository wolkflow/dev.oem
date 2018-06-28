<? use Wolk\Core\Helpers\Text as TextHelper ?>
<? use Wolk\Core\Helpers\Currency as CurrencyHelper ?>

<div class="krr-wrapper">
	<div class="krr-header">
		<table>
			<tbody>
				<tr>
					<td colspan="2" class="bb0 w370">
						КРАСНОДАРСКОЕ ОТДЕЛЕНИЕ N8619 ПАО СБЕРБАНК Г. КРАСНОДАР
					</td>
					<td class="w63">
						БИК
					</td>
					<td class="bb0 w224">
						040349602
					</td>
				</tr>
				<tr>
					<td colspan="2" class="bt0 va-b">
						<div class="small">Банк получателя</div>
					</td>
					<td>
						Сч. №
					</td>
					<td class="bt0">
						30101810100000000602
					</td>
				</tr>
				<tr>
					<td>
						<span class="mr10">ИНН</span>
						<span>2311215725</span>
					</td>
					<td>
						<span class="mr10">КПП</span>
						<span>231101001</span>
					</td>
					<td class="bb0">
						Сч. №
					</td>
					<td class="bt0">
						40702810230000013142
					</td>
				</tr>
				<tr>
					<td colspan="2" class="w370 bb0">
						<?= $arResult['USER']['WORK_COMPANY'] ?>
					</td>
					<td class="bt0 bb0">&nbsp;</td>
					<td class="bb0">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="2" class="w370 bt0 bb0">
						&nbsp;
					</td>
					<td class="bt0 bb0">&nbsp;</td>
					<td class="bt0 bb0">&nbsp;</td>
				</tr>
				<tr>
					<td colspan="2" class="w370 bt0">
						<div class="small">Получатель</div>
					</td>
					<td class="bt0">&nbsp;</td>
					<td class="bt0">&nbsp;</td>
				</tr>
			</tbody>
		</table>
	</div>

	<div class="krr-title">
		Счет на оплату №<?= $arResult['PROPS']['BILL']['VALUE'] ?> 
		от
		
		<? $ordertime = strtotime($arResult['ORDER']['DATE_INSERT']) ?>
		
		<?= date('d', $ordertime) ?>
		<?= TextHelper::i18nmonth(date('n', $ordertime), false, 'ru') ?> 
		<?= date('Y', $ordertime) ?> г.
	</div>
	
	<table class="no-border" style="border-top: 1px solid #000;">
		<tr>
			<td class="pr5" colspan="2" style="font-size: 4pt;">&nbsp;</td>
		</tr>
		<tr>
			<td class="pr5">Поставщик<br> (Исполнитель):</td>
			<td><b>ООО "ЮЖНАЯ ВЫСТАВОЧНАЯ КОМПАНИЯ", ИНН 2311215725, КПП 231101001, 350019, Краснодарский край, Краснодар г, Им Дзержинского ул, дом № 100, помещение 10, тел.: 8(861)2537987</b></td>
		</tr>
		<tr>
			<td class="pr5" colspan="2">&nbsp;</td>
		</tr>
		<tr>
			<td class="pr5">Покупатель<br> (Заказчик):</td>
			<td>
				<b>
					<?= $arResult['USER']['WORK_COMPANY'] ?>
					<?= implode('<br/>', [$arResult['USER']['WORK_STREET'], $arResult['USER']['UF_REQUISITES']]) ?>
				</b>
			</td>
		</tr>
		<tr>
			<td class="pr5" colspan="2">&nbsp;</td>
		</tr>
		<tr>
			<td class="pr5">Основание:</td>
			<td><b>Договор №ЮВК-ВКК-КО-2016-01 от 16.08.2016г.</b></td>
		</tr>
		<tr>
			<td class="pr5" colspan="2" style="font-size: 4pt;">&nbsp;</td>
		</tr>
	</table>


	<table class="krr-table">
		<thead>
			<tr>
				<th class="w33">№</th>
				<th class="w339">Товары (работы, услуги)</th>
				<th class="w54">Кол-во</th>
				<th class="w42">Ед.</th>
				<th class="w86">Цена</th>
				<th class="w100">Сумма</th>
			</tr>
		</thead>
		<tbody>
			<? $i = 1 ?>
			<? foreach ($arResult['BASKETS'] as $basket) { ?>
				<tr>
					<td class="ta-c">
						<?= $i++ ?>
					</td>
					<td>
						<?= $basket['NAME'] ?>
					</td>
					<td class="ta-r">
						<?= $basket['QUANTITY'] ?>
					</td>
					<td>шт</td>
					<td class="ta-r">
						<?= number_format($basket['SURCHARGE_PRICE'], 2, ',', ' ') ?>
					</td>
					<td class="ta-r">
						<?= number_format($basket['SURCHARGE_SUMMARY_PRICE'], 2, ',', ' ') ?>
					</td>
				</tr>
			<? } ?>
		</tbody>
	</table>

	<table class="no-border">
		<? if ($arResult['EVENT']['PROPS']['INCLUDE_VAT']['VALUE'] != 'Y') { ?>
			<tr>
				<td class="ta-r"><b>Итого:</b></td>
				<td class="ta-r w100">
					<b>
						<?= number_format($arResult['ORDER']['PRICE'] - $arResult['ORDER']['TAX_VALUE'], 2, ',', ' ') ?>
					</b>
				</td>
			</tr>
			<tr>
				<td class="ta-r"><b>В том числе НДС:</b></td>
				<td class="ta-r w100">
					<b>
						<?= number_format($arResult['ORDER']['TAX_VALUE'], 2, ',', ' ') ?>
					</b>
				</td>
			</tr>
			<tr>
				<td class="ta-r"><b>Всего к оплате:</b></td>
				<td class="ta-r w100">
					<b>
						<?= number_format($arResult['ORDER']['PRICE'], 2, ',', ' ') ?>
					</b>
				</td>
			</tr>
		<? } else { ?>
			<tr>
				<td class="ta-r"><b>НДС:</b></td>
				<td class="ta-r w100">
					<b>
						<?= number_format($arResult['ORDER']['UNTAX_VALUE'], 2, ',', ' ') ?>
					</b>
				</td>
			</tr>
			<tr>
				<td class="ta-r"><b>Всего к оплате:</b></td>
				<td class="ta-r w100">
					<b>
						<?= number_format($arResult['ORDER']['PRICE'], 2, ',', ' ') ?>
					</b>
				</td>
			</tr>
		<? } ?>
	</table>
	
	<p>
		Всего наименований <?= count($arResult['BASKETS']) ?>, 
		на сумму <?= number_format($arResult['ORDER']['PRICE'], 2, ',', ' ') ?> руб.<br>
		<b>
			<?= TextHelper::mb_ucfirst(TextHelper::num2str($arResult['ORDER']['PRICE'], true)) ?>
		</b>
	</p>
	<p>
		Оплатой данного счета Экспонент подтверждает Устроителю своё согласие на выполнение услуг, работ указанных в счете.<br>
		Работы, услуги считаются выполненными в полном объеме, надлежащим образом  и в надлежащий срок в момент поступления денежных средств на расчетный счет банка Устроителя.
	</p>
	<div class="sign">
		<div class="sign-stamp">
			<img src="<?= $this->getFolder() ?>/images/krr_stmp.png" />
		</div>
		<div class="sign-left">
			<div class="sign-title">Руководитель</div>
			<div class="sign-name">Бычин А. В.</div>
		</div>
		<div class="sign-right">
			<div class="sign-title">Бухгалтер</div>
			<div class="sign-name">Бычин А. В.</div>
		</div>
	</div>
</div>
