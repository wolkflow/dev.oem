<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>

<? use Bitrix\Main\Localization\Loc; ?>

<? $lang = strtoupper(\Bitrix\Main\Context::getCurrent()->getLanguage()) ?>


<div class="personal-order-print">
	<div class="logo">
		<? $src = CFile::getPath($arResult['BASKET']['PROPS']['LANG_LOGO_' . $lang]['VALUE']) ?>
		<? if (is_readable($_SERVER['DOCUMENT_ROOT'] . $src)) { ?>
			<img src="/i.php?src=<?= $src ?>&h=100" />
		<? } ?>
	</div>
	<section>
		<div class="center">
			<h1><?= $arResult['EVENT']['PROPS']['LANG_TITLE_' . $lang]['VALUE'] ?></h1>
			<b><?= $arResult['EVENT']['PROPS']['LANG_LOCATION_' . $lang]['VALUE'] ?></b>
		</div>
		
		<div>
			<h3>Свойства стенда</h3>
			<ul>
				<li>
					Тип стенда: <b><?= Loc::getMessage('TYPE_' . strtoupper($arResult['BASKET']['PARAMS']['SFORM'])) ?></b>
				</li>
				<li>
					Размер стенда: 
					<b><?= $arResult['BASKET']['PARAMS']['WIDTH'] ?> &times; <?= $arResult['BASKET']['PARAMS']['DEPTH'] ?></b>
				</li>
				<li>
					Общий метраж: 
					<b><?= $arResult['BASKET']['PARAMS']['WIDTH'] * $arResult['BASKET']['PARAMS']['DEPTH'] ?> м<sup>2</sup></b>
				</li>
			</ul>
		</div>
		
		<hr/><br/>
		
		<? $image = CFile::getPath($arResult['BASKET']['SKETCH']['SKETCH_IMAGE']) ?>
		<? if (!empty($image)) { ?>
			<div class="center">
				<img src="data:image/png;base64, <?= $image ?>" style="max-height: 500px; max-width: 72%;" />
			</div>
		<? } ?>
		
		<? $renders = array_filter((array) $arResult['BASKET']['RENDERS']) ?>
		<? if (!empty($renders)) { ?>
			<? foreach ($renders as $render) { ?>
				<? if (is_readable($_SERVER['DOCUMENT_ROOT'] . $render)) { ?>
					<div class="center">
						<img src="/i.php?src=<?= $render ?>&h=450" />
					</div>
				<? } ?>
			<? } ?>
		<? } ?>
	</section>
</div>