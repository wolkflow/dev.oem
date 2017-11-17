<? use Bitrix\Main\Localization\Loc; ?>

<div class="form-group js-param-block">
    <label class="control-label"><?= Loc::getMessage('HEADER_PROPERTY_COLOR') ?>:</label>
    
    <?  // Получение цветов.
        $result = \Wolk\OEM\Dicts\Color::getList(['order' => ['UF_NUM' => 'ASC', 'UF_SORT' => 'ASC']], false);
        $colors = array();
    ?>
    <input type="hidden" class="js-param-x-value" name="PRODUCTS[<?= $pbid ?>][PROPS][COLOR][ID]" value="<?= $pval['ID'] ?>" />
    <input type="hidden" class="js-param-x-color" name="PRODUCTS[<?= $pbid ?>][PROPS][COLOR][COLOR]" value="<?= $pval['COLOR'] ?>" />
    
    <ul class="center js-colors-palette color-palette">
        <? while ($color = $result->fetch()) { ?>
            <li class="color-item <?= ($pval['ID'] == $color['ID']) ? ('active') : ('') ?>">
                <span 
                    class="js-color-item" 
                    style="background: <?= (!empty($color['UF_BACKGROUND'])) ? ('url('.$color['UF_BACKGROUND'].')') : ('rgb('.$color['UF_CODE'].')') ?>;" 
                    title="<?= $color['UF_NUM'] ?> <?= ($color['UF_LANG_NAME_RU']) ?: ($color['UF_XML_ID']) ?> / sRGB: <?= $color['UF_CODE'] ?>"
                    data-id="<?= $color['ID'] ?>"
                ></span>
            </li>
        <? } ?>
    </ul>
 </div>
 
<style>
    .color-palette {
        width: 98%;
        overflow: hidden;
        margin: 0px;
        padding: 0px;        
    }
    
    .color-palette .color-item {
        list-style: none;
        width: 30px;
        height: 30px;
        float: left;
        margin: 0 0 11px 14px;
    }
    
    .color-palette .color-item span {
        width: 30px;
        height: 30px;
        display: block;
        margin-bottom: 4px;
        box-sizing: border-box;
        cursor: pointer;
        border: 1px #7f7f7f dotted;
        border-radius: 3px;
    }
    
    .color-palette .color-item.active span {
        border: 3px #000000 solid;
    }
    
    .button-prop-color {
        margin-top: 10px;
        position: relative;
        line-height: 38px;
        font-size: 16px;
        font-weight: bold;
        text-transform: uppercase;
        text-align: center;
        color: #fff;
        background: #7f7f7f;
        cursor: pointer;
        border: 0;
        border-radius: 5px;
        width: 100%;
    }
</style>