<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<? use Bitrix\Main\Localization\Loc; ?>
<? use Wolk\Core\Helpers\Text as TextHelper; ?>
<? use Wolk\Oem\Basket; ?>

<? $proptmpid = uniqid() ?>
<? $params = (is_object($basketitem)) ? ($basketitem->getParams()) : ([]) ?>
<? $value  = $params[Basket::PARAM_TEXT] ?>

<div class="js-param-block" data-code="<?= Basket::PARAM_TEXT ?>">
    <div class="serviceItem__left">
        <div class="serviceItem__subtitle">
            <?= Loc::getMessage('TEXT') ?>
        </div>
        <div class="itemText_custom">
            <input class="js-param-required js-param-value js-param-x-value styler" name="<?= Basket::PARAM_TEXT ?>" type="text" value="<?= $value ?>" />
        </div>
    </div>
</div>
