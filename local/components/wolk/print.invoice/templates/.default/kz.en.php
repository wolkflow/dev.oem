<? use Wolk\Core\Helpers\Text as TextHelper ?>
<? use Wolk\Core\Helpers\Currency as CurrencyHelper ?>

<div class="tg-invoice">
    <div class="tg-invoice-head">
        <div class="tg-invoice-head_pre">
            <p class="tg-invoice-head_name">
                <?= $arResult['EVENT']['NAME'] ?>
            </p>
            <p class="tg-invoice-head_name">
                <? $timef = strtotime($arResult['EVENT']['ACTIVE_FROM']) ?>
                <? $timet = strtotime($arResult['EVENT']['ACTIVE_TO']) ?>
                
                <?= date('d', $timef) ?> 
                <?= TextHelper::i18nmonth(date('n', $timef), false, 'ru') ?> 
                <?= date('Y', $timef) ?> г.
                &mdash;
                <?= date('d', $timet) ?> 
                <?= TextHelper::i18nmonth(date('n', $timef), false, 'ru') ?> 
                <?= date('Y', $timet) ?> г.
            </p>
            <p class="tg-invoice-num">
                Счет № <?= $arResult['PROPS']['BILL']['VALUE'] ?>
            </p>
            <p class="tg-invoice-date">
                <? $ordertime = strtotime($arResult['ORDER']['DATE_INSERT']) ?>
                От
                &laquo;<?= date('d', $ordertime) ?>&raquo; 
                <?= TextHelper::i18nmonth(date('n', $ordertime), false, 'ru') ?> 
                <?= date('Y', $ordertime) ?> г.
            </p>
        </div>
        <div class="tg-invoice-detail">
            <p>
                Республика Казахстан, 010000<br>
                г. Астана, ул. Достык, 1<br>
                ТОО &laquo;ВК &laquo;Астана-Экспо КС&raquo;<br>
                БИН 050640004409<br>
                АО &laquo;Bank RBK&raquo;<br>
                Р/счет № KZ248210439812157236<br>
                БИК KINCKZKA, AO &laquo;Bank RBK&raquo;<br>
                Свид. НДС серия 62001 №0015312 от 19.09.12<br>
                Код ОКПО 40565396<br>
                Код ОКЭД 82300<br>
                <br>
                Плательщик: <?= $arResult['USER']['WORK_COMPANY'] ?>
            </p>
        </div>
    </div>

    <div class="tg-invoice-table">
        <table>
            <thead>
                <tr>
                    <th>Наименование</th>
                    <th>Ед. изм.</th>
                    <th>Кол-во</th>
                    <th>Цена, тенге</th>
                    <th>Сумма, тенге</th>
                </tr>
                <tr>
                    <th colspan="5">За дополнительное оборудование</th>
                </tr>
            </thead>
            <tbody>
                <? $i = 0 ?>
                <? foreach ($arResult['BASKETS'] as $basket) { ?>
                    <? if ($basket['SUMMARY_PRICE'] <= 0) continue ?>
                    <? $i++ ?>
                    <tr>
                        <td><?= $basket['NAME'] ?></td>
                        <td>шт.</td>
                        <td><?= $basket['QUANTITY'] ?></td>
                        <td><?= number_format($basket['SURCHARGE_PRICE'], 2, ',', ' ') ?></td>
                        <td><?= number_format($basket['SURCHARGE_SUMMARY_PRICE'], 2, ',', ' ') ?></td>
                    </tr>
                <? } ?>
                <tr>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                </tr>
                <tr class="tg-invoice-table_footer">
                    <td>Итого к оплате (в т.ч. НДС 12%)</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td><?= number_format($arResult['ORDER']['PRICE'], 2, ',', ' ') ?></td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="tg-invoice-sum">
        <p>
            Итого: <?= number_format($arResult['ORDER']['PRICE'], 0, ',', ' ') ?>
            (<?= TextHelper::mb_ucfirst(TextHelper::num2str($arResult['ORDER']['PRICE'], false)) ?>) тенге.
        </p>
    </div>
    <div class="tg-invoice-footer">
        <div class="tg-invoice-footer-copy">
            <p>
                Менеджер Качан Е. Н. <br>
                +7 (7172) 52-43-03
            </p>
        </div>
        <div class="tg-invoice-footer-stamp">
            <img src="<?= $this->getFolder() ?>/images/tg-stamp.png" />
        </div>
    </div>

</div>