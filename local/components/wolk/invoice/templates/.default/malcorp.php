<? use Wolk\Core\Helpers\Text as TextHelper ?>

<div class="invoice invoice2">
	<div class="invoiceHeader">
		<div class="invoiceHeader__left">
			<div class="invoiceHeaderTitle">Invoice</div>
			<div class="invoiceHeaderDetail">
				<p>OOO Messe Frankfurt RUS,</p>
				<p>Address for correspondence</p>
				<p>Leningradsky pr?t 39 build 80, 125167 Mosсow</p>
				<p>Tel. +7 495 649?8775, fax +7 495 649?8785</p>
			</div>
		</div>
		<div class="invoiceHeader__right">
			<img src="<?= $this->getFolder() ?>/images/invoiceLogo.png" />
		</div>
		<div class="clear"></div>
	</div>

	<div class="invoiceText">
		<p>MALCORP</p>
		<p>Jalan Ampang  11th floor, Bangunan Getah Asli(Menara),148  Kuala Lumpur  50450   Malaysia</p>
	</div>
	<? if (!empty($arResult['USER']['UF_VAT'])) { ?>
		<div class="invoiceText">
			<p>VAT ID</p>
			<p><?= $arResult['USER']['UF_VAT'] ?></p>
		</div>
	<? } ?>
	<div class="invoiceTextRight">
		<p class="invoiceTb"><b>To be indicated on payment</b></p>
		<p>Date: <span><?= date('d.m.Y') ?></span></p>
		<p>Customer number: <span><?= $arResult['USER']['UF_CLIENT_NUMBER'] ?></span></p>
		<p>Invoice number: <span><?= $arResult['PROPS']['BILL']['VALUE'] ?></span></p>
	</div>
	
	<div class="invoiceParams">
		<p><span class="toleft">Event:</span> <?= $arResult['EVENT']['NAME'] ?></p>
		<p><span class="toleft">Place:</span> <span class="red"><?= $arResult['EVENT']['PROPS']['LANG_LOCATION_EN']['VALUE'] ?></span></p>
		<p>
			<span class="toleft">From..To:</span> 
			<span class="red"><?= date('d.m.Y', strtotime($arResult['EVENT']['ACTIVE_FROM'])) ?> – <?= date('d.m.Y', strtotime($arResult['EVENT']['ACTIVE_TO'])) ?></span>
		</p>
	</div>

	<div class="invoiceParams2">
		<div class="invoiceParams2__left">
			<p><span class="toleft">Surface area in sqm:</span> <?= ($arResult['PROPS']['width']['VALUE'] * $arResult['PROPS']['depth']['VALUE']) ?></p>
			<p><span class="toleft">Sides opened:</span> </p>
			<p><span class="toleft">Width in m:</span> <?= $arResult['PROPS']['width']['VALUE'] ?></p>
			<p><span class="toleft">Depth in m:</span> <?= $arResult['PROPS']['depth']['VALUE'] ?></p>
		</div>
		<div class="invoiceParams2__right">
			<p><span class="toright">Hall / floor:</span> </p>
			<p><span class="toright">Stand No:</span> <?= $arResult['PROPS']['standNum']['VALUE'] ?></p>
		</div>
		<div class="clear"></div>
	</div>

	<table class="invoiceItems__table">
		<thead>
			<tr>
				<th>Description</th>
				<th>Code</th>
				<th>Price</th>
				<th>Quantity</th>
				<th>Unit</th>
				<th>EUR <br>Amount</th>
			</tr>
		</thead>
		<tbody>
			<? foreach ($arResult['BASKETS'] as $basket) { ?>
				<tr>
					<td><?= $basket['NAME'] ?></td>
					<td></td>
					<td><?= number_format($basket['PRICE'], 2, ',', ' ') ?></td>
					<td><?= $basket['QUANTITY'] ?></td>
					<td></td>
					<td><?= number_format($basket['SUMMARY_PRICE'], 2, ',', ' ') ?></td>
				</tr>
			<? } ?>
			<tr class="invoiceItems__table-amount">
				<td colspan="5">Net amount:</td>
				<td><?= number_format($arResult['ORDER']['PRICE'] - $arResult['ORDER']['TAX_VALUE'], 2, ',', ' ') ?></td>
			</tr>
			<tr class="invoiceItems__table-amount">
				<td colspan="5">VAT (18%):</td>
				<td><?= number_format($arResult['ORDER']['TAX_VALUE'], 2, ',', ' ') ?></td>
			</tr>
			<tr class="invoiceItems__table-total">
				<td colspan="5">Total:</td>
				<td><?= number_format($arResult['ORDER']['PRICE'], 2, ',', ' ') ?></td>
			</tr>
		</tbody>
	</table>
	<div class="invoiceMessage">
		<p>
			Very Important:<br>
			Please ensure Messe Frankfurt RUS receives complete invoice amount<br>
			All service charges (including bank fee) to be borne in full by the remitter - local and international
		</p>
	</div>

	<div class="invoiceAfter">
		<div class="invoiceAfterLeft">
			OOO Messe Frankfurt RUS<br>
			INN 7705365187<br>
			Account number 40702-978-7-0000-0-401484<br>
			Raiffeisenbank ZAO, Moscow 17/1 Troitskaya, Moscow, 129090, Russia<br>
			SWIFT: RZBMRUMM<br>
			Intermediary bank:<br>
			via Reiffeisen Zentralbank Osterreich AG<br>
			SWIFT: RZBAATWW
		</div>
		<div class="invoiceAfterRight">
			<div class="invoiceAfterSignature">

			</div>
			OOO Messe Frankfurt RUS<br>
			Responsible manager signature
		</div>
		<div class="clear"></div>
	</div>
</div>
