<? use Wolk\Core\Helpers\Text as TextHelper ?>
<? use Wolk\Core\Helpers\Currency as CurrencyHelper ?>

<div class="bvk-wrapper">
	<div class="bvk-head">
		<div class="mb10">
			<span class="inline-title">СЧЕТ №</span>
			<span class="inline-line mw35">
				<?= $arResult['PROPS']['BILL']['VALUE'] ?> 
			</span>
			<span class="inline-title">-ЭУ-18 от</span>
			<span class="inline-line mw60">
				<? $ordertime = strtotime($arResult['ORDER']['DATE_INSERT']) ?>
				
				<?= date('d', $ordertime) ?>
				<?= TextHelper::i18nmonth(date('n', $ordertime), false, 'ru') ?> 
			</span>
			<span class="inline-title"><?= date('Y', $ordertime) ?> г. к договору на участие в выставке №</span>
			<span class="inline-line mw35">
				<!-- text номер-->
			</span>
			<span class="inline-title">/ЭУ-18 от </span>
		</div>
		<div class="ta-c">
			<span class="inline-line mw70">
				<? $ordertime = strtotime($arResult['ORDER']['DATE_INSERT']) ?>
				
				<?= date('d', $ordertime) ?>
				<?= TextHelper::i18nmonth(date('n', $ordertime), false, 'ru') ?> 
			</span>
			<span class="inline-title"><?= date('Y', $ordertime) ?> г.</span>
		</div>
	</div>

	<div class="bvk-data">
		<p>Продавец: ООО "Башкирская выставочная компания"</p>
		<p>Адрес: 450080, Республика Башкортостан, Уфа, ул. Менделеева, 158</p>
		<p>Грузоотправитель и его адрес: ООО "Башкирская выставочная компания", 450080, Республика Башкортостан, Уфа, ул. Менделеева, 158</p>
	</div>

	<div class="bvk-message">
		ВНИМАНИЕ! <br> ИЗМЕНИЛИСЬ БАНКОВСКИЕ РЕКВИЗИТЫ!
	</div>

	<div class="bvk-header">
		<p class="ta-c">Образец заполнения платежного поручения</p>
		<table>
			<tbody>
				<tr>
					<td class="bb0 w185">
						ИНН 0272012500
					</td>
					<td class="bb0 w185">
						КПП 027801001
					</td>
					<td class="bb0 w63">
						&nbsp;
					</td>
					<td class="bb0 w224">
						&nbsp;
					</td>
				</tr>
				<tr>
					<td class="bb0 w370" colspan="2">
						Получатель
					</td>
					<td class="bb0 bt0 w63">
						&nbsp;
					</td>
					<td class="bb0 bt0 w224">
						&nbsp;
					</td>
				</tr>
				<tr>
					<td class="bt0 w370" colspan="2">
						ООО "Башкирская выставочная компания"
					</td>
					<td class="bt0 w63 ta-c">
						Сч. №
					</td>
					<td class="bt0 w224">
						40702810106000008747
					</td>
				</tr>
				<tr>
					<td class="bb0 w370" colspan="2">
						Банк получателя
					</td>
					<td class="bt0 w63 ta-c">
						БИК
					</td>
					<td class="bb0 w224">
						048073601
					</td>
				</tr>
				<tr>
					<td class="bt0 w370" colspan="2">
						БАШКИРСКОЕ ОТДЕЛЕНИЕ №8598 ПАО СБЕРБАНК Г. УФА
					</td>
					<td class="bt0 w63 ta-c">
						Кор/сч. №
					</td>
					<td class="bt0 w224">
						30101810300000000601
					</td>
				</tr>
			</tbody>
		</table>
	</div>


	<div class="bvk-buyer">
		<p>
			<span>Покупатель:</span>
			<span><?= $arResult['USER']['WORK_COMPANY'] ?></span>
		</p>
		<p>
			<span>Адрес:</span>
			<span><?= $arResult['USER']['WORK_STREET'] ?></span>
		</p>

		<div class="bvk-buyer__twice">
			<p>
				<span>ИНН покупателя</span>
				<span><!-- текст --></span>
			</p>
			<p>
				<span>КПП</span>
				<span><!-- текст --></span>
			</p>
			<!-- <?= $arResult['USER']['UF_REQUISITES'] ?> -->
		</div>
	</div>



	<table class="bvk-table">
		<thead>
			<tr>
				<th class="w33">№</th>
				<th class="w339">Наименование<br> товара</th>
				<th class="w54">Ед. Изм.</th>
				<th class="w42">Кол-<br>во</th>
				<th class="w86">Цена (руб.)</th>
				<th class="w100">Сумма</th>
			</tr>
		</thead>
		<tbody>
			<? $i = 1 ?>
			<? foreach ($arResult['BASKETS'] as $basket) { ?>
				<tr>
					<td class="ta-r">
						<?= $i++ ?>
					</td>
					<td>
						<?= $basket['NAME'] ?>
					</td>
					<td class="ta-c">ед.</td>
					<td class="ta-r">
						<?= $basket['QUANTITY'] ?>
					</td>
					<td class="ta-r">
						<?= number_format($basket['SURCHARGE_PRICE'], 2, ',', ' ') ?>
					</td>
					<td class="ta-r">
						<?= number_format($basket['SURCHARGE_SUMMARY_PRICE'], 2, ',', ' ') ?>
					</td>
				</tr>
			<? } ?>
			<tr>
				<td class="ta-r bb0" colspan="5"><b>Итого:</b></td>
				<td class="ta-r">
					<b><?= number_format($arResult['ORDER']['PRICE'], 2, ',', ' ') ?></b>
				</td>
			</tr>
			<tr>
				<td class="ta-r bt0" colspan="5"><b>Всего к оплате:</b></td>
				<td class="ta-r">
					<b><?= number_format($arResult['ORDER']['PRICE'], 2, ',', ' ') ?></b>
				</td>
			</tr>
		</tbody>
	</table>


	<p>
		<span class="inline-title">Всего на сумму:</span>
		<span class="inline-line mw120">
			<?= TextHelper::mb_ucfirst(TextHelper::num2str($arResult['ORDER']['PRICE'], true)) ?>
		</span>
		<span class="inline-title">НДС не облагается</span>
	</p>


	<div class="bvk-signs">
		<div class="bvk-stamp"><img src="<?= $this->getFolder() ?>/images/bvk-stamp.png" /></div>
		<div class="bvk-sign"><img src="<?= $this->getFolder() ?>/images/bvk-sign.png" /></div>
		<div class="ta-c fw-700">М.П.</div>

		<div class="bvk-signs__block bvk-whoin">
			<span class="inline-title">Получил:</span>
			<span class="inline-line mw120"><!-- text  --></span>
			<div class="bvk-who__info">(Подпись покупателя или полномочного представителя покупателя)</div>
			<div class="bvk-note">
				<p class="fw-700">ПРИМЕЧАНИЕ: Без печати не действительно</p>
				<p>Счет действителен в течение 10 (десяти) дней</p>
			</div>
		</div>
		<div class="bvk-signs__block bvk-whoout">
			<span class="inline-title">Выдал:</span>
			<span class="inline-line mw120"><!-- text  --></span>
			<div class="bvk-who__info">(Подпись ответственного лица от поставщика)</div>
			<div class="bvk-who__info">Первый экземпляр (оригинал) - покупатель</div>
			<div class="bvk-who__info">Второй экземпляр (копия) - поставщик</div>
		</div>

	</div>
</div>