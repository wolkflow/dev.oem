<? use Wolk\Core\Helpers\Text as TextHelper ?>

<div class="bmr">
    <div class="bmrText">
		Внимание! Оплата данного счета означает согласие с условиями поставки товара. 
		Уведомление об оплате обязательно, в противном случае не гарантируется наличие товара на складе. 
		Товар отпускается по факту прихода денег на р/с Поставщика, самовывозом, при наличии доверенности и паспорта
	</div>

    <div class="bmrRequisite">
        <table>
            <tr>
                <td rowspan="2" colspan="2" class="shldr">
                    ПАО "СБЕРБАНК РОССИИ" Г. МОСКВА
                    <br>
                    <span>Банк получателя</span>
                </td>
                <td>БИК</td>
                <td colspan="2" rowspan="2">044525225 <br>30101810400000000225</td>
            </tr>
            <tr>
                <td>Сч. №</td>
            </tr>
            <tr>
                <td>ИНН 7729635280</td>
                <td>КПП 771401001</td>
                <td rowspan="2">Сч. №</td>
                <td>40702810938050012256</td>
            </tr>
            <tr>
                <td colspan="2" class="shldr">
					Общество с ограниченной ответсвенностью <br> 
					"БизнесМедиаРаша" <br> 
					<span>Получатель</span>
				</td>
                <td>&nbsp;</td>
            </tr>
        </table>
    </div>

    <div class="bmrInvoice">
        <div class="bmrInvoiceTitle">
			Счет на оплату №
			<span><?= $arResult['PROPS']['BILL']['VALUE'] ?></span>
			от
			<span class="text-right">
				<?= date('d', $arResult['DATE']) ?> <?= TextHelper::i18nmonth(date('n', $arResult['DATE']), false, 'ru') ?>
			</span> 
			<?= date('Y', $arResult['DATE']) ?> г.</div>
        <div class="bmrInvoiceRequisite">
            <table>
                <tr>
                    <td>Поставщик: </td>
                    <td>
						<b>
							Общество с ограниченной ответсвенностью "БизнесМедиаРаша", 
							ИНН 7729635280, КПП 771401001, 125252, 
							Москва г, Авиаконструктора Микояна ул, дом № 12, пом. 3, 
							тел.: 649-69-11
						</b>
					</td>
                </tr>
                <tr>
                    <td>Покупатель:</td>
                    <td>
						<b><?= $arResult['USER']['WORK_COMPANY'] ?></b>
						<? if (!empty($arResult['USER']['WORK_STREET']) || !empty($arResult['USER']['UF_REQUISITES'])) { ?>
							<br/>
							<b><?= implode('<br/>', array($arResult['USER']['WORK_STREET'], $arResult['USER']['UF_REQUISITES'])) ?></b>
						<? } ?>
					</td>
                </tr>
            </table>
        </div>
        <div class="bmrInvoiceTable">
            <table>
                <thead>
                    <tr>
                        <th>№</th>
                        <th>Товары (работы, услуги)</th>
                        <th>Кол-во</th>
                        <th>Ед.</th>
                        <th>Цена</th>
                        <th>Сумма</th>
                    </tr>
                </thead>
                <tbody>
					<tr>
						<td>1</td>
						<td>За технический сервис на выставке "<?= $arResult['EVENT']['NAME'] ?>"</td>
						<td>1</td>
						<td></td>
						<td>
							<? if ($arResult['ORDER']['TAX_VALUE'] > 0) { ?>
								<?= number_format($arResult['ORDER']['PRICE'] - $arResult['ORDER']['TAX_VALUE'], 2, ',', ' ') ?>
							<? } else { ?>
								<?= number_format($arResult['ORDER']['PRICE'], 2, ',', ' ') ?>
							<? } ?>
						</td>
						<td>
							<? if ($arResult['ORDER']['TAX_VALUE'] > 0) { ?>
								<?= number_format($arResult['ORDER']['PRICE'] - $arResult['ORDER']['TAX_VALUE'], 2, ',', ' ') ?>
							<? } else { ?>
								<?= number_format($arResult['ORDER']['PRICE'], 2, ',', ' ') ?>
							<? } ?>
						</td>
					</tr>
				
					<? /*
					<? $cnt = 0 ?>
					<? foreach ($arResult['BASKETS'] as $basket) { ?>
						<? if ($basket['SUMMARY_PRICE'] <= 0) continue; ?>
						<tr>
							<td><?= ++$cnt ?></td>
							<td><?= $basket['NAME'] ?></td>
							<td><?= $basket['QUANTITY'] ?></td>
							<td></td>
							<td><?= number_format($basket['SURCHARGE_PRICE'], 2, ',', ' ') ?></td>
							<td><?= number_format($basket['SURCHARGE_SUMMARY_PRICE'], 2, ',', ' ') ?></td>
						</tr>
					<? } ?>
					*/ ?>
					
					<? if ($arResult['ORDER']['TAX_VALUE'] > 0) { ?>
						<tr class="tableFooter first-child">
							<td colspan="5">Итого: </td>
							<td><?= number_format($arResult['ORDER']['PRICE'] - $arResult['ORDER']['TAX_VALUE'], 2, ',', ' ') ?></td>
						</tr>
						<tr class="tableFooter">
							<td colspan="5">Всего к оплате: </td>
							<td><?= number_format($arResult['ORDER']['PRICE'], 2, ',', ' ') ?></td>
						</tr>
						<tr class="tableFooter">
							<td colspan="5">В том числе с НДС: </td>
							<td><?= number_format($arResult['ORDER']['TAX_VALUE'], 2, ',', ' ') ?></td>
						</tr>
					<? } else { ?>
						<tr class="tableFooter first-child">
							<td colspan="5">Всего к оплате: </td>
							<td><?= number_format($arResult['ORDER']['PRICE'], 2, ',', ' ') ?></td>
						</tr>
						<tr class="tableFooter">
							<td colspan="5">В том числе с НДС: </td>
							<td><?= number_format($arResult['ORDER']['UNTAX_VALUE'], 2, ',', ' ') ?></td>
						</tr>
					<? } ?>
                </tbody>
            </table>
        </div>
        
        <p class="bmrInvoiceTotal">
			Всего <?= TextHelper::decofnum($cnt, ['наименование', 'наименования', 'наименований']) ?>, 
			на сумму <?= number_format($arResult['ORDER']['PRICE'], 2, ',', ' ') ?> руб.
		</p>
        <p class="bmrInvoiceTotal">
			<?= TextHelper::mb_ucfirst(TextHelper::num2str($arResult['ORDER']['PRICE'], true)) ?>
		</p>

        <div class="bmrInvoiceSign">
            <div class="bmrInvoiceSign-1"><span>Руководитель</span> Матвеев А. А.
	            <div class="bmrInvoiceImg"><img src="<?= $this->getFolder() ?>/images/stamp_inv.png" /></div>
	            <div class="bmrInvoiceAutographImg"><img src="<?= $this->getFolder() ?>/images/autograph2.png" /></div>
            </div>
        </div>
    </div>
</div>