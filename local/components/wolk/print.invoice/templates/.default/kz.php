<? use Wolk\Core\Helpers\Text as TextHelper ?>
<? use Wolk\Core\Helpers\Currency as CurrencyHelper ?>

<div class="inv_kz-wrapper">
	<div class="inv_kz-top">
		<div class="inv_kz-name">
			Наименование выставки
			<p class="line qr">
				<?= $arResult['EVENT']['PROPS']['LANG_TITLE_RU']['VALUE'] ?>
			</p>
		</div>
		<div class="inv_kz-date">
			<span class="line dm qr">03</span>
			<span class="line dmy">Апрель</span>
			&ndash;
			<span class="line dm qr">05</span>
			<span class="line dmy">Апрель</span>
			201<span class="line yy">8</span> г.
		</div>
		<div class="inv_kz-invnum">
			СЧЕТ № <span class="line"><?= $arResult['PROPS']['BILL']['VALUE'] ?></span>
		</div>
	</div>

	<div class="inv_kz-header">
		<? $ordertime = strtotime($arResult['ORDER']['DATE_INSERT']) ?>
		<div class="sdate">
			От 
			<span class="line qr"><?= date('d', $ordertime) ?></span> 
			<span class="line"><?= TextHelper::i18nmonth(date('n', $ordertime), false, 'ru') ?></span> 
			<span class="line"><?= date('Y', $ordertime) ?></span>  г.
		</div>
		<p class="clear">Республика Казахстан, 010000</p>
		<p>г. Астана, ул. Достык, 1, ВП-15</p>
		<p>тел./факс: + 7 (7172) 278 181</p>
		<p>ТОО «ВК «Астана–Экспо КС»</p>
		<p>БИН 050 640 004 409</p>
		<p>ИИК KZ7396503F0007729189</p>
		<p>Филиал АО «ForteBank» в г. Астана</p>
		<p>SWIFT код: IRTYKZKA</p>
		<p>БИК: IRTYKZKA</p>
		<p>Свид. НДС серия 62001 № 0015312 от 19.09.12</p>
	</div>
	
	<p class="linein">
		<span>Плательщик</span>
		<?= $arResult['USER']['WORK_COMPANY'] ?>
	</p>

	<table>
		<thead>
			<tr>
				<th class="tname">Наименование</th>
				<th class="tunit">Ед. изм.</th>
				<th class="tcount">Кол-во</th>
				<th class="tcost">Стоимость, тенге</th>
				<th class="tsumm">Сумма, тенге</th>
			</tr>
		</thead>
		<tbody>
			<? foreach ($arResult['BASKETS'] as $basket) { ?>
				<? if ($basket['SUMMARY_PRICE'] <= 0) continue ?>
				<tr>
					<td class="tname"><?= $basket['NAME'] ?></td>
					<td class="tunit">шт</td>
					<td class="tcount"><?= $basket['QUANTITY'] ?></td>
					<td class="tcost"><?= number_format($basket['SURCHARGE_PRICE'], 2, ',', ' ') ?></td>
					<td class="tsumm"><?= number_format($basket['SURCHARGE_SUMMARY_PRICE'], 2, ',', ' ') ?></td>
				</tr>
			<? } ?>
			<tr class="total">
				<td class="tname">Итого (в т. ч. НДС)</td>
				<td class="tunit"></td>
				<td class="tcount"></td>
				<td class="tcost"></td>
				<td class="tsumm"><?= number_format($arResult['ORDER']['PRICE'], 0, ',', ' ') ?></td>
			</tr>
		</tbody>
	</table>

	<div class="totalString">
		Итого: 
		 <?= number_format($arResult['ORDER']['PRICE'], 0, ',', ' ') ?>
		(<?= TextHelper::mb_ucfirst(TextHelper::num2str($arResult['ORDER']['PRICE'], false)) ?>) тенге.
	</div>
	
	<? $contact = reset($arResult['EVENT']['PROPS']['LANG_CONTACTS_RU']['VALUE']) ?>
	<div class="info">
		<div class="manager">
			Менеджер: 
			<span class="line">Ведущий менеджер Илья Киселёв</span>
		</div>
		<div class="phone">
			Тел.: 
			<span class="line">+ 7 (7172) 278 181</span>
		</div>
		<div class="inv_kz-sign">
			<img src="<?= $this->getFolder() ?>/images/inv_kz-stamp.png" />
			<img src="<?= $this->getFolder() ?>/images/inv_kz-sign.png" />
		</div>
	</div>
	</div>