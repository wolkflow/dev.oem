<? use Wolk\Core\Helpers\Text as TextHelper ?>

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
					<p> Leningradsky Prosp. 39, Build. 80, 125167, Moscow Russia <br>
						Raiffeisenbank AO, Moscow<br>
						SWIFT RZBMRUMM<br>
						Acc.: 40702978200000001177
					</p>
				</td>
				<td>
					<p><b>Al Muqarram Auto Spare Pars Trading L.L.C.</b></p>
					<p>Twin Towers, Bauiyas Street, 17th Floor, Office Suite:<br> 1701-1702, Deira Dubai U.A.E, P.O Box 60235</p>
				</td>
			</tr>
		</tbody>
	</table>

	<div class="finvoiceNum">Invoice No 2016020325-16020518-1 Date 29.02.16</div>

	<table class="finvoiceDetail">
		<thead>
			<tr>
				<th colspan="2">Product</th>
				<th class="finvoiceDetail-3">Total amount</th>
			</tr>
		</thead>
		<tbody>
			<tr class="fiItemRow">
				<td colspan="2">Prepayment for rent of exhibition space and for the participation in an exhibition MIMS 2016 (22.08.16 - 25.08.16) / Предоплата за аренду выставочной площади и организацию участия в выставке MIMS Automechanika Moscow 2016 (22.08.16 - 25.08.16) </td>
				<td class="fiSumm">8 599,30</td>
			</tr>
			<tr>
				<td class="unborder">&nbsp;</td>
				<td class="finvoiceDetail-2">VAT:</td>
				<td class="finvoiceDetail-3">18%</td>
			</tr>
			<tr>
				<td class="unborder">&nbsp;</td>
				<td class="finvoiceDetail-2">Total VAT:</td>
				<td class="finvoiceDetail-3">1 547,87</td>
			</tr>
			<tr>
				<td class="unborder">Currency: EURO</td>
				<td class="finvoiceDetail-2">Total value:</td>
				<td class="finvoiceDetail-3">10 147,17</td>
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
			<div class="bottomSignatureCode"><img src="<?= $this->getFolder() ?>/images/code.png" alt=""></div>
			<div class="finvoiceStamp"><img src="<?= $this->getFolder() ?>/images/itemf_stamp.png" alt=""></div>
		</div>
		<div class="bottomSignatureRight">
			Chief accountant S. Samsonova
			<div class="finvoiceSignature"><img src="<?= $this->getFolder() ?>/images/itemf_signature.png" alt=""></div>
		</div>
		<div class="clear"></div>
	</div>
</div>
