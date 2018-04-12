<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<? use Bitrix\Main\Localization\Loc; ?>
<? use Wolk\Core\Helpers\Text as TextHelper; ?>
<? use Wolk\Oem\Basket; ?>

<? $proptmpid = uniqid() ?>
<? $params = (is_object($basketitem)) ? ($basketitem->getParams()) : ([]) ?>
<? $value  = $params[Basket::PARAM_COLOR] ?>

<? $required = (in_array(Basket::PARAM_COLOR, $arResult['SECTION_PARAMS'][$section->getID()]['PROPS']['REQUIRED'])) ?>

<div class="js-param-block js-param-color <?= ($required) ? ('js-param-required') : ('') ?>" data-code="<?= Basket::PARAM_COLOR ?>">
    <input class="js-param-value js-param-x-value" name="<?= Basket::PARAM_COLOR ?>.ID" type="hidden" value="<?= (!empty($value)) ? ($value['ID']) : ('') ?>" />
    <input class="js-param-value js-param-x-color" name="<?= Basket::PARAM_COLOR ?>.COLOR" type="hidden" value="<?= (!empty($value)) ? ($value['COLOR']) : ('') ?>" />

    <div class="serviceCol serviceColor lamColor">
        <div class="serviceItem__subtitle">
			<?= ($arResult['SECTION_PARAMS'][$section->getID()]['NAMES'][Basket::PARAM_COLOR]) ?: (Loc::getMessage('COLOR')) ?>
		</div>
		
		<div class="js-color-indicator color-indicator" data-indicator="#js-color-popup-<?= $proptmpid ?>-id" style="<?= (!empty($value)) ? ('background:' . $value['COLOR'] . ';') : ('') ?>"></div>
		<button class="js-button-param styler itemColor__custom customizable" data-modal="#js-color-popup-<?= $proptmpid ?>-id">
            <? if (!empty($params[Basket::PARAM_COLOR])) { ?>
                <?= Loc::getMessage('CHANGE_COLOR') ?>
            <? } else { ?>
                <?= Loc::getMessage('CHOOSE_COLOR') ?>
            <? } ?>
        </button>
		
        <div class="js-color-title itemColor__custom-name" data-title="#js-color-popup-<?= $proptmpid ?>-id">
			<? if (!empty($value['ID'])) { ?>
				<?= htmlspecialchars(($arResult['COLORS'][$value['ID']]['UF_LANG_NAME_'.$arResult['LANG']]) ?: ($arResult['COLORS'][$value['ID']]['UF_XML_ID'])) ?>,
				<?= $arResult['COLORS'][$value['ID']]['UF_NUM'] ?>
			<? } ?>
		</div>
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
                            <span class="js-color-item" style="background: <?= (!empty($item['UF_BACKGROUND'])) ? ('url('.\CFile::getPath($item['UF_BACKGROUND']).')') : ('rgb('.$item['UF_CODE'].')') ?>;" data-id="<?= $item['ID'] ?>" data-title="<?= htmlspecialchars(($item['UF_LANG_NAME_'.$arResult['LANG']]) ?: ($item['UF_XML_ID'])) ?>, <?= $item['UF_NUM'] ?>"></span>
                            <div class="colorTip">
                                <?= $item['UF_NUM'] ?>
                                <?= ($item['UF_LANG_NAME_'.$arResult['LANG']]) ?: ($item['UF_XML_ID']) ?>
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
