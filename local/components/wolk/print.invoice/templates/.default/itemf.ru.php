<? use Wolk\Core\Helpers\Text as TextHelper ?>

<script>
	$(document).ready(function(){
		var allheight = $('.ficontainer').height();
		if(allheight >= 880 && allheight < 1200) {
			$('.ficontainer').css({'padding-bottom': '400px'})
		}
	});
</script>


<div class="ficontainer cur-ru">
	<div class="filogo">
		<img src="<?= $this->getFolder() ?>/images/itemf_logo.png" />
	</div>

	<div class="finvoiceName">Общество с ограниченной ответственностью «АйТиИМФ Экспо»</div>

	<table class="fpaymentsTitle">
		<thead>
			<tr>
				<th>Организатор</th>
				<th>Участник</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>
					<p>
						<b>Общество с ограниченной ответственностью «АйТиИМФ Экспо» </b>
					</p>
					<p>
						Адрес(юр.): 107140, г. Москва, ул. Верхняя Красносельская д. 3, стр. 2, этаж А2, помещение I, комн. 1<br>
						Телефон: +7 (499) 750-08-18<br>
						Расчетный счет 40702810700000006578 в<br>
						АО "РАЙФФАЙЗЕНБАНК" г. Москва<br>
						К/с 30101810200000000700<br>
						БИК 044525700<br>
						ИНН: 7708326524 <br>КПП: 770801001
					</p>
				</td>
				<td class="botInn">
					<p><b><?= $arResult['USER']['WORK_COMPANY'] ?></b></p>
					<p>
						<?= implode('<br/>', array($arResult['USER']['WORK_STREET'], $arResult['USER']['UF_REQUISITES'])) ?>
					</p>
					<? /*
					<span class="botInnTypo"> ИНН: 7718990283 <br>КПП: 771801001</span>
					*/ ?>
				</td>
			</tr>
		</tbody>
	</table>

	<div class="finvoiceNum">
		Счет № <?= $arResult['PROPS']['BILL']['VALUE'] ?> от <?= date('d.m.y', $arResult['DATE']) ?>
	</div>

	<table class="finvoiceDetail">
		<tr class="thead">
			<td colspan="2">Наименование услуги </td>
			<td>Цена в руб.*</td>
			<td>Количество</td>
			<td class="finvoiceDetail-3">Сумма в руб.*</td>
		</tr>
			<? foreach ($arResult['BASKETS'] as $basket) { ?>
				<? if ($basket['SUMMARY_PRICE'] <= 0) continue ?>
				<tr class="fiItemRow">
					<td colspan="2">
						<?= $basket['NAME'] ?>
					</td>
					<td class="fiSumm">
						<?= number_format($basket['SURCHARGE_PRICE'], 2, ',', ' ') ?>
					</td>
					<td class="fiSumm">
						<?= $basket['QUANTITY'] ?>
					</td>
					<td class="fiSumm">
						<?= number_format($basket['SURCHARGE_SUMMARY_PRICE'], 2, ',', ' ') ?>
					</td>
				</tr>
			<? } ?>
			<? if ($arResult['EVENT']['PROPS']['INCLUDE_VAT']['VALUE'] != 'Y') { ?>
				<tr>
					<td class="unborder">&nbsp;</td>
					<td class="unborder">&nbsp;</td>
					<td colspan="2" class="finvoiceDetail-2 no-right-border">Итого без НДС:</td>
					<td class="finvoiceDetail-3"><?= number_format($arResult['ORDER']['PRICE'] - $arResult['ORDER']['TAX_VALUE'], 2, ',', ' ') ?></td>
				</tr>			
				<tr>
					<td class="unborder">&nbsp;</td>
					<td class="unborder">&nbsp;</td>
					<td colspan="2" class="finvoiceDetail-2 no-right-border">Ставка НДС:</td>
					<td class="finvoiceDetail-3">18%</td>
				</tr>
				<tr>
					<td class="unborder">&nbsp;</td>
					<td class="unborder">&nbsp;</td>
					<td colspan="2" class="finvoiceDetail-2 no-right-border">Сумма НДС:</td>
					<td class="finvoiceDetail-3"><?= number_format($arResult['ORDER']['TAX_VALUE'], 2, ',', ' ') ?></td>
				</tr>
				<tr>
					<td class="unborder">&nbsp;</td>
					<td class="unborder">&nbsp;</td>
					<td colspan="2" class="finvoiceDetail-2 no-right-border">Всего с НДС:</td>
					<td class="finvoiceDetail-3"><?= number_format($arResult['ORDER']['PRICE'], 2, ',', ' ') ?></td>
				</tr>
			<? } else { ?>		
				<tr>
					<td class="unborder">&nbsp;</td>
					<td class="unborder">&nbsp;</td>
					<td colspan="2" class="finvoiceDetail-2 no-right-border">Ставка НДС:</td>
					<td class="finvoiceDetail-3">18%</td>
				</tr>
				<tr>
					<td class="unborder">&nbsp;</td>
					<td class="unborder">&nbsp;</td>
					<td colspan="2" class="finvoiceDetail-2 no-right-border">Сумма НДС:</td>
					<td class="finvoiceDetail-3"><?= number_format($arResult['ORDER']['UNTAX_VALUE'], 2, ',', ' ') ?></td>
				</tr>
				<tr>
					<td class="unborder">&nbsp;</td>
					<td class="unborder">&nbsp;</td>
					<td colspan="2" class="finvoiceDetail-2 no-right-border">Всего с НДС:</td>
					<td class="finvoiceDetail-3"><?= number_format($arResult['ORDER']['PRICE'], 2, ',', ' ') ?></td>
				</tr>
			<? } ?>
	</table>

	<div class="finvoiceDetailSum">
		<p>Всего к оплате: <?= TextHelper::mb_ucfirst(TextHelper::num2str($arResult['ORDER']['PRICE'], true)) ?></p>
	</div>
	<div class="finvoiceDetailLeg">
		<p><b>*Оплата производится в рублях по безналичному расчету. При оплате указывайте номер счета.</b></p>
	</div>
	<div class="finvoiceDeadline">Счет действителен в течение 14 календарных дней.</div>

	<div class="bottomSignature">
		<div class="bottomSignatureLeft">
			Генеральный директор<br> Александров С.В.
			<div class="bottomSignatureLeg">
				<span>Примечание:</span><br>
				Без печати недействительно.<br>
				Первый экземпляр (оригинал) – участник<br>
				Второй экземпляр (копия) – организатор
				<div class="finvoiceStamp"><img src="<?= $this->getFolder() ?>/images/stamp_inv.png" /></div>
			</div>
		</div>
		<div class="bottomSignatureRight">
			Главный бухгалтер <br>Самсонова А.В.
			<div class="finvoiceSignature">
				<img src="<?= $this->getFolder() ?>/images/itemf_signature.png" />
			</div>
		</div>
		<div class="clear"></div>
	</div>
</div>
