<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>

<div class="order-print">
	<h1>Заказ №<?= $arResult['ORDER']['ID'] ?> от <?= date('d.m.Y', strtotime($arResult['ORDER']['DATE_INSERT'])) ?></h1>
	<div class="order">
		<table width="300px">
			<tbody>
				<tr>
					<td>Номер стенда</td>
					<td><?= $arResult['PROPS']['standNum']['VALUE'] ?></td>
				</tr>
				<tr>
					<td>Ширина стенда (м)</td>
					<td><?= $arResult['PROPS']['width']['VALUE'] ?></td>
				</tr>
				<tr>
					<td>Глубина стенда (м)</td>
					<td><?= $arResult['PROPS']['depth']['VALUE'] ?></td>
				</tr>
				<tr>
					<td>Полщадь стенда (м<sup>2</sup>)</td>
					<td><?= ($arResult['PROPS']['width']['VALUE'] * $arResult['PROPS']['depth']['VALUE']) ?></td>
				</tr>
			</tbody>
		</table>
	</div>
	
	<div class="baskets">
		<table class="invoiceItems__table">
			<thead>
				<tr>
					<th>Изображение</th>
					<th>Наименование</th>
					<th>Цена</th>
					<th>Количество</th>
					<th>Сумма</th>
				</tr>
			</thead>
			<tbody>
				<? foreach ($arResult['BASKETS'] as $basket) { ?>
					<tr>
						<td>
							 <? if (!empty($basket['ITEM']['PREVIEW_PICTURE'])) { ?>
								<img src="<?= $basket['ITEM']['IMAGE'] ?>" width="60" height="60" />
							<? } else { ?>
								<div class="no-photo">Нет картинки</div>
							<? } ?>
						</td>
						<td><?= $basket['NAME'] ?></td>
						<td><?= number_format($basket['PRICE'], 2, ',', ' ') ?></td>
						<td><?= intval($basket['QUANTITY']) ?></td>
						<td><?= number_format($basket['SUMMARY_PRICE'], 2, ',', ' ') ?></td>
					</tr>
				<? } ?>
			</tbody>
			<tfoot>
				<tr class="invoiceItems__table-amount">
					<td colspan="3"></td>
					<td class="text-left">Всего без НДС:</td>
					<td><?= number_format($arResult['ORDER']['PRICE'] - $arResult['ORDER']['TAX_VALUE'], 2, ',', ' ') ?></td>
				</tr>
				<tr class="invoiceItems__table-amount">
					<td colspan="3"></td>
					<td class="text-left">НДС (18%):</td>
					<td><?= number_format($arResult['ORDER']['TAX_VALUE'], 2, ',', ' ') ?></td>
				</tr>
				<tr class="invoiceItems__table-total">
					<td colspan="3"></td>
					<td class="text-left">ВСЕГО С НДС:</td>
					<td><?= number_format($arResult['ORDER']['PRICE'], 2, ',', ' ') ?></td>
				</tr>
			</tfoot>
		</table>
	</div>
	
	<div class="sketch">
		<h2>Расположение элементов на стенде</h2>
		<div id="designer" style="margin-top: 40px; width: 940px; height: 680px;" onmouseout="ru.octasoft.oem.designer.Main.stopDragging()"></div>
	</div>
</div>

<script type="text/javascript">
    $(document).ready(function () {

        var sketchitems = <?= json_encode(array_values($arResult['SKETCH']['items'])) ?>;
		
		
		/*
         * Обработчики скетча.
         */
		var loadsketch = function() 
		{
			window.addEventListener('touchmove', function (event) {
				event.preventDefault();
			}, false);

			if (typeof window.devicePixelRatio != 'undefined' && window.devicePixelRatio > 2) {
				var meta = document.getElementById('viewport');
				meta.setAttribute('content', 'width=device-width, initial-scale=' + (2 / window.devicePixelRatio) + ', user-scalable=no');
			}
			
			var gridX = <?= (int) ($arResult['PROPS']['width']['VALUE']) ?: 5 ?>;
			var gridY = <?= (int) ($arResult['PROPS']['depth']['VALUE']) ?: 5 ?>;
		
			// compute initial editor's height
			(window.resizeEditor = function(items) {
				$('#designer').height(gridY * 100 + 12);
			})(sketchitems);

			window.onEditorReady = function() {
				ru.octasoft.oem.designer.Main.init({
					w: gridX,
					h: gridY,
					hideControls: true,
					type: '<?= $arResult['PROPS']['standType']['VALUE'] ?>',
					items: sketchitems,
					placedItems: <?= (!empty($arResult['SKETCH']['objects'])) ? (json_encode($arResult['SKETCH']['objects'])) : ('null') ?>
				});
			};
			lime.embed('designer', 0, 0);
		}
		
		
		$.when(loadsketch()).done(function() {
			setTimeout(function() {window.print();}, 1000);
		});
    });
</script>
