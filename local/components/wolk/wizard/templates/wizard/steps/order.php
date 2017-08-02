<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<? $this->setFrameMode(true); ?>

<? use Bitrix\Main\Localization\Loc; ?>
<? use Wolk\Core\Helpers\Text as TextHelper ?>

<pre>
    <? // print_r($arResult['STEPLINKS']) ?>
</pre>

<div id="step">
    <div class="orderpage">
        <div class="pagetitle"><?= Loc::getMessage('ORDER') ?></div>
        <div class="pagedescription">
            <? Helper::includeFile('orderpage_textdesc_' . \Bitrix\Main\Context::getCurrent()->getLanguage()) ?>
        </div>
        <div class="ordercontainer">
            <div class="ordercontainer__columnscontainer">
                <div class="ordercontainer__column right">
                    
                    <? // Оборудование. // ?>
                    <div class="pagesubtitle">
                        <?= Loc::getMessage('TITLE_EQUIPMENT') ?>
                        <div class="pagesubtitle__addbutton customizable" onclick="javascript: location.href = '<?= $arResult['STEPLINKS']['equipments'] ?>';"></div>
                    </div>
                    <? if (!empty($arResult['PRODUCTS'][Wolk\OEM\Products\Section::TYPE_EQUIPMENTS])) { ?>
                        <? foreach ($arResult['PRODUCTS'][Wolk\OEM\Products\Section::TYPE_EQUIPMENTS] as $item) { ?>
                            <div class="ordercontainer__itemscontainer js-product-block">
                                <div class="pagesubsubtitle">
                                    <?= $item['ITEM']->getTitle() ?>
                                </div>
                                <div class="ordercontainer__item" v-for="item in items">
                                    <div class="ordercontainer__itemtotalprice">
                                        <?= FormatCurrency($item['BASKET']->getCost(), $arResult['CURRENCY']) ?>
                                    </div>
                                    <div class="ordercontainer__itemname">
                                        <?= $item['ITEM']->getTitle() ?> | 
                                        <?= FormatCurrency($item['BASKET']->getPrice(), $arResult['CURRENCY']) ?>
                                        &times;
                                        <?= $item['BASKET']->getQuantity() ?>
                                    </div>
                                    <div class="ordercontainer__changebutton">
                                        <a href="<?= $arResult['STEPLINKS']['equipments'] ?>">
                                            <?= Loc::getMessage('CHANGE') ?>
                                        </a>
                                        |
                                        <a href="javascript:void(0)" class="js-basket-delete" data-bid="<?= $item['BASKET']->getID() ?>">
                                            <?= Loc::getMessage('DELETE') ?>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <? } ?>
                    <? } ?>
                </div>
                
                <div class="ordercontainer__column">
                
                    <? // Стенды. // ?>
                    <? if ($arResult['CONTEXT']->getType() != Wolk\OEM\Context::TYPE_INDIVIDUAL && !empty($arResult['STAND'])) { ?>
                        <div class="pagesubtitle">
                            <?= Loc::getMessage('STAND_TYPE') ?>
                        </div>
                        <div class="ordercontainer__itemscontainer">
                            <div class="pagesubsubtitle">
                                <?= Loc::getMessage('SYSTEM_STAND') ?>
                            </div>
                            <div class="last ordercontainer__item">
                                <div class="ordercontainer__itemtotalprice">
                                    <?= FormatCurrency($arResult['STAND']['BASKET']->getCost(), $arResult['CURRENCY']) ?>
                                </div>
                                <div class="ordercontainer__itemname">
                                    <?= $arResult['STAND']['ITEM']->getTitle() ?>
                                </div>
                                <div class="ordercontainer__changebutton">
                                    <a href="<?= $arResult['STEPLINKS']['stands'] ?>">
                                        <?= Loc::getMessage('CHANGE') ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                    <? } ?>
                    
                    <? // Услуги. // ?>
                    <div class="pagesubtitle">
                        <?= Loc::getMessage('TITLE_SERVICES') ?>
                        <div class="pagesubtitle__addbutton customizable" onclick="javascript: location.href = '<?= $arResult['STEPLINKS']['services'] ?>';"></div>
                    </div>
                    <? if (!empty($arResult['PRODUCTS'][Wolk\OEM\Products\Section::TYPE_SERVICES])) { ?>
                        <? foreach ($arResult['PRODUCTS'][Wolk\OEM\Products\Section::TYPE_SERVICES] as $item) { ?>
                            <div class="ordercontainer__itemscontainer js-product-block">
                                <div class="pagesubsubtitle">
                                    <?= $item['ITEM']->getSection()->getTitle() ?>
                                </div>
                                <div class="ordercontainer__item">
                                    <div class="ordercontainer__itemtotalprice">
                                        <?= FormatCurrency($item['BASKET']->getCost(), $arResult['CURRENCY']) ?>
                                    </div>
                                    <div class="ordercontainer__itemname">
                                        <?= $item['ITEM']->getTitle() ?> | 
                                        <?= FormatCurrency($item['BASKET']->getPrice(), $arResult['CURRENCY']) ?> 
                                            &times;
                                        <?= $item['BASKET']->getQuantity() ?>
                                    </div>
                                    <div class="ordercontainer__changebutton">
                                        <a href="<?= $arResult['STEPLINKS']['services'] ?>">
                                            <?= Loc::getMessage('CHANGE') ?>
                                        </a>
                                        |
                                        <a href="javascript:void(0)" class="js-basket-delete" data-bid="<?= $item['BASKET']->getID() ?>">
                                            <?= Loc::getMessage('DELETE') ?>
                                        </a>
                                    </div>
                                </div>
                            </div>
                        <? } ?>
                    <? } ?>
                </div>
            </div>
        </div>
        
        
        <? // Стоимость заказа. // ?>
        <div class="ordertotalcontainer">
            <div class="ordertotalcontainer__standandpavillion">
                <div class="ordertotalcontainer__standcontainer">
                    <div class="ordertotalcontainer__title">
                        <?= Loc::getMessage('STAND') ?> №
                    </div>
                    <div class="ordertotalcontainer__number">
                        <input type="text" name="STAND_NUMBER" value="0" />
                    </div>
                </div>
                <div class="ordertotalcontainer__pavillioncontainer">
                    <div class="ordertotalcontainer__title">
                        <?= Loc::getMessage('PAVILION') ?>
                    </div>
                    <div class="ordertotalcontainer__number">
                        <input type="text" name="PAVILION" value="0" />
                    </div>
                </div>
                <div class="ordertotalcontainer__placeorder" data-modal="<?= ($USER->IsAuthorized()) ? ('#placeLogin') : ('#placeUnlogin') ?>">
                    <?= Loc::getMessage('PLACE_ORDER') ?>
                </div>
            </div>
            <div class="ordertotalcontainer__total" v-show="summaryPrice">
                <?= Loc::getMessage('PRICE_TOTAL') ?>: 
                <span>
                    <?= FormatCurrency($arResult['PRICES']['PRICE'], $arResult['CURRENCY']) ?>
                </span>
            </div>
            
            <? if ($arResult['PRICES']['SURCHARGE_PRICE'] > 0) { ?>
                <div class="ordertotalcontainer__surcharge">
                    <?= Loc::getMessage('SURCHRGE') ?>: 
                    <span>
                        <?= FormatCurrency($arResult['PRICES']['SURCHARGE_PRICE'], $arResult['CURRENCY']) ?>
                    </span>
                </div>
                <div class="ordertotalcontainer__total">
                    <?= Loc::getMessage('PRICE_TOTAL_WITH_VAT') ?>: 
                    <span>
                        <?= FormatCurrency($arResult['PRICES']['SUMMARY'], $arResult['CURRENCY']) ?>
                    </span>
                </div>
            <? } ?>
            
            <div class="ordertaxcontainer__total">
                <?= Loc::getMessage('VAT') ?>:
                <span>
                    <?= FormatCurrency($arResult['PRICES']['VAT_PRICE'], $arResult['CURRENCY']) ?>
                </span>
            </div>
            
            <div class="ordertotalcontainer__surchargetotal" v-show="totalPrice">
                <? if ($arResult['PRICES']['SURCHARGE_PRICE'] > 0) { ?>
                    <div class="ordertotalcontainer__surchargetotaltitle">
                        <?= Loc::getMessage('PRICE_TOTAL_WITH_SURCHARGE') ?>:
                    </div>
                <? } else { ?>
                    <div class="ordertotalcontainer__surchargetotaltitle">
                        <?= Loc::getMessage('PRICE_TOTAL_WITH_VAT') ?>:
                    </div>
                <? } ?>
                <div class="ordertotalcontainer__surchargetotalcount">
                    <?= FormatCurrency($arResult['PRICES']['SUMMARY'], $arResult['CURRENCY']) ?>
                </div>
            </div>
        </div>
    </div>
</div>
