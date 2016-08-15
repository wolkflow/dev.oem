<table class="qo-wrapper">
    <tr>
        <td>
            <div class="qo-header">
                <div class="qo-logo">
                    <img src="images/qo-logo.png" alt="<?= $arResult['EVENT']['NAME'] ?>" />
                </div>
                <div class="qo-logo-2">
                    <img src="images/qo-logo-2.png" alt="" />
                </div>
            </div>
            <div class="qo-order">
                <div class="qo-order-left">Order #<?= $arResult['ORDER']['ID'] ?></div>
                <div class="qo-order-right">
                    <ul>
                        <li><b>Pavillion:</b> <?= $arResult['PROPS']['pavillion']['VALUE'] ?></li>
                        <li><b>Stand:</b> <?= $arResult['PROPS']['standNum']['VALUE'] ?></li>
                        <li><b>Area:</b> <?= round(floatval($arResult['PROPS']['width']['VALUE'] * $arResult['PROPS']['depth']['VALUE']), 2) ?> �<sup>2</sup></li>
                        <li><b>Company:</b> <?= $arResult['USER']['WORK_COMPANY'] ?></li>
                        <li><b>Dat:</b> <?= date('d.m.Y', $arResult['DATE']) ?></li>
                    </ul>                    
                </div>
            </div>
            
            <table class="qo-table">
                <thead>
                    <tr>
                        <th>Title</th>
                        <th>Cost</th>
                        <th>Quantity</th>
                        <th>Price</th>
                    </tr>
                </thead>
                <tbody>
                    <? $i = 0 ?>
                    <? foreach ($arResult['BASKETS'] as $basket) { ?>
                        <? if ($basket['SUMMARY_PRICE'] <= 0) continue ?>
                        <? $i++ ?>
                        <tr>
                            <td><?= $i ?>. <?= $basket['NAME'] ?>.</td>
                            <td><?= CurrencyFormat($basket['SURCHARGE_SUMMARY_PRICE'], $basket['CURRENCY']) ?></td>
                            <td><?= $basket['QUANTITY'] ?></td>
                            <td><?= CurrencyFormat($basket['SURCHARGE_PRICE'], $basket['CURRENCY']) ?></td>
                        </tr>
                    <? } ?>
                    <tr class="qp-table-footer first-child">
                        <td colspan="4">&nbsp;</td>
                    </tr>
                    
                    <? if ($arResult['EVENT']['PROPS']['INCLUDE_VAT']['VALUE'] != 'Y') { ?>
                        <tr class="qp-table-footer">
                            <td>&nbsp;</td>
                            <td colspan="2">
                                TOTAL WITHOUT VAT
                            </td>
                            <td>
                                <?= CurrencyFormat($arResult['ORDER']['PRICE'] - $arResult['ORDER']['TAX_VALUE'], $arReuslt['ORDER']['CURRENCY']) ?>
                            </td>
                        </tr>
                        <tr class="qp-table-footer">
                            <td>&nbsp;</td>
                            <td colspan="2">
                                VAR (18%)
                            </td>
                            <td>
                                <?= CurrencyFormat($arResult['ORDER']['TAX_VALUE'], $arReuslt['ORDER']['CURRENCY']) ?>
                            </td>
                        </tr>
                        <tr class="qp-table-footer">
                            <td>&nbsp;</td>
                            <td colspan="2">
                                TOTAL WITH VAT
                            </td>
                            <td>
                                <?= CurrencyFormat($arResult['ORDER']['PRICE'], $arReuslt['ORDER']['CURRENCY']) ?>
                            </td>
                        </tr>
                    <? } else { ?>
                        <tr class="qp-table-footer">
                            <td>&nbsp;</td>
                            <td colspan="2">
                                VAT (18%)
                            </td>
                            <td>
                                <?= CurrencyFormat($arResult['ORDER']['UNTAX_VALUE'], $arReuslt['ORDER']['CURRENCY']) ?>
                            </td>
                        </tr>
                        <tr class="qp-table-footer">
                            <td>&nbsp;</td>
                            <td colspan="2">
                                TOTAL WITH VAT
                            </td>
                            <td>
                                <?= CurrencyFormat($arResult['ORDER']['PRICE'], $arReuslt['ORDER']['CURRENCY']) ?>
                            </td>
                        </tr>
                    <? } ?>
                    <tr class="qp-table-footer last-child">
                        <td colspan="4">&nbsp;</td>
                    </tr>
                </tbody>
            </table>
        </td>
    </tr>
    <tr class="qo-wrapper-bottom">
        <td>
            <div class="qo-col">
                <div>
                    <span>Order accepted</span>
                </div>
            </div>
            <div class="qo-col">
                <div>
                    <span>Users</span>
                </div>
            </div>
            <div class="qo-col">
                <div>
                    <span>Payment received</span>
                </div>
            </div>
        </td>
    </tr>
</table>