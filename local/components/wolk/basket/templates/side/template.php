<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<? $this->setFrameMode(true); ?>

<? $component = $this->getComponent() ?>

<? use Bitrix\Main\Localization\Loc; ?>
<? use Wolk\Core\Helpers\Text as TextHelper ?>


<? if (!is_null($arResult['STAND'])) { ?>
    <div class="basketcontainer__itemcontainer customizable_border">
        <div class="basketcontainer__itemname">
            <?= $arResult['STAND']->getElement()->getTitle() ?>
        </div>
        <div class="basketcontainer__itemtotalprice">
            <?= FormatCurrency($arResult['STAND']->getCost(), $arResult['CURRENCY']) ?>
        </div>
        <div class="basketcontainer__itemprice">
            <?= $arResult['STAND']->getParam('width') ?>m &times; <?= $arResult['STAND']->getParam('depth') ?>m
        </div>
    </div>
<? } ?>

<? if (!empty($arResult['ITEMS']))  { ?>
    <? foreach ($arResult['ITEMS'] as $item) { ?>
        <? $product = $item->getElement() ?>
        
        <? if (empty($product)) continue; ?>
        
        <div class="basketcontainer__itemcontainer customizable_border">
            <div class="basketcontainer__itemname">
                <?= $product->getTitle() ?>
            </div>
            <div class="basketcontainer__itemtotalprice">
                <?= FormatCurrency($item->getCost(), $arResult['CURRENCY']) ?>
            </div>
            <div class="basketcontainer__itemprice">
                <?= FormatCurrency($item->getPrice(), $arResult['CURRENCY']) ?> &times; <?= $item->getQuantity() ?>
            </div>
        </div>
    <? } ?>
<? } ?>

<div class="basketcontainer__totalpricecontainer">
    <div class="basketcontainer__totalpricecontainertitle">
        <?= Loc::getMessage('TOTAL_PRICE') ?>*:
    </div>
    <div class="basketcontainer__totalpricecontainercount">
        <?= FormatCurrency($arResult['PRICE'], $arResult['CURRENCY']) ?>
    </div>
    <small>
        <? if ($arResult['EVENT']->hasVAT()) { ?>
            <?= Loc::getMessage('TAX_INCLUDED') ?>
        <? } else { ?>
            <?= Loc::getMessage('TAX_EXCLUDED') ?>
        <? } ?>
    </small>
</div>
    
    