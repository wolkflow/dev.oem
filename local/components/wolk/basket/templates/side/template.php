<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<? $this->setFrameMode(true); ?>

<? $component = $this->getComponent() ?>

<? use Bitrix\Main\Localization\Loc; ?>
<? use Wolk\Core\Helpers\Text as TextHelper ?>

<div class="basketcontainer__title customizable_border">
    <?= Loc::getMessage('BASKET') ?>
</div>

<div class="basketcontainer__itemscontainer customizable_border">
    
    <div class="basketcontainer__itemcontainer customizable_border">
        <div class="basketcontainer__itemname">
            InterStellar
        </div>
        <div class="basketcontainer__itemtotalprice">
            $2400
        </div>
        <div class="basketcontainer__itemprice">
            5m &times; 4m
        </div>
    </div>
    
    <? foreach ($arResult['ITEMS'] as $item) { ?>
        <? $product = $arResult['PRODUCTS'][$item['pid']] ?>
        
        <? if (empty($product)) continue; ?>
        
        <div class="basketcontainer__itemcontainer customizable_border">
            <div class="basketcontainer__itemname">
                <?= $product->getTitle() ?>
            </div>
            <div class="basketcontainer__itemtotalprice">
                <?= FormatCurrency($item['cost'], $arResult['CURRENCY']) ?>
            </div>
            <div class="basketcontainer__itemprice">
                <?= FormatCurrency($item['price'], $arResult['CURRENCY']) ?> &times; <?= $item['quantity'] ?>
            </div>
        </div>
    <? } ?>
    
    <div class="basketcontainer__totalpricecontainer" v-show="totalPrice">
        <div class="basketcontainer__totalpricecontainertitle">
            <?= Loc::getMessage('TOTAL_PRICE') ?>*:
        </div>
        <div class="basketcontainer__totalpricecontainercount">
            <?= FormatCurrency($arResult['BASKET']->getPrice(), $arResult['CURRENCY']) ?>
        </div>
        <small>
            <? if ($arResult['EVENT']->hasVAT()) { ?>
                <?= Loc::getMessage('TAX_INCLUDED') ?>
            <? } else { ?>
                <?= Loc::getMessage('TAX_EXCLUDED') ?>
            <? } ?>
        </small>
    </div>
    
    <div class="navButtons">
        <a href="javascript:void(0)" class="button styler prev">
            <?= Loc::getMessage('BACK') ?>
        </a>
        <div class="basketcontainer__nextstepbutton">
            <?= Loc::getMessage('NEXT') ?>
        </div>
    </div>
</div>