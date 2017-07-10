<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<? use Bitrix\Main\Localization\Loc; ?>
<? use Wolk\Core\Helpers\Text as TextHelper; ?>
<? use Wolk\Oem\Basket; ?>

<? $proptmpid = uniqid() ?>
<? $params = (is_object($basketitem)) ? ($basketitem->getParams()) : ([]) ?>
<? $value  = $params[Basket::PARAM_COLOR] ?>

<div class="js-param-block" data-code="<?= Basket::PARAM_COLOR ?>">

    <input class="js-param-required js-param-value js-param-x-value" name="<?= Basket::PARAM_COLOR ?>.ID" type="hidden" value="<?= (!empty($value)) ? ($value['ID']) : ('') ?>" />
    <input class="js-param-required js-param-value js-param-x-color" name="<?= Basket::PARAM_COLOR ?>.COLOR" type="hidden" value="<?= (!empty($value)) ? ($value['COLOR']) : ('') ?>" />

    <div class="serviceItem__col-7 lamColor">
        <div class="serviceItem__subtitle">
            <?= Loc::getMessage('COLOR') ?>
        </div>
        <button class="js-button-param styler itemColor__custom" data-modal="#js-color-popup-<?= $proptmpid ?>-id" style="<?= (!empty($value)) ? ('background:' . $value['COLOR'] . ';') : ('') ?>">
            <? if (!empty($params[Basket::PARAM_COLOR])) { ?>
                <?= Loc::getMessage('CHANGE_COLOR') ?>
            <? } else { ?>
                <?= Loc::getMessage('CHOOSE_COLOR') ?>
            <? } ?>
        </button>
        <div class="js-color-title itemColor__custom-name"></div>
    </div>
    
    <? // Модальное окно для выбора цвета // ?>
    <div class="hide">
        <div class="modal" id="js-color-popup-<?= $proptmpid ?>-id">
            <div class="modalClose arcticmodal-close"></div>
            <div class="modalTitle">
                <?= Loc::getMessage('CHOOSING_COLOR') ?>
            </div>
            <div class="colorsArray">
                <ul class="js-colors-palette">
                    <? foreach ($arResult['COLORS'] as $item) { ?>
                        <li <?= ($value['ID'] == $item['ID']) ? ('active') : ('') ?>>
                            <span class="js-color-item" style="background: <?= (!empty($item['UF_BACKGROUND'])) ? ('url('.$item['UF_BACKGROUND'].')') : ('rgb('.$item['UF_CODE'].')') ?>;" data-id="<?= $item['ID'] ?>"></span>
                            <div class="colorTip">
                                <?= $item['UF_NUM'] ?>
                                <?= ($item['UF_LANG_NAME_'.$arResult['LANG']]) ?: ($item['UF_XML_ID'])  ?>
                                <br>
                                <b>sRGB:</b> 
                                <?= $item['UF_CODE'] ?>
                            </div>
                            <div class="colorTitle">
                                <?= $item['UF_NUM'] ?>
                            </div>
                        </li>
                    <? } ?>
                </ul>
                <div class="colorsNote">
                    <?= Loc::getMessage('COLOR_NOTE') ?>
                </div>
            </div>
        </div>
    </div>
</div>
