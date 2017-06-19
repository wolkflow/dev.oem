<? $logo = CFile::ResizeImageGet($arResult['EVENT']['PROPS']['LANG_LOGO_'.$arResult['LANGUAGE']]['VALUE'], ['width' => 168, 'height' => 68], BX_RESIZE_IMAGE_PROPORTIONAL_ALT)['src'] ?>

<table class="qo-wrapper">
    <tr>
        <td>
            <div class="qo-header">
                <div class="qo-logo">
                    <img src="<?= $logo ?>" alt="<?= $arResult['EVENT']['NAME'] ?>" />
                </div>
                <div class="qo-logo-2">
                    <img src="<?= $this->getFolder() ?>/images/qo-logo-2.png" />
                </div>
            </div>
            <div class="qo-order">
                <div class="qo-order-left">Заказ №<?= $arResult['ORDER']['ID'] ?></div>
                <div class="qo-order-right">
                    <ul>
                        <li><b>Павильон:</b> <?= $arResult['PROPS']['pavillion']['VALUE'] ?></li>
                        <li><b>Стенд:</b> <?= $arResult['PROPS']['standNum']['VALUE'] ?></li>
                        <? /* <li><b>Метраж:</b> <?= round(floatval($arResult['PROPS']['width']['VALUE'] * $arResult['PROPS']['depth']['VALUE']), 2) ?> м<sup>2</sup></li> */ ?>
                        <li><b>Компания:</b> <?= $arResult['USER']['WORK_COMPANY'] ?></li>
                        <li><b>Дата:</b> <?= date('d.m.Y', $arResult['DATE']) ?></li>
                    </ul>                    
                </div>
            </div>
            
            <table class="qo-table">
                <thead>
                    <tr>
                        <th class="go-item-name">Название</th>
                        <th>Цена</th>
                        <th>Количество</th>
                        <th>Стоимость</th>
                    </tr>
                </thead>
                <tbody>
                    <? $i = 0 ?>
                    <? foreach ($arResult['BASKETS'] as $basket) { ?>
                        <? if ($basket['SUMMARY_PRICE'] <= 0) continue ?>
                        <? $i++ ?>
                        <tr>
                            <td><?= $i ?>. <?= $basket['NAME'] ?>.</td>
                            <td><?= str_replace('₽', 'руб.', CurrencyFormat($basket['SURCHARGE_PRICE'], $basket['CURRENCY'])) ?></td>
                            <td><?= $basket['QUANTITY'] ?></td>
                            <td><?= str_replace('₽', 'руб.', CurrencyFormat($basket['SURCHARGE_SUMMARY_PRICE'], $basket['CURRENCY'])) ?></td>
                        </tr>
                    <? } ?>
                    <tr class="qp-table-footer first-child">
                        <td colspan="4">&nbsp;</td>
                    </tr>
                </tbody>
            </table>
			<br/>
			<table style="width: 100%">
				<tr>
					<td align="right">
						<table style="width: 100%">
							<tbody>
								<? if ($arResult['EVENT']['PROPS']['INCLUDE_VAT']['VALUE'] != 'Y') { ?>
									<tr class="qp-table-footer">
										<td align="right">
											ИТОГО БЕЗ НДС
										</td>
										<td>&nbsp;</td>
										<td align="left">
											<?= str_replace('₽', 'руб.', CurrencyFormat($arResult['ORDER']['PRICE'] - $arResult['ORDER']['TAX_VALUE'], $arResult['ORDER']['CURRENCY'])) ?>
										</td>
										<td align="right">
											НДС (18%)
										</td>
										<td>&nbsp;</td>
										<td align="left">
											<?= str_replace('₽', 'руб.', CurrencyFormat($arResult['ORDER']['TAX_VALUE'], $arResult['ORDER']['CURRENCY'])) ?>
										</td>
										<td align="right">
											ИТОГО С НДС
										</td>
										<td>&nbsp;</td>
										<td align="left">
											<?= str_replace('₽', 'руб.', CurrencyFormat($arResult['ORDER']['PRICE'], $arResult['ORDER']['CURRENCY'])) ?>
										</td>
									</tr>
								<? } else { ?>
									<tr class="qp-table-footer">
										<td align="right">
											НДС (18%)
										</td>
										<td style="">&nbsp;</td>
										<td align="left">
											<?= str_replace('₽', 'руб.', CurrencyFormat($arResult['ORDER']['UNTAX_VALUE'], $arResult['ORDER']['CURRENCY'])) ?>
										</td>
										<td align="right">
											ИТОГО С НДС
										</td>
										<td style="">&nbsp;</td>
										<td align="left">
											<?= str_replace('₽', 'руб.', CurrencyFormat($arResult['ORDER']['PRICE'], $arResult['ORDER']['CURRENCY'])) ?>
										</td>
									</tr>
								<? } ?>
							</tbody>
						</table>
					</td>
					<tr class="qp-table-footer last-child">
						<td>&nbsp;</td>
					</tr>
				</tr>
			</table>
        </td>
    </tr>
    <tr class="qo-wrapper-bottom">
        <td>
            <div class="qo-col">
                <div>
                    <span>Заказ принят</span>
                </div>
            </div>
            <div class="qo-col">
                <div>
                    <span>Участник</span>
                </div>
            </div>
            <div class="qo-col">
                <div>
                    <span>Оплата принята</span>
                </div>
            </div>
        </td>
    </tr>
</table>