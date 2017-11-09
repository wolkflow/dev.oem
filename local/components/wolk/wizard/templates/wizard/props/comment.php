<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<? use Bitrix\Main\Localization\Loc; ?>
<? use Wolk\Core\Helpers\Text as TextHelper; ?>
<? use Wolk\Oem\Basket; ?>

<? $proptmpid = uniqid() ?>
<? $params = (is_object($basketitem)) ? ($basketitem->getParams()) : ([]) ?>
<? $value  = $params[Basket::PARAM_COMMENT] ?>

<? $required = (in_array(Basket::PARAM_COMMENT, $arResult['SECTION_PARAMS'][$section->getID()]['PROPS']['REQUIRED'])) ?>

<div class="js-param-block js-param-comment <?= ($required) ? ('js-param-required') : ('') ?>" data-code="<?= Basket::PARAM_COMMENT ?>">
    <div class="serviceItem__subtitle">
		<?= ($arResult['SECTION_PARAMS'][$section->getID()]['NAMES'][Basket::PARAM_COMMENT]) ?: (Loc::getMessage('COMMENT')) ?>
    </div>
    <textarea name="<?= Basket::PARAM_COMMENT ?>" class="styler js-param-value js-param-x-value" placeholder="<?= Loc::getMessage('COMMENT_PLACEHOLDER') ?>"><?= $value ?></textarea>
</div>