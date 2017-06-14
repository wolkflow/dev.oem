<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<? $this->setFrameMode(true); ?>

<? use Bitrix\Main\Localization\Loc; ?>
<? use Wolk\Core\Helpers\Text as TextHelper ?>

<div class="pagetitle">
    <?= Loc::getMessage('TITLE_EQUIPMENT') ?>
</div>

<div class="main">
    <div id="step">
        <div class="equipmentcontainer">
            <div class="options_group">
                <? foreach ($arResult['ITEMS'] as $group) { ?>
                    <div class="pagesubtitle moduleinited customizable_border open" data-module="pagesubtitle-dropdown">
                        <?= $group->getTitle() ?>
                    </div>
                    <div class="js-section-wrapper pagesubtitleopencontainer">
                        <? $sections = $group->getInsides('SORT') ?>
                        <? foreach ($sections as $section) { ?>
                            <? $pricetype  = $section->getPriceType() ?>
                            <? $properties = $section->getProperties() ?>
                            <? $products   = $section->getInsides() ?>
                        
                            <div class="js-product-section js-pricetype-<?= mb_strtolower($pricetype) ?>" data-bid="" data-sid="<?= $section->getID() ?>" data-pricetype="<?= $pricetype ?>">
                                <div class="serviceItem__title">
                                    <?= $section->getTitle() ?>
                                </div>
                                
                                <? if ($pricetype == 'QUANTITY') { ?>
                                    
                                    <? if (count($products) == 1) { ?>
                                        <? $product = reset($products) ?>
                                        <div class="equipmentcontainer__itemcontainer">
                                            <div class="equipmentcontainer__itemrightside">
                                                <div class="equipmentcontainer__itemprice">
                                                    <?= FormatCurrency($product->getPrice(), $arResult['CURRENCY']) ?>
                                                </div>
                                                <div class="itemquantitycontainer">
                                                    <div class="js-quantity-wrapper itemCount" data-pid="<?= $product->getID() ?>">
                                                        <div class="serviceItem__subtitle">
                                                            <?= Loc::getMessage('QUANTITY') ?>
                                                        </div>
                                                        <div class="js-quantity-dec itemCount__button itemCount__down"></div>
                                                        <div class="js-quantity-inc itemCount__button itemCount__up"></div>
                                                        
                                                        <input id="<?= $product->getID() ?>" type="text" class="js-quantity itemCount__input styler" value="0" />
                                                    </div>
                                                    <? if (array_key_exists($product->getID(), $arResult['BASE'])) { ?>
                                                        <div class="equipmentcontainer__standartnote">
                                                            <?= Loc::getMessage('STANDARD_INCLUDES') ?>
                                                            <b><?= $arResult['BASE'][$product->getID()] ?></b>
                                                        </div>
                                                    <? } ?>
                                                </div>
                                            </div>
                                            <div class="equipmentcontainer__itemleftside">
                                                <div class="equipmentcontainer__itemphotocontainer">
                                                    <a class="photoZoom" href="<?= $product->getImageSrc() ?>"></a>
                                                    <img src="/i?src=<?= $product->getImageSrc() ?>&h=210" class="equipmentcontainer__itemphoto" />
                                                </div>
                                                <div class="equipmentcontainer__itemsize">
                                                    <?= $product->getDescription() ?>
                                                </div>
                                            </div>
                                        </div>
                                    <? } else { ?>
                                        <div class="equipmentcontainer__itemcontainer">
                                            <div class="equipmentcontainer__itemrightside">
                                                <div class="equipmentcontainer__itemprice">
                                                    {{ price | format_currency ' ' currency_format }}
                                                </div>
                                                <? /* // ЦВЕТ
                                                <div class="equipmentcontainer__itemcolorcontainer" v-if="colors">
                                                    <div class="equipmentcontainer__itemcolortitle">
                                                        <?= Loc::getMessage('color') ?>
                                                    </div>
                                                    <div class="equipmentcontainer__itemcolordropdown" v-if="colorsLength > 1">
                                                        <select v-styler="selectedColor" class="styler">
                                                            <option :value="">
                                                                <?= Loc::getMessage('not selected') ?>
                                                            </option>
                                                            <option v-for="(colorValueId, color) in colors" value="{{ colorValueId }}">
                                                                {{ color }}
                                                            </option>
                                                        </select>
                                                    </div>
                                                    <span v-else>
                                                        {{ colors.VALUE }}
                                                    </span>
                                                    <input type="hidden" v-model="selectedColor" :value="colors.ID" />
                                                </div>
                                                */ ?>
                                                <div class="itemquantitycontainer">
                                                    <div class="itemCount">
                                                        <div class="serviceItem__subtitle"><?= Loc::getMessage('quantity') ?></div>
                                                        <div class="itemCount__button itemCount__down" @click="decQty"></div>
                                                        <div class="itemCount__button itemCount__up" @click="incQty"></div>
                                                        <input id="{{section.ID}}_{{item.ID}}" v-model="item.QUANTITY" type="text" class="itemCount__input styler" number :value="item.QUANTITY" />
                                                    </div>
                                                    <div class="equipmentcontainer__standartnote" v-if="item.STANDART > 0">
                                                        <?= Loc::getMessage('eqipment_standart_include') ?> <b>{{ item.STANDART }}</b>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="equipmentcontainer__itemleftside">
                                                <div class="equipmentcontainer__itemphotocontainer">
                                                    <a class="photoZoom" :href="item.PICTURE.BIG"></a>
                                                    <img :src="item.PICTURE.SMALL" class="equipmentcontainer__itemphoto" />
                                                </div>
                                                <div class="equipmentcontainer__itemsize">
                                                    {{{ description }}}
                                                </div>
                                            </div>
                                        </div>
                                    <? } ?>
                                <? } ?>
                            </div>
                            
                            <? if (false) { ?>
                                <div class="serviceItem__block" v-for="selectedItem in selectedItems">
                                    <div class="serviceItem__row">
                                        <div class="serviceItem__left">
                                            <div class="serviceItem__subtitle">
                                                <?= $section->getDescription() ?>
                                            </div>
                                            <select class="styler">
                                                <option value="">
                                                    <?= Loc::getMessage('NOT_SELECTED') ?>
                                                </option>
                                                <option value="{{ item.ID }}" v-for="item in items | orderBy SORT">
                                                    {{ item.NAME }} ({{ item.PRICE | format_currency ' ' currency_format }})
                                                </option>
                                            </select>
                                        </div>
                                        <div class="serviceItem__right">
                                            <div class="itemCount">
                                                <div class="serviceItem__subtitle">
                                                    <?= Loc::getMessage('quantity') ?>
                                                </div>
                                                <div class="itemCount__button itemCount__down" @click="decQty(selectedItem, $index)"></div>
                                                <div class="itemCount__button itemCount__up" @click="incQty(selectedItem, $index)"></div>
                                                <input id="{{section.NAME}}_{{$index}}" type="text" v-model="selectedItem.QUANTITY" class="itemCount__input styler" number />
                                            </div>
                                        </div>
                                        <div style="margin-top: 10px;">
                                            <div class="serviceItem__cost">
                                                <div class="serviceItem__subtitle">
                                                    <?= Loc::getMessage('PRICE') ?>
                                                </div>
                                                <div class="serviceItem__cost-value">
                                                    {{ section.ITEMS[selectedItem.ID].PRICE | format_currency ' ' currency_format}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <? } ?>
                            
                            <? // Добавление типа товара // ?>
                            <? if (!empty($properties)) { ?>
                                <div class="serviceItem__bottom">
                                    <a href="javascript:void(0);" class="js-more-field itemAdd_field">
                                        <span><?= Loc::getMessage('MORE') ?></span>
                                    </a>
                                </div>
                            <? } ?>
                        <? } ?>
                    </div>
                <? } ?>
            </div>
        </div>
    </div>
    <div style="clear:both;"></div>
</div>

<aside class="siteAside" data-sticky_column>
    <div class="basketcontainer">
        <div class="basketcontainer__title customizable_border">
            <?= Loc::getMessage('BASKET') ?>
        </div>
        <div class="basketcontainer__itemscontainer customizable_border">
            <div id="js-basket-wrapper-id">
                <?  // Корзина.
                    $APPLICATION->IncludeComponent(
                        "wolk:basket", 
                        "side", 
                        array(
                            "EID"  => $arResult['EVENT']->getID(),
                            "CODE" => $arResult['EVENT']->getCode(),
                            "TYPE" => $arResult['CONTEXT']->getType(),
                            "LANG" => $arResult['CONTEXT']->getLang(),
                        )
                    );
                ?>
            </div>
            <div class="navButtons">
                <a href="<?= $arResult['LINKS']['PREV'] ?>" class="button styler prev">
                    <?= Loc::getMessage('PREV') ?>
                </a>
                <div class="basketcontainer__nextstepbutton">
                    <?= Loc::getMessage('NEXT') ?>
                </div>
            </div>
        </div>
    </div>
</aside>
