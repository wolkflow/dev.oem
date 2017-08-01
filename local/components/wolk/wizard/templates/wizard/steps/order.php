<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<? $this->setFrameMode(true); ?>

<? use Bitrix\Main\Localization\Loc; ?>
<? use Wolk\Core\Helpers\Text as TextHelper ?>

<pre>
    <? // print_r($arResult) ?>
</pre>

<div class="main">
    <div id="step">
        <div class="orderpage">
            <div class="pagetitle"><?= Loc::getMessage('ORDER') ?></div>
            <div class="pagedescription">
                <? Helper::includeFile('orderpage_textdesc_' . $arResult['CONTEXT']->getLang()) ?>
            </div>
            <div class="ordercontainer">
                <div class="ordercontainer__columnscontainer">
                    <? if ($arResult['CONTEXT']->getType() != Wolk\OEM\Context::TYPE_INDIVIDUAL) { ?>
                        <div class="ordercontainer__column right">
                            <div class="pagesubtitle">
                                <?= Loc::getMessage('EQUIPMENTS') ?>
                                <div class="pagesubtitle__addbutton customizable"></div>
                            </div>
                            <? if (!empty($arResult['PRODUCTS'][Wolk\OEM\Products\Section::TYPE_EQUIPMENTS])) { ?>
                                <? foreach ($arResult['PRODUCTS'][Wolk\OEM\Products\Section::TYPE_EQUIPMENTS] as $item) { ?>
                                    <div class="ordercontainer__itemscontainer">
                                        <div v-if="!isEmptyObject(items)" class="pagesubsubtitle">
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
                                                <a href="<?= '/link' ?>">
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
                    <? } ?>
                    
                    <div class="ordercontainer__column">
                        <div class="pagesubtitle">
                            <?= Loc::getMessage('STAND_TYPE') ?>
                        </div>
                        <div class="ordercontainer__itemscontainer">
                            <div class="pagesubsubtitle">
                                <?= Loc::getMessage('system_stand') ?>
                            </div>
                            <div class="last ordercontainer__item" v-show="selectedStand.ID > 0">
                                <div class="ordercontainer__itemtotalprice" v-if="selectedStand.PRICE.PRICE">
                                    {{ selectedStand.PRICE.PRICE | format_currency ' ' currency_format }}
                                </div>
                                <div class="ordercontainer__itemname">
                                    {{ selectedStand.NAME }}
                                </div>
                                <div class="ordercontainer__changebutton">
                                    <a @click.prevent="setStep(1)" href="javascript:void(0)">
                                        <?= Loc::getMessage('change') ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="pagesubtitle">
                            <?= Loc::getMessage('services') ?>
                            <div class="pagesubtitle__addbutton customizable" @click="setStep(3)"></div>
                        </div>
                        
                        <? if (!empty($arResult['PRODUCTS'][Wolk\OEM\Products\Section::TYPE_SERVICES])) { ?>
                            <? foreach ($arResult['PRODUCTS'][Wolk\OEM\Products\Section::TYPE_SERVICES] as $item) { ?>
                                <div class="ordercontainer__itemscontainer">
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
                                            <a href="<?= '/link' ?>">
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
                    <span>{{summaryPrice | format_currency ' ' currency_format }}</span>
                </div>
                <div class="ordertotalcontainer__surcharge" v-show="curEvent.SURCHARGE > 0 && totalSurchargePrice">
                    <?= Loc::getMessage('SURCHRGE') ?>: 
                    <span>{{ curEvent.SURCHARGE }} % ({{ moneySurcharge }})</span>
                </div>
                <? // Если есть наценка, то этот блок просто показывает с НДС, иначе это показывает последняя строка - итого. // ?>
                <div class="ordertotalcontainer__total" v-show="curEvent.SURCHARGE > 0 && totalSurchargePrice">
                    <?= Loc::getMessage('PRICE_TOTAL_WITH_VAT') ?>: 
                    <span>{{totalSurchargePrice | format_currency ' ' currency_format }}</span>
                </div>
                <? if ($arResult['EVENT']['PROPS']['INCLUDE_VAT']['VALUE'] != 'Y') { ?>
                    <div class="ordertaxcontainer__total" v-show="taxPrice">
                        <?= Loc::getMessage('tax') ?>:
                        <span>{{taxPrice | format_currency ' ' currency_format }}</span>
                    </div>
                <? } ?>
                <div class="ordertotalcontainer__surchargetotal" v-show="totalPrice">
                    <div class="ordertotalcontainer__surchargetotaltitle" v-show="curEvent.SURCHARGE > 0">
                        <?= Loc::getMessage('PRICE_TOTAL_WITH_SURCHARGE') ?>:
                    </div>
                    <div class="ordertotalcontainer__surchargetotaltitle" v-show="curEvent.SURCHARGE <= 0">
                        <?= Loc::getMessage('PRICE_TOTAL_SUMM') ?>:
                    </div>
                    <div class="ordertotalcontainer__surchargetotalcount">
                        {{ totalPrice | format_currency ' ' currency_format }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

