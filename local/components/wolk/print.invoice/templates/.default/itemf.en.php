<? use Wolk\Core\Helpers\Text as TextHelper ?>

<script>
	$(document).ready(function(){
		var allheight = $('.ficontainer').height();
		if(allheight >= 880 && allheight < 1200) {
			$('.ficontainer').css({'padding-bottom': '400px'})
		}
	});
</script>
<div class="ficontainer">
	<div class="filogo">
		<img src="<?= $this->getFolder() ?>/images/itemf_logo.png" alt="">
	</div>

	<div class="finvoiceName">ITEMF EXPO</div>

	<table class="fpaymentsTitle">
		<thead>
			<tr>
				<th>PAYMENT DETAILS</th>
				<th>EXHIBITOR</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td>
					<p><b>Joint Stock Company “ITEMF EXPO”</b></p>
					<p>
						Leningradsky Prosp. 39, Build. 80, 125167, Moscow Russia <br>
						Raiffeisenbank AO, Moscow<br>
						SWIFT RZBMRUMM<br>
						Acc.: 40702978200000001177
					</p>
				</td>
				<td>
					<p><b><?= $arResult['USER']['WORK_COMPANY'] ?></b></p>
					<p>
						<?= implode('<br/>', array($arResult['USER']['WORK_STREET'], $arResult['USER']['UF_REQUISITES'])) ?>
					</p>
				</td>
			</tr>
		</tbody>
	</table>

	<div class="finvoiceNum">
		Invoice No <?= $arResult['PROPS']['BILL']['VALUE'] ?> Date <?= date('d.m.y', $arResult['DATE']) ?>
	</div>

	<table class="finvoiceDetail">
		<thead>
			<tr>
				<th colspan="2">Product</th>
				<th>Price</th>
				<th>Quantity</th>
				<th class="finvoiceDetail-3">Total amount</th>
			</tr>
		</thead>
		<tbody>
			<? foreach ($arResult['BASKETS'] as $basket) { ?>
				<? if ($basket['SUMMARY_PRICE'] <= 0) continue ?>
				<tr class="fiItemRow">
					<td colspan="2">
						<?= $basket['NAME'] ?>
					</td>
					<td class="fiSumm">
						<?= number_format($basket['PRICE'], 2, ',', ' ') ?>
					</td>
					<td class="fiSumm">
						<?= $basket['QUANTITY'] ?>
					</td>
					<td class="fiSumm">
						<?= number_format($basket['SURCHARGE_SUMMARY_PRICE'], 2, ',', ' ') ?>
					</td>
				</tr>
			<? } ?>
			<tr>
				<td class="unborder">&nbsp;</td>
				<td class="unborder">&nbsp;</td>
				<td colspan="2" class="finvoiceDetail-2 no-right-border"">Total without VAT:</td>
				<td class="finvoiceDetail-3"><?= number_format($arResult['ORDER']['PRICE'] - $arResult['ORDER']['TAX_VALUE'], 2, ',', ' ') ?></td>
			</tr>
			<tr>
				<td class="unborder">&nbsp;</td>
				<td class="unborder">&nbsp;</td>
				<td colspan="2" class="finvoiceDetail-2 no-right-border"">VAT:</td>
				<td class="finvoiceDetail-3">18%</td>
			</tr>
			<tr>
				<td class="unborder">&nbsp;</td>
				<td class="unborder">&nbsp;</td>
				<td colspan="2" class="finvoiceDetail-2 no-right-border"">Total VAT:</td>
				<td class="finvoiceDetail-3"><?= number_format($arResult['ORDER']['TAX_VALUE'], 2, ',', ' ') ?></td>
			</tr>
			<tr>
				<td class="unborder">&nbsp;</td>
				<td class="unborder">Currency: EURO</td>
				<td colspan="2" class="finvoiceDetail-2 no-right-border"">Total value:</td>
				<td class="finvoiceDetail-3"><?= number_format($arResult['ORDER']['PRICE'], 2, ',', ' ') ?></td>
			</tr>
		</tbody>
	</table>

	<div class="importantBlock">
		<p>IMPORTANT: PLEASE ASK YOUR BANK TO CLEARLY STATE YOUR COMPANY NAME AND THE INVOCE № ON ALL PAYMENTS</p>
		<p>PAYMENT IS EXPECTED IN FULL, AS THE CUSTOMER IS RESPONSIBLE FOR HIS OWN BANK FEES</p>
		<p>PAYMENT DOCUMENT MUST REFER TO THE INVOICE NUMBER</p>
	</div>

	<div class="bottomSignature">
		<div class="bottomSignatureLeft">
			Director General S. Alexandrov
			<div class="finvoiceStamp finvoiceStamp_en"><img src="<?= $this->getFolder() ?>/images/stamp_inv.png" /></div>
		</div>
		<div class="bottomSignatureRight">
			Chief accountant S. Samsonova
			<div class="finvoiceSignature"><img src="<?= $this->getFolder() ?>/images/itemf_signature.png" /></div>
		</div>
		<div class="clear"></div>
	</div>
</div>
