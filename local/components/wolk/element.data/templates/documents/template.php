<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>

<? $context  = \Bitrix\Main\Application::getInstance()->getContext(); ?>
<? $language = strtoupper($context->getLanguage()); ?>

<? if (!empty($arResult['DOCUMENTS'])) { ?>
	<div class="generalInfoContent">
		<? $i = 1 ?>
		<? foreach ($arResult['DOCUMENTS'] as $document) { ?>
			<div>
				<div class="modalTitle"><?= $i++ ?>. <?= $document['TITLE'] ?></div>
				<?= $document['HTML'] ?>
			</div>
		<? } ?>
	</div>
<? } ?>
