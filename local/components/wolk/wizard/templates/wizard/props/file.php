<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<? use Bitrix\Main\Localization\Loc; ?>
<? use Wolk\Core\Helpers\Text as TextHelper; ?>
<? use Wolk\Oem\Basket; ?>

<? $proptmpid = uniqid() ?>
<? $params = (is_object($basketitem)) ? ($basketitem->getParams()) : ([]) ?>
<? $value  = $params[Basket::PARAM_COMMENT] ?>

<div class="js-param-block" data-code="<?= Basket::PARAM_LINK ?>">
    <div class="serviceItem__right-large">
        <div class="serviceItem__subtitle">
            <?= Loc::getMessage('FILE') ?>
        </div>
        <div class="preview-image js-param-preview" style="display: none;">
            <? if (!empty($value)) { ?>
                <? $file = CFile::getByID($value); ?>
                <? $parh = CFile::getPath($value); ?>
                <? if (in_array(strtolower(pathinfo($path, PATHINFO_EXTENSION)), ['png', 'jpeg', 'jpg', 'gif'])) { ?>
                    <img width="60" height="60" src="<?= $path ?>" />
                <? } ?>
            <? } ?>
        </div>
        <input type="file" name="<?= Basket::PARAM_COMMENT ?>" class="styler js-param-value js-param-x-file" />
    </div>
</div>