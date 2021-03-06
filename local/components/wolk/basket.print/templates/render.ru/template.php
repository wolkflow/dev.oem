<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>

<? use Bitrix\Main\Localization\Loc; ?>

<? $lang = $arResult['LANGUAGE'] ?>

<div class="personal-renders-print">
	<div class="more">
		<section>
			<div class="logo">
				<? $src = CFile::getPath($arResult['EVENT']['PROPS']['LANG_LOGO_' . $lang]['VALUE']) ?>
				<? if (is_readable($_SERVER['DOCUMENT_ROOT'] . $src)) { ?>
					<img src="http://<?= $arResult['SERVER_NAME'] ?>/i.php?src=<?= $src ?>&h=100" />
				<? } ?>
			</div>
			<div class="center">
				<h1><?= $arResult['EVENT']['PROPS']['LANG_TITLE_' . $lang]['VALUE'] ?></h1>
				<b><?= $arResult['EVENT']['PROPS']['LANG_LOCATION_' . $lang]['VALUE'] ?></b>
				<? if (!empty($arResult['EVENT']['PROPS']['DATE_BEGIN']['VALUE']) && !empty($arResult['EVENT']['PROPS']['DATE_FINISH']['VALUE'])) { ?>
					<br/>
					Даты выставки: 
					<?= $arResult['EVENT']['PROPS']['DATE_BEGIN']['VALUE'] ?> &mdash; <?= $arResult['EVENT']['PROPS']['DATE_FINISH']['VALUE'] ?>
				<? } ?>
			</div>
		</section>
		<section>
			<table class="info-table" width="100%">
				<thead>
					<tr>
						<th>Компания</th>
						<th>Тип стенда</th>
						<th>Размер стенда</th>
						<th>Общий метраж</th>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>
							<?= $arResult['USER']['WORK_COMPANY'] ?>
						</td>
						<td>
							<?= Loc::getMessage('TYPE_' . strtoupper($arResult['BASKET']['PARAMS']['SFORM'])) ?>
						</td>
						<td>
							<?= $arResult['BASKET']['PARAMS']['WIDTH'] ?> &times; <?= $arResult['BASKET']['PARAMS']['DEPTH'] ?>
						</td>
						<td>
							<?= $arResult['BASKET']['PARAMS']['WIDTH'] * $arResult['BASKET']['PARAMS']['DEPTH'] ?> м<sup>2</sup>
						</td>
					</tr>
				</tbody>
			</table>
			
			<br/>
			
			<? $image = $arResult['BASKET']['SKETCH']['SKETCH_IMAGE'] ?>
			<? if (!empty($image)) { ?>
				<div class="center">
					<img src="data:image/png;base64, <?= $image ?>" style="max-height: 500px; max-width: 72%;" />
					<br/>
					<span>Масштаб: 1 клетка равна 1 м<sup>2</sup></span>
				</div>
				<hr/>
				<div class="legend">
					<? if (!empty($arResult['FASCIA'])) { ?>
						<h3>Надпись на фриз:</h3>
						<? foreach ($arResult['FASCIA'] as $item) { ?>
							<? $color = $arResult['COLORS'][$item['params']['COLOR']['ID']] ?>
							<div>
								<b><?= $item['params']['TEXT'] ?></b> (<?= $color['UF_XML_ID'] ?>, <?= $color['UF_NUM'] ?>)
							</div>
						<? } ?>
						<br/>
					<? } ?>
					
					<div class="legend-items">
						<h2>Условные обозначения:</h2>
						<? foreach ($arResult['BASKET']['PRODUCTS'] as $basket) { ?>
							<? $product = new Wolk\OEM\Products\Base($basket['pid']) ?>
							<? if ($product->isSketchShow()) { ?>
								<div class="legend-item">
									<div class="legend-image">
										<img src="http://<?= $arResult['SERVER_NAME'] ?>/i.php?src=<?= $product->getSketchImageSrc() ?>&h=40" style="max-width: 100px;" />
									</div>
									<div class="title"><?= $product->getTitle($lang) ?></div>
								</div>
							<? } ?>
						<? } ?>
					</div>
				</div>
			<? } ?>
		</section>
	</div>
	<div style="clear:both;"></div>
	
	<? $renders = array_filter((array) $arResult['BASKET']['RENDERS']) ?>
	<? if (!empty($renders)) { ?>
		<? $chunks = array_chunk($renders, 2) ?>
		<? $i = 1 ?>
		<? foreach ($chunks as $renders) { ?>
			<section class="<?= ($i++ == 1) ? ('more') : ('') ?>">
				<div class="logo">
					<? $src = CFile::getPath($arResult['EVENT']['PROPS']['LANG_LOGO_' . $lang]['VALUE']) ?>
					<? if (is_readable($_SERVER['DOCUMENT_ROOT'] . $src)) { ?>
						<img src="http://<?= $arResult['SERVER_NAME'] ?>/i.php?src=<?= $src ?>&h=100" />
					<? } ?>
				</div>
				<div class="center">
					<h1><?= $arResult['EVENT']['PROPS']['LANG_TITLE_' . $lang]['VALUE'] ?></h1>
					<b><?= $arResult['EVENT']['PROPS']['LANG_LOCATION_' . $lang]['VALUE'] ?></b>
				</div>
				<div style="clear:both;"></div>
				<? foreach ($renders as $render) { ?>
					<? if (is_readable($_SERVER['DOCUMENT_ROOT'] . $render)) { ?>
						<div class="center">
							<img src="http://<?= $arResult['SERVER_NAME'] ?>/i.php?src=<?= $render ?>&h=520" />
						</div>
					<? } ?>
				<? } ?>
			</section>
		<? } ?>
	<? } ?>
</div>
