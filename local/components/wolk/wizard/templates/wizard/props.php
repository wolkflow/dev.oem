<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<? use Bitrix\Main\Localization\Loc; ?>
<? use Wolk\Core\Helpers\Text as TextHelper ?>
<? use Wolk\Oem\Basket; ?>

<? // Обработка свойств товара. // ?>
<? foreach ($properties as $property) { ?>
	<div class="js-property-block">
		<? if ($property == Basket::PARAM_COLOR) { ?>
			<? include ($_SERVER['DOCUMENT_ROOT'] . $this->getFolder() . '/props/color.php') ?>
		<? } ?>
		<? if ($property == Basket::PARAM_FORM_HANGING_STRUCTURE) { ?>
			<? include ($_SERVER['DOCUMENT_ROOT'] . $this->getFolder() . '/props/form-hanging-structure.php') ?>
		<? } ?>
		<? if ($property == Basket::PARAM_FILE) { ?>
			<? include ($_SERVER['DOCUMENT_ROOT'] . $this->getFolder() . '/props/file.php') ?>
		<? } ?>
		<? if ($property == Basket::PARAM_LINK) { ?>
			<? include ($_SERVER['DOCUMENT_ROOT'] . $this->getFolder() . '/props/link.php') ?>
		<? } ?>
		<? if ($property == Basket::PARAM_COMMENT) { ?>
			<? include ($_SERVER['DOCUMENT_ROOT'] . $this->getFolder() . '/props/comment.php') ?>
		<? } ?>
	</div>
<? } ?>