<? use Wolk\Core\Helpers\Text as TextHelper ?>

<div class="ficontainer cur-ru">
	<div class="filogo">
		<img src="<?= $this->getFolder() ?>/images/itemf_logo.png" alt="">
	</div>

	<div class="finvoiceName">Акционерное Общество «АйТиИМФ Экспо»</div>

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
						<b>Акционерное Общество «АйТиИМФ Экспо» </b>
					</p>
					Адрес(юр.): Москва, Ленинградский проспект, д.39, стр.80<br>
					Телефон: +7 (499) 750-08-18<br>
					Расчетный счет 40702810700000006578 в<br>
					АО "РАЙФФАЙЗЕНБАНК" г. Москва<br>
					К/с 30101810200000000700<br>
					БИК 044525700<br>
					ИНН: 7704619858 <br>КПП: 771401001
				</td>
				<td class="botInn">
					<p><b>ООО "Брембо Руссия"</b></p>
					<p>Адрес(юр.) 107589, г. Москва, ул. Красноярская, 17, пом. 22 <br>Телефон: (903) 568 39 00</p>
					<span class="botInnTypo"> ИНН: 7718990283 <br>КПП: 771801001</span>
				</td>
			</tr>
		</tbody>
	</table>

	<div class="finvoiceNum">Счет № <?= $arResult['PROPS']['BILL']['VALUE'] ?> от <?= date('d.m.y') ?></div>

	<table class="finvoiceDetail">
		<thead>
			<tr>
				<th colspan="2">Наименование услуги </th>
				<th class="finvoiceDetail-3">Сумма в руб.*</th>
			</tr>
		</thead>
		<tbody>
			<tr class="fiItemRow">
				<td colspan="2">Предоплата за аренду выставочной площади и организацию участия в выставке "MIMS Automechanika Moscow 2016" (22.08.16 - 25.08.16) в соответствии с договором № 2016020100 от 02.02.16 </td>
				<td class="fiSumm">369 420,00</td>
			</tr>
			<tr>
				<td class="unborder">&nbsp;</td>
				<td class="finvoiceDetail-2">Ставка НДС:</td>
				<td class="finvoiceDetail-3">18%</td>
			</tr>
			<tr>
				<td class="unborder">&nbsp;</td>
				<td class="finvoiceDetail-2">Сумма НДС:</td>
				<td class="finvoiceDetail-3">66 495,60</td>
			</tr>
			<tr>
				<td class="unborder">&nbsp;</td>
				<td class="finvoiceDetail-2">Всего с НДС:</td>
				<td class="finvoiceDetail-3">435 915,60</td>
			</tr>
		</tbody>
	</table>

	<div class="finvoiceDetailSum">
		<p>Всего к оплате: Четыреста тридцать пять тысяч девятьсот пятнадцать рублей 60 копеек</p>
	</div>
	<div class="finvoiceDetailLeg">
		<p><b>*Оплата производится в рублях по безналичному расчету. При оплате указывайте номер счета.</b></p>
	</div>
	<div class="finvoiceDeadline">Счет действителен в течение 14 календарных дней.</div>

	<div class="bottomSignature">
		<div class="bottomSignatureLeft">
			Генеральный директор<br> Александров С.В
			<div class="bottomSignatureLeg">
				<span>Примечание:</span><br>
				Без печати недействительно.<br>
				Первый экземпляр (оригинал) – участник<br>
				Второй экземпляр (копия) – организатор
			</div>
			<div class="bottomSignatureCode"><img src="<?= $this->getFolder() ?>/images/code2.png" alt=""></div>
			<div class="finvoiceStamp"><img src="<?= $this->getFolder() ?>/images/itemf_stamp.png" alt=""></div>
		</div>
		<div class="bottomSignatureRight">
			Главный бухгалтер <br>Самсонова А.В.
			<div class="finvoiceSignature"><img src="<?= $this->getFolder() ?>/images/itemf_signature.png" alt=""></div>
		</div>
		<div class="clear"></div>
	</div>
</div>
