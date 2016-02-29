<?use Bitrix\Main\Localization\Loc;?>
<div class="profilecontainer__column left">
    <? foreach ($arResult['EVENTS'] as $n => $event): ?>
    <? if ($n > count($arResult['EVENTS']) / 2): ?></div>
<div class="profilecontainer__column right"><? endif; ?>
    <div class="pagesubsubtitle"><?= $event['NAME'] ?></div>
    <div class="profilecontainer__itemscontainer">
        <? foreach ($event['ORDERS'] as $order): ?>
            <?#dump($order['ORDER']['STATUS_ID'])?>
            <div class="profilecontainer__item">
                <div class="profilecontainer__itemnumber"><?= $order['ORDER']['ID'] ?></div>
				<div class="profilecontainer__itemstatus"><?=Loc::getMessage('status')?>: <?= $arResult['STATUSES'][$order['ORDER']['STATUS_ID']] ?: Loc::getMessage('empty_status') ?></div>
                <? if ($order['ORDER']['STATUS_ID'] == 'N'): ?>
                    <div class="profilecontainer__changebutton">
                        <a @click.prevent="loadOrder(<?= $order['ORDER']['ID'] ?>)"
                           href="/events/<?= $event['CODE'] ?>/?ORDER_ID=<?= $order['ORDER']['ID'] ?>">
                            <?=Loc::getMessage('сhangeview_order');?>
                        </a>
                    </div>
                <? endif; ?>
            </div>
        <? endforeach ?>
    </div>
    <? endforeach; ?>
</div>

<div class="hide">
    <div class="modal modalFull modalOrder" id="order-detail">
        <div class="modalClose arcticmodal-close"></div>
        <div class="modalTitle"><?=Loc::getMessage('сhangeview_order')?> {{orderId}}</div>
        <div class="modalContent">
            <div class="ordercontainer">
                <div class="ordercontainer__columnscontainer">
                    <div class="ordercontainer__column right">
                        <div class="pagesubtitle"><?=Loc::getMessage('standard equipment')?>
                            <div class="pagesubtitle__addbutton">
                                <a :href="'/events/'+ curEvent.CODE + '/?ORDER_ID='+ orderId + '&step=2'"></a>
                            </div>
                        </div>
                        <div class="ordercontainer__itemscontainer">
                            <div class="ordercontainer__item" v-for="eq in selectedStand.EQUIPMENT">
                                <div class="ordercontainer__itemtotalprice">
                                    {{ eq.COST_FORMATTED }}
                                </div>
                                <div class="ordercontainer__itemname">
                                    {{ eq.NAME }} | {{ eq.PRICE_FORMATTED }} x {{ parseInt(eq.QUANTITY) }}
                                </div>
                                <div class="ordercontainer__changebutton">
                                    <a :href="'/events/'+ curEvent.CODE + '/?ORDER_ID='+ orderId + '&step=2'"><?=Loc::getMessage('change')?></a>
                                </div>
                            </div>
                        </div>
                        <div class="pagesubtitle"><?=Loc::getMessage('additional equipment')?>
                            <div class="pagesubtitle__addbutton">
                                <a :href="'/events/'+ curEvent.CODE + '/?ORDER_ID='+ orderId + '&step=3'"></a>
                            </div>
                        </div>
                        <div class="ordercontainer__itemscontainer" v-for="(sectionId, items) in selectedStand.OPTIONS">
                            <div v-if="!$.isEmptyObject(items)" class="pagesubsubtitle">
                                {{ options.SECTIONS[sectionId].NAME }}
                            </div>
                            <div class="ordercontainer__item" v-for="item in items">
                                <div class="ordercontainer__itemtotalprice">
                                    {{ item.COST_FORMATTED }}
                                </div>
                                <div class="ordercontainer__itemname">
                                    {{ item.NAME }} | {{ item.PRICE_FORMATTED }} x {{ item.QUANTITY }}
                                </div>
                                <div class="ordercontainer__changebutton">
                                    <a :href="'/events/'+ curEvent.CODE + '/?ORDER_ID='+ orderId + '&step=3'"><?=Loc::getMessage('change')?></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="ordercontainer__column">
                        <div class="pagesubtitle"><?=Loc::getMessage('stand type')?></div>
                        <div class="ordercontainer__itemscontainer">
                            <div class="pagesubsubtitle"><?=Loc::getMessage('system stand')?></div>
                            <div class="last ordercontainer__item">
                                <div class="ordercontainer__itemtotalprice">
                                    {{ selectedStand.COST_FORMATTED }}
                                </div>
                                <div class="ordercontainer__itemname">{{ selectedStand.NAME }}</div>
                                <div class="ordercontainer__changebutton">
                                    <a :href="'/events/'+ curEvent.CODE + '/?ORDER_ID='+ orderId + '&step=1'"><?=Loc::getMessage('change')?></a>
                                </div>
                            </div>
                        </div>
                        <div class="pagesubtitle"><?=Loc::getMessage('services')?>
                            <div class="pagesubtitle__addbutton">
                                <a :href="'/events/'+ curEvent.CODE + '/?ORDER_ID='+ orderId + '&step=4'"></a>
                            </div>
                        </div>
                        <div class="ordercontainer__itemscontainer"
                             v-for="(sectionId, items) in selectedStand.SERVICES">
                            <div class="pagesubsubtitle">{{ sectionName }}</div>
                            <div class="ordercontainer__item" v-for="item in items">
                                <div class="ordercontainer__itemtotalprice">
                                    {{ item.COST_FORMATTED }}
                                </div>
                                <div class="ordercontainer__itemname">
                                    {{ item.NAME }} | {{ item.PRICE_FORMATTED }} x {{ item.QUANTITY }}
                                </div>
                                <div class="ordercontainer__changebutton">
                                    <a :href="'/events/'+ curEvent.CODE + '/?ORDER_ID='+ orderId + '&step=4'"><?=Loc::getMessage('change')?></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="ordercontainer__changeorderbutton">
                    <form action="/events/{{ curEvent.CODE }}/">
                        <input type="hidden" name="ORDER_ID" :value="orderId">
                        <select name="step" onchange="this.form.submit()" v-styler class="styler">
                            <option></option>
                            <option value="6"><?=Loc::getMessage('Change order')?></option>
                            <option value="1"><?=Loc::getMessage('Stand Type')?></option>
                            <option value="2"><?=Loc::getMessage('Standard Equipment')?></option>
                            <option value="3"><?=Loc::getMessage('Additional Equipment')?></option>
                            <option value="4"><?=Loc::getMessage('Services')?></option>
                        </select>
                    </form>
                </div>
            </div>
            <div class="ordertotalcontainer">
                <div class="ordertotalcontainer__standandpavillion">
                    <div class="ordertotalcontainer__standcontainer">
                        <div class="ordertotalcontainer__title"><?=ucfirst(Loc::getMessage('stand'))?> №</div>
                        <div class="ordertotalcontainer__number">
                            <input disabled type="text" :value="orderProps.standNum.VALUE">
                        </div>
                    </div>
                    <div class="ordertotalcontainer__pavillioncontainer">
                        <div class="ordertotalcontainer__title"><?=ucfirst(Loc::getMessage('pavillion'))?></div>
                        <div class="ordertotalcontainer__number">
                            <input disabled type="text" :value="orderProps.pavillion.VALUE">
                        </div>
                    </div>
                </div>
                <div class="ordertotalcontainer__total"><?=Loc::getMessage('total')?>: {{TOTAL_PRICE_FORMATTED}}</div>
            </div>
        </div>
    </div>
