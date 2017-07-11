<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<? use Bitrix\Main\Localization\Loc; ?>
<? use Wolk\Core\Helpers\Text as TextHelper; ?>
<? use Wolk\Oem\Basket; ?>

<? $proptmpid = uniqid() ?>
<? $params = (is_object($basketitem)) ? ($basketitem->getParams()) : ([]) ?>
<? $value  = $params[Basket::PARAM_LINK] ?>

<div class="js-param-block" data-code="<?= Basket::PARAM_LINK ?>">
    <div class="serviceItem__left">
        <div class="serviceItem__subtitle">
            <?= Loc::getMessage('LINK') ?>
        </div>
        <div class="itemText_custom">
            <input class="js-param-required js-param-value js-param-x-value styler" name="<?= Basket::PARAM_LINK ?>" type="text" value="<?= $value ?>" />
        </div>
    </div>
</div>
