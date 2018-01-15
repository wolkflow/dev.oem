<? use Wolk\Core\Helpers\Text as TextHelper ?>
<? use Wolk\Core\Helpers\Currency as CurrencyHelper ?>

<div class="kds-wrapper">
	<div class="kds-header-top">
		<div class="kds-header-top-left">
			<p><b>KIDS Russia 2018</b></p>
			<p><b>February 27 - March 1, 2018</b></p>
			<p>10, Ordzhonikidze Street, 119071, Moscow, Russia</p>
			<p>Phone / Fax: +7 (495)258-80-32</p>
			<p>e-mail: info@kidsrussia.ru</p>
		</div>
		<div class="kds-header-top-right">
			<div class="kds-logo">
				<img src="<?= $this->getFolder() ?>/images/kds-logo.png" />
			</div>
		</div>
	</div>
	<div class="kds-header-mid">
		<div class="kds-header-mid-left">
			Invoice № <?= $arResult['PROPS']['BILL']['VALUE'] ?>
		</div>
		<div class="kds-header-mid-right">
			<? $ordertime = strtotime($arResult['ORDER']['DATE_INSERT']) ?>
			Date: <?= date('F d', $ordertime) ?>, <?= date('Y', $ordertime) ?>
		</div>
	</div>
	<div class="kds-header-bot">
		<div class="kds-header-bot-left">
			<div class="kds-header-bot-left-title">
				Contract Partner Name & Address:
			</div>
			<p><?= $arResult['USER']['WORK_COMPANY'] ?></p>
			<p><?= $arResult['USER']['WORK_STREET'] ?></p>
			<p>Phone: <?= $arResult['USER']['PERSONAL_PHONE'] ?></p>
			
			
			<? /*
			<p>NANTONG JINRONG MANUFACTURE CO., LTD</p>
			<p>Room 509, Eastern Ginza, Qidong Jiangsu , Nantong, China</p>
			<p>Phone: +86 513 833 00 337</p>
			*/ ?>
		</div>
		<div class="kds-header-bot-right">
			<p>Payment terms: wire bank transfer</p>
			<div class="kds-header-bot-right-title">
				All bank commissions are paid by the payer *
			</div>
			<p>Please kindly indicate in the payment your invoice number & brief exhibition name (KR-2018)</p>
		</div>
	</div>
	
	<div class="kds-table">
		<table>
			<thead>
				<tr>
					<th class="kds-table-col-1">Service</th>
					<th class="kds-table-col-2">Quantity (sq.m.)</th>
					<th class="kds-table-col-3">Unit cost, Euro</th>
					<th class="kds-table-col-4">Amount, Euro</th>
				</tr>
			</thead>
			<tbody>
				<? foreach ($arResult['BASKETS'] as $basket) { ?>
					<? if ($basket['SUMMARY_PRICE'] <= 0) continue ?>
					<tr>
						<td class="kds-table-col-1">
							<?= $basket['NAME'] ?>
						</td>
						<td class="kds-table-col-2">
							<?= $basket['QUANTITY'] ?>
						</td>
						<td class="kds-table-col-3">
							<?= number_format($basket['SURCHARGE_PRICE'], 2, ',', ' ') ?>
						</td>
						<td class="kds-table-col-4">
							<?= number_format($basket['SURCHARGE_SUMMARY_PRICE'], 2, ',', ' ') ?>
						</td>
					</tr>
				<? } ?>
				<tr class="kds-total-row kds-total-sum">
					<td class="kds-table-col-1" colspan="3">TOTAL for payment</td>
					<td class="kds-table-col-4">
						<?= number_format($arResult['ORDER']['PRICE'], 2, ',', ' ') ?>
					</td>
				</tr>
				<tr class="kds-total-row kds-total-say">
					<td class="kds-table-col-1" colspan="3">
						SAY:
						<? $mantissa = round(floatval($arResult['ORDER']['PRICE']) - intval($arResult['ORDER']['PRICE']), 2) * 100; ?>
						<? if ($mantissa > 0) { ?>
							<?= TextHelper::mb_ucfirst(TextHelper::num2str(intval($arResult['ORDER']['PRICE']), true, 'en', false, array('Euro', 'Euro', 'Euro'), array('Euro', 'Euro', 'Euro'))) ?>
							and
							<?= TextHelper::mb_ucfirst(TextHelper::num2str(intval($mantissa), true, 'en', false, array('Eurocents', 'Eurocents', 'Eurocents'), array('Eurocent', 'Eurocents', 'Eurocents'))) ?>
						<? } else { ?>
							<?= TextHelper::mb_ucfirst(TextHelper::num2str(intval($arResult['ORDER']['PRICE']), true, 'en', false, array('Euro', 'Euro', 'Euro'), array('Euro', 'Euro', 'Euro'))) ?>
						<? } ?>
						<!-- Three thousand & two hundred twenty five Euro -->
					</td>
					<td class="kds-table-col-4"></td>
				</tr>
				<tr class="kds-total-row kds-total-vat">
					<td class="kds-table-col-1" colspan="3">Including Russian VAT 18%</td>
					<td class="kds-table-col-4">
						<?= number_format($arResult['ORDER']['TAX_VALUE'], 2, ',', ' ') ?>
					</td>
				</tr>
				<tr class="kds-total-row kds-total-say">
					<td class="kds-table-col-1" colspan="3">
						SAY: 
						<!-- Four hundred ninty one Euro and ninty five Eurocents-->
						<? $mantissa = round(floatval($arResult['ORDER']['TAX_VALUE']) - intval($arResult['ORDER']['TAX_VALUE']), 2) * 100; ?>
						<? if ($mantissa > 0) { ?>
							<?= TextHelper::mb_ucfirst(TextHelper::num2str(intval($arResult['ORDER']['TAX_VALUE']), true, 'en', false, array('Euro', 'Euro', 'Euro'), array('Euro', 'Euro', 'Euro'))) ?>
							and
							<?= TextHelper::mb_ucfirst(TextHelper::num2str(intval($mantissa), true, 'en', false, array('Eurocents', 'Eurocents', 'Eurocents'), array('Eurocent', 'Eurocents', 'Eurocents'))) ?>
						<? } else { ?>
							<?= TextHelper::mb_ucfirst(TextHelper::num2str(intval($arResult['ORDER']['TAX_VALUE']), true, 'en', false, array('Euro', 'Euro', 'Euro'), array('Euro', 'Euro', 'Euro'))) ?>
						<? } ?>
					</td>
					<td class="kds-table-col-4"></td>
				</tr>
			</tbody>
		</table>
	</div>


	<div class="kds-chief">
		<div class="kds-chief-title">Chief Executive Officer</div>
		<div class="kds-chief-name">Alexander Parafeynikov</div>
		<div class="kds-stamp"><!--Печать--></div>
	</div>


	<div class="kds-payments">
		<div class="kds-payments-title">Payment schedule procedures:</div>
		<p><b>Registration & Marketing fees</b> are paid within 10 (ten) bank days after receipt of the invoice issued on the base of the exhibition participation Application form submitted to the Organizer. In case of late payment of the Registration & Marketing fees Application for participation at the exhibition is cancelled.</p>
		<p><b>Payment for the rented exhibition space at the rate of 50% of the total cost should be made within 10 days after receipt of the invoice issued on the base of the exhibition participation Application form submitted to the Organizer.</b></p>
		<p><b>Payment for the rented exhibition space at the rate of 50% of the total cost and the payment for all services, granting by GRAND EXPO, CJSC for the additional payment, should be made not later than December 30, 2017.</b></p>
	</div>
	<div class="kds-note">
		<p>Services, ordered by the Exhibitor under invoices issued by GRAND EXPO, CJSC after the indicated date, should be paid within 3 (three) banking days after the invoice receipt. <b>All bank payment fees should be paid by the Exhibitor.</b></p>
		<p class="kds-note_blue">*Please contact your international wire transfer bank specialist to be sure that you have paid all bank commissions including intermediary bank fees and beneficiary bank fees and beneficiary will receive exactly the amount that you send</p>
	</div>

	<div class="kds-info">
		<p>Bank transfer information:</p>
		<p>The payment recipient company name: GRAND EXPO, CJSC</p>
		<p>Address: 10, Ordzhonikidze Street, Moscow, 119071, Russia</p>
		<p>Payment account (EUR): 40702978222000007215 at AKB Absolut Bank, Kutuzovskiy prospekt 24,  Moscow, Russia, 121151, SWIFT: ABSLRUMM</p>
		<p>Correspondent account (EUR) BE73488591799660 at KBC BANK NV Havenlaan 2, B – 1080 Brussels, Belgium  SWIFT: KREDBEBB</p>
	</div>

	<div class="kds-ps">If you have any questions concerning this invoice, contact us by e-mail: sergey.sheyko@kidsrussia.ru</div>
</div>