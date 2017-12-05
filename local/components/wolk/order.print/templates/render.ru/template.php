<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>

<? use Bitrix\Main\Localization\Loc; ?>

<? $lang = strtoupper(\Bitrix\Main\Context::getCurrent()->getLanguage()) ?>

<? // скетча логотип, название выставки, тип и размер стенда, общий метраж. ?>


<div class="personal-order-print">
	<div class="logo">
		<? $src = CFile::getPath($arResult['EVENT']['PROPS']['LANG_LOGO_' . $lang]['VALUE']) ?>
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
			<div>Участник: <b><?= $arResult['USER']['WORK_COMPANY'] ?></b></div>
			<div>Телефон: <?= $arResult['USER']['PERSONAL_PHONE'] ?></div>
		</div>
		
		<div>
			<h3>Свойства стенда</h3>
			<ul>
				<li>
					Тип стенда: <b><?= Loc::getMessage('TYPE_' . strtoupper($arResult['ORDER']['PROPS']['SFORM']['VALUE'])) ?></b>
				</li>
				<li>
					Размер стенда: 
					<b><?= $arResult['ORDER']['PROPS']['WIDTH']['VALUE'] ?> &times; <?= $arResult['ORDER']['PROPS']['DEPTH']['VALUE'] ?></b>
				</li>
				<li>
					Общий метраж: 
					<b><?= $arResult['ORDER']['PROPS']['WIDTH']['VALUE'] * $arResult['ORDER']['PROPS']['DEPTH']['VALUE'] ?> м<sup>2</sup></b>
				</li>
			</ul>
		</div>
		
		<hr/><br/>
		
		<? $src = CFile::getPath($arResult['ORDER']['PROPS']['SKETCH_FILE']['VALUE']) ?>
		<? if (is_readable($_SERVER['DOCUMENT_ROOT'] . $src)) { ?>
			<div class="center">
				<img src="/i.php?src=<?= $src ?>&h=500" style="max-height: 500px; max-width: 72%;" />
			</div>
		<? } ?>
		
		<? $renders = array_filter((array) unserialize($arResult['ORDER']['PROPS']['RENDERS']['VALUE'])) ?>
		<? if (!empty($renders)) { ?>
			<? foreach ($renders as $src) { ?>
				<? if (is_readable($_SERVER['DOCUMENT_ROOT'] . $src)) { ?>
					<div class="center">
						<img src="/i.php?src=<?= $src ?>&h=450" />
					</div>
				<? } ?>
			<? } ?>
		<? } ?>
	</section>
</div>