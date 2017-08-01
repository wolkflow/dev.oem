<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<? $this->setFrameMode(true); ?>

<? use Bitrix\Main\Localization\Loc; ?>
<? use Wolk\Core\Helpers\Text as TextHelper ?>

<pre>
    <? print_r($arResult) ?>
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
                            <div class="ordercontainer__itemscontainer" v-for="(sectionId, items) in selectedStand.OPTIONS">
                                <div v-if="!isEmptyObject(items)" class="pagesubsubtitle">
                                    {{ options.SECTIONS[sectionId].NAME }}
                                </div>
                                <div class="ordercontainer__item" v-for="item in items">
                                    <div class="ordercontainer__itemtotalprice">
                                        {{ allServices[item.ID].PRICE * item.QUANTITY | format_currency ' ' currency_format }}
                                    </div>
                                    <div class="ordercontainer__itemname">
                                        {{ allServices[item.ID].NAME }} | {{ allServices[item.ID].PRICE | format_currency ' ' currency_format }} &times; {{ item.QUANTITY }}
                                    </div>
                                    <div class="ordercontainer__changebutton">
                                        <a  @click.prevent="setStep(2)"  href="javascript:void(0)">
                                            <?= Loc::getMessage('change') ?>
                                        </a>
                                        |
                                        <a @click.prevent="deleteOption(sectionId, item)" href="javascript:void(0)">
                                            <?= Loc::getMessage('delete') ?>
                                        </a>
                                    </div>
                                </div>
                            </div>
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
                        <div class="ordercontainer__itemscontainer" v-for="(sectionName, items) in groupedSelectedServices">
                            <div class="pagesubsubtitle">{{ sectionName }}</div>
                            <div class="ordercontainer__item" v-for="item in items" v-show="allServices[item.ID].PRICE > 0 || allServices[item.ID].CODE == 'FASCIA_NAME'">
                                <div class="ordercontainer__itemtotalprice">
                                    {{ item.MULTIPLIER ? allServices[item.ID].PRICE * item.QUANTITY * item.MULTIPLIER : allServices[item.ID].PRICE * item.QUANTITY | format_currency ' ' currency_format }}
                                </div>
                                <div class="ordercontainer__itemname">
                                    {{ item.ID == 5 ? allServices[item.ID].NAME + ' (' + item.FASCIA_TEXT + ' - ' + item.FASCIA_COLOR + ')' : allServices[item.ID].NAME }} | {{ item.MULTIPLIER ? allServices[item.ID].PRICE * item.MULTIPLIER : allServices[item.ID].PRICE | format_currency ' ' currency_format }} &times; {{ item.QUANTITY }}
                                </div>
                                <div class="ordercontainer__changebutton">
                                    <a @click.prevent="setStep(3)" href="javascript:void(0)">
                                        <?= Loc::getMessage('change') ?>
                                    </a>
                                    |
                                    <a href="#" @click.prevent="deleteServiceItem(sectionName, $index)">
                                        <?= Loc::getMessage('delete') ?>
                                    </a>
                                </div>
                            </div>
                        </div>
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

