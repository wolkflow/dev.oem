<? use Wolk\Core\Helpers\Text as TextHelper ?>

<div class="bmren">
    <div class="bmrenImg">
		<img src="<?= $this->getFolder() ?>/images/bmrhdr.jpg" />
	</div>
    <div class="bmrenHdr">
        <div class="bmrenTitle">INVOICE / СЧЕТ</div>
        <div class="bmrenAddr">
            <?= $arResult['USER']['WORK_COMPANY'] ?>
			<? if (!empty($arResult['USER']['WORK_STREET']) || !empty($arResult['USER']['UF_REQUISITES'])) { ?>
				<br/>
				<?= implode('<br/>', array($arResult['USER']['WORK_STREET'], $arResult['USER']['UF_REQUISITES'])) ?>
			<? } ?>
        </div>
        <div class="bmrenNum">
	        <table>
		        <tr>
			        <td style="font-size: 18px;font-weight: 700;">Invoice (Счет) №:</td>
			        <td style="font-size: 18px;font-weight: 700;padding-left: 40px;" align="right"><?= $arResult['PROPS']['BILL']['VALUE'] ?></td>
		        </tr>
		        <tr>
			        <td>Date (Дата): </td>
			        <td align="right" style="padding-left: 40px;"><?= date('d.m.Y', $arResult['DATE']) ?></td>
		        </tr>
	        </table>
        </div>
    </div>

    <div class="bmrenTable">
        <table>
            <thead>
                <tr>
                    <th>
                        <span>Event:<br> Событие:</span>
                        <?= $arResult['EVENT']['NAME'] ?>
                    </th>
                    <th>Price</th>
                    <th>Count</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
				<? $cnt = 0 ?>
				<? foreach ($arResult['BASKETS'] as $basket) { ?>
					<? if ($basket['SUMMARY_PRICE'] <= 0) continue; ?>
					<tr>
						<td><?= $basket['NAME'] ?></td>
						<td><?= number_format($basket['SURCHARGE_PRICE'], 2, ',', ' ') ?></td>
						<td><?= $basket['QUANTITY'] ?></td>
						<td><?= number_format($basket['SURCHARGE_SUMMARY_PRICE'], 2, ',', ' ') ?></td>
					</tr>
				<? } ?>
				
				<? if ($arResult['EVENT']['PROPS']['INCLUDE_VAT']['VALUE'] != 'Y') { ?>
					<tr class="bmrenTableFooter first-child">
						<td colspan="3">SUB TOTAL (СУММА БЕЗ НДС)</td>
						<td><?= number_format($arResult['ORDER']['PRICE'] - $arResult['ORDER']['TAX_VALUE'], 2, ',', ' ') ?></td>
					</tr>
					<tr class="bmrenTableFooter">
						<td colspan="3">VAT (НДС) 18%</td>
						<td><?= number_format($arResult['ORDER']['TAX_VALUE'], 2, ',', ' ') ?></td>
					</tr>
					<tr class="bmrenTableFooter">
						<td colspan="3"><b>TOTAL INVOICE VALUE (ОБЩАЯ СУММА СЧЕТА)</b></td>
						<td class="border-top">
							<span>&euro;</span>
							<?= number_format($arResult['ORDER']['PRICE'], 2, ',', ' ') ?>
						</td>
					</tr>
				<? } else { ?>
					<tr class="bmrenTableFooter first-child">
						<td colspan="3">VAT (НДС) 18%</td>
						<td><?= number_format($arResult['ORDER']['UNTAX_VALUE'], 2, ',', ' ') ?></td>
					</tr>
					<tr class="bmrenTableFooter">
						<td colspan="3"><b>TOTAL INVOICE VALUE (ОБЩАЯ СУММА СЧЕТА)</b></td>
						<td class="border-top">
							<span>&euro;</span>
							<?= number_format($arResult['ORDER']['PRICE'], 2, ',', ' ') ?>
						</td>
					</tr>
				<? } ?>
            </tbody>
        </table>
        
        <div class="bmrenTotal">
            <div class="bmrenTotalTitle">
				YOU NEED TO PAY € <?= number_format($arResult['ORDER']['PRICE'], 2, ',', ' ') ?> NOW
			</div>
            <div class="bmrenTotalMsg">IMPORTANT: INTERMEDIARY BANK CHARGES MUST BE PAID BY THE ORDERING CUSTOMER</div>

            <div class="bmrenTotalDetails">
                <div class="bmrenTotalDetailsTitle">PAYMENT DETAILS</div>
                <div class="bmrenTotalDetailsLeft">
                    <p>By bank transfer to:</p>
                </div>
                <div class="bmrenTotalDetailsRight">
                    <p>Bank name: SBERBANK</p>
                    <p>Branch: Moscow Bank of SBERBANK of Russia</p>
                    <p>SWIFT:     SABRRUMM</p>
                    <p>Account: 40702978238051000388</p>
                    <p>BusinessMediaRussia ltd</p>
                    <p>bld.3, 12, Aviakonstruktora Mikoyana str., Moscow 125252, Russia</p>
                    <p>Correspondent bank:Deutsche Bank AG, Frankfurt am Main, Germany</p>
                    <p>SWIFT:  DEUTDEFF</p>
                    <p>Corr. acc.  10094987261000</p>
                </div>
                <div class="bmrenTotalDetailsNote">
					IMPORTANT: PLEASE ASK YOUR BANK TO CLEARLY STATE YOUR COMPANY NAME AND THE INVOICE No. ON ALL PAYMENTS
				</div>
            </div>
        </div>
    </div>

    <div class="bmrenSign">
        Managing director <span>A. Matveev</span>
        <div class="bmrenSignImg"><img src="<?= $this->getFolder() ?>/images/stamp_inv.png" /></div>
        <div class="bmrenAutographImg"><img src="<?= $this->getFolder() ?>/images/autograph2.png" /></div>
    </div>
</div>
