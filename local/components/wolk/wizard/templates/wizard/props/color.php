<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<? use Bitrix\Main\Localization\Loc; ?>
<? use Wolk\Core\Helpers\Text as TextHelper ?>


<div class="js-property js-property-color">
    <input name="color" type="hidden" value="" />
    <div class="serviceItem__col-7 lamColor">
        <div class="serviceItem__subtitle">
            <?= Loc::getMessage('COLOR') ?>
        </div>
        <button class="styler itemColor__custom" data-modal="#js-color-popup-id">
            <? $params = $basketitem->getParams() ?>
            <? if (!empty($params['COLOR'])) { ?>
                <?= Loc::getMessage('CHANGE_COLOR') ?>
            <? } else { ?>
                <?= Loc::getMessage('CHOOSE_COLOR') ?>
            <? } ?>
        </button>
        <div class="js-color-title itemColor__custom-name"></div>
    </div>
</div>

<? // Модальное окно для выбора цвета // ?>
<div class="hide">
    <div class="modal" id="js-color-popup-id">
        <div class="modalClose arcticmodal-close"></div>
        <div class="modalTitle">
            <?= Loc::getMessage('CHOOSING_COLOR') ?>
        </div>
        <div class="colorsArray">
            <ul>
                <? foreach ($arResult['COLORS'] as $color) { ?>
                    <li>
                        <span style="background: <?= (!empty($color['UF_BACKGROUND'])) ? ('url('.$color['UF_BACKGROUND'].')') : ('rgb('.$color['UF_CODE'].')') ?>;"></span>
                        <div class="colorTip">
                            <?= $color['UF_NUM'] ?>
                            <?= ($color['UF_LANG_NAME_'.$arResult['LANG']]) ?: ($color['UF_XML_ID'])  ?>
                            <br>
                            <b>sRGB:</b> 
                            <?= $color['UF_CODE'] ?>
                        </div>
                        <div class="colorTitle">
                            <?= $color['UF_NUM'] ?>
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