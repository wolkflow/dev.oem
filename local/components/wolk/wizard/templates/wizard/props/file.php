<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<? use Bitrix\Main\Localization\Loc; ?>
<? use Wolk\Core\Helpers\Text as TextHelper; ?>
<? use Wolk\Oem\Basket; ?>

<? $proptmpid = uniqid() ?>
<? $params = (is_object($basketitem)) ? ($basketitem->getParams()) : ([]) ?>
<? $value  = $params[Basket::PARAM_FILE] ?>

<? $required = (in_array(Basket::PARAM_FILE, $arResult['SECTION_PARAMS'][$section->getID()]['PROPS']['REQUIRED'])) ?>

<div class="js-param-block js-param-file <?= ($required) ? ('js-param-required') : ('') ?>" data-code="<?= Basket::PARAM_FILE ?>">
    <input type="hidden" name="<?= Basket::PARAM_FILE ?>" class="styler js-param-required js-param-value js-param-x-file" value="<?= $value ?>" />
    <div class="serviceItem__right-large">
        <div class="serviceItem__subtitle">
			<?= ($arResult['SECTION_PARAMS'][$section->getID()]['NAMES'][Basket::PARAM_FILE]) ?: (Loc::getMessage('FILE')) ?>
        </div>
        <div class="preview-image js-param-x-image <?= (!empty($value)) ? ('changed') : ('') ?>" <?= (empty($value)) ? ('style="display: none;"') : ('') ?>>
            <? if (!empty($value)) { ?>
                <? $file = CFile::getByID($value); ?>
                <? $path = CFile::getPath($value); ?>
                <a href="<?= $path ?>" target="_blank">
                    <? if (in_array(strtolower(pathinfo($path, PATHINFO_EXTENSION)), ['png', 'jpeg', 'jpg', 'gif'])) { ?>
                        <img width="56" height="56" src="<?= $path ?>" />
                    <? } else { ?>
                        <img width="56" height="56" src="/local/templates/.default/build/images/download.png" />
                    <? } ?>
                </a>
            <? } ?>
        </div>
        <input type="file" class="styler js-param-x-upload" />
    </div>
</div>