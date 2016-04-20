<?use Bitrix\Main\Localization\Loc;?>
<script type="x/template" id="temporary-staff">
    <div class="servicescontainer serviceContainer">
        <div @click="toggleVisible" data-module="pagesubtitle-dropdown" class="pagesubtitle customizable_border open"
             :class="{'open': visible == false}">{{ section.NAME }}
        </div>
        <div class="pagesubtitleopencontainer">
            <stand-security></stand-security>

            <interpreter></interpreter>

            <stand-cleaning></stand-cleaning>

            <stand-assistant></stand-assistant>

            <input @click.prevent="save" type="button" class="styler saveButton" value="<?=Loc::getMessage('save')?>">
        </div>
    </div>
</script>

<script type="x/template" id="stand-security">
    <div class="serviceItem" v-if="items">
        <div class="serviceItem__title">{{ section.NAME }}</div>
        <div class="serviceItem__block" v-for="selectedItem in selectedItems">
            <div class="serviceItem__row">
                <div class="serviceItem__left">
                    <div class="serviceItem__beforeDate">
                        <div class="serviceItem__subtitle">&nbsp;</div>
                        <select v-styler="selectedItem.ID" class="styler">
                            <option value=""><?= Loc::getMessage('not selected') ?></option>
                            <option value="{{ item.ID }}" v-for="item in items">
                                {{ item.NAME }} &nbsp;&nbsp;&nbsp; {{ item.PRICE | format_currency ' ' currency_format}}
                            </option>
                        </select>
                    </div>
					<? // Установка дат // ?>
                    <div class="setDateBlock">
                        <div class="serviceItem__subtitle">
							<?= Loc::getMessage('dates') ?>
						</div>
                        <div class="setDate hasDatepicker" v-pickmeup="selectedItem.calendar">
                            <div class="loolee">
                                <div class="looleeHead">
                                    <label class="styler">
										<input type="checkbox" class="changeMode" />
                                        <span></span><?= Loc::getMessage('daterange') ?>
									</label>
                                    <a href="#" class="cButton buttonClear dateClear">
										<?= Loc::getMessage('clear') ?>
									</a>
                                    <a href="#" class="cButton buttonOk looleeClose">
										ОК
									</a>
                                </div>
                                <div class="dpBlock" data-mode="multiple"></div>
                            </div>
                        </div>
                    </div>
                </div>
				<? // Установка времени // ?>
                <div class="serviceItem__right">
                    <div class="itemCount">
                        <div class="serviceItem__subtitle">
							<?= Loc::getMessage('time') ?>
						</div>
                        <div class="setTime">
                            <select v-timepicker="selectedItem.timeStart" class="styler"></select>
                            <span class="setTime__divider"></span>
                            <select v-timepicker="selectedItem.timeEnd" class="styler"></select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="serviceItem__row">
                <div class="serviceItem__left">
                    <div class="serviceItem__cost serviceItem__cost-small" v-if="selectedItem.ID">
                        <div class="serviceItem__subtitle">
							<?= Loc::getMessage('price') ?>
						</div>
                        <div class="serviceItem__cost-value">
                            {{ items[selectedItem.ID].PRICE | format_currency ' ' currency_format}}
                        </div>
                    </div>
                    <div class="serviceItem__desc">* <?=Loc::getMessage('min_order_hours')?></b></div>
                    <div v-show="selectedItem.calendar.dates">
                        <div id="dateResult">{{ selectedItem.calendar.datesType == 'multiple' ? selectedItem.calendar.dates.join(", ") : selectedItem.calendar.dates.join(" - ") }}</div>
                    </div>
                    <a href="#" @click.prevent="addItem" class="itemAdd_field itemAdd__filed-left clear-left">
                        <i></i>
                        <span><?=Loc::getMessage('add_field')?></span>
                    </a>
                </div>
                <div class="serviceItem__right">
                    <div class="itemCount">
                        <div class="serviceItem__subtitle"><?=Loc::getMessage('quantity')?></div>
                        <div class="itemCount__button itemCount__down justcnt" @click="decQty(selectedItem)"></div>
                        <div class="itemCount__button itemCount__up justcnt" @click="incQty(selectedItem)"></div>
                        <input v-model="selectedItem.QUANTITY" type="text" class="itemCount__input styler">
                    </div>
                </div>
            </div>
        </div>
    </div>
</script>

<script type="x/template" id="interpreter">
    <div class="serviceItem" v-if="items">
        <div class="serviceItem__title">{{ section.NAME }}</div>

        <div class="serviceItem__block" v-for="selectedItem in selectedItems">
            <div class="serviceItem__row">
                <div class="serviceItem__left">
                    <div class="serviceItem__beforeDate">
                        <div class="serviceItem__subtitle">{{ section.SUBTITLE || '&nbsp;' }}</div>
                        <select v-styler="selectedItem.ID" class="styler">
                            <option value=""><?=Loc::getMessage('not selected')?></option>
                            <option value="{{ item.ID }}" v-for="item in items">
                                {{ item.NAME }} &nbsp;&nbsp;&nbsp; {{ item.PRICE | format_currency ' ' currency_format}}
                            </option>
                        </select>
                    </div>
                    <div class="setDateBlock">
                        <div class="serviceItem__subtitle"><?=Loc::getMessage('dates')?></div>
                        <div class="setDate hasDatepicker" v-pickmeup="selectedItem.calendar">
                            <div class="loolee" data-loolee="1">
                                <div class="looleeHead">
                                    <label class="styler">
										<input type="checkbox" class="changeMode" />	
                                        <span></span><?= Loc::getMessage('daterange') ?>
									</label>
                                    <a href="#" class="cButton buttonClear dateClear"><?=Loc::getMessage('clear')?></a>
                                    <a href="#" class="cButton buttonOk looleeClose">ОК</a>
                                </div>
                                <div class="dpBlock" data-mode="multiple"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="serviceItem__right">
                    <div class="itemCount">
                        <div class="itemCount">
                            <div class="serviceItem__subtitle"><?=Loc::getMessage('quantity')?></div>
                            <div class="itemCount__button itemCount__down justcnt" @click="decQty(selectedItem)"></div>
                            <div class="itemCount__button itemCount__up justcnt" @click="incQty(selectedItem)"></div>
                            <input v-model="selectedItem.QUANTITY" type="text" class="itemCount__input styler">
                        </div>
                    </div>
                </div>
            </div>
            <div class="serviceItem__row">
                <div class="serviceItem__left">
                    <div class="serviceItem__cost serviceItem__cost-small" v-if="selectedItem.ID">
                        <div class="serviceItem__subtitle"><?=Loc::getMessage('price')?></div>
                        <div class="serviceItem__cost-value">
                            {{ items[selectedItem.ID].PRICE | format_currency ' ' currency_format}}
                        </div>
                    </div>
                    <div class="serviceItem__desc">* <?=Loc::getMessage('min_order_hours')?></b></div>
                    <div v-show="selectedItem.calendar.dates">
                        <div id="dateResult">{{ selectedItem.calendar.datesType == 'multiple' ? selectedItem.calendar.dates.join(", ") : selectedItem.calendar.dates.join(" - ") }}</div>
                    </div>
                </div>
                <a href="#" @click.prevent="addItem" class="itemAdd_field">
                    <i></i>
                    <span><?=Loc::getMessage('add_field')?></span>
                </a>
            </div>
        </div>
    </div>
</script>

<script type="x/template" id="stand-cleaning">
    <div class="serviceItem" v-if="items">
        <div class="serviceItem__title">{{ section.NAME }}</div>

        <div class="serviceItem__block" v-for="selectedItem in selectedItems">
            <div class="serviceItem__row">
                <div class="serviceItem__left">
                    <div class="serviceItem__beforeDate">
                        <div class="serviceItem__subtitle">{{ section.SUBTITLE || '&nbsp;' }}</div>
                        <select v-styler="selectedItem.ID" class="styler">
                            <option value="">
								<?= Loc::getMessage('not selected') ?>
							</option>
                            <option value="{{ item.ID }}" v-for="item in items">
                                {{ item.NAME }} &nbsp;&nbsp;&nbsp; {{ item.PRICE | format_currency ' ' currency_format}}
                            </option>
                        </select>
                    </div>
                </div>
                <div class="serviceItem__right">
                    <div class="itemCount">
                        <div class="itemCount">
                            <div class="serviceItem__subtitle"><?=Loc::getMessage('space')?> <span>(m<sup>2</sup>)</span></div>
                            <div class="itemCount__button itemCount__down justcnt" @click="decQty(selectedItem)"></div>
                            <div class="itemCount__button itemCount__up justcnt" @click="incQty(selectedItem)"></div>
                            <input v-model="selectedItem.QUANTITY" type="text" class="itemCount__input styler">
                        </div>
                    </div>
                </div>
            </div>
            <div class="serviceItem__row">
				<div class="serviceItem__left">
					<div class="serviceItem__cost serviceItem__cost-small" v-if="selectedItem.ID">
						<div class="serviceItem__subtitle">
							<?= Loc::getMessage('price') ?>
						</div>
						<div class="serviceItem__cost-value">
							{{ items[selectedItem.ID].PRICE | format_currency ' ' currency_format}}
						</div>
					</div>
					<div class="serviceItem__desc">* <?=Loc::getMessage('cleaning_desc')?></div>
				</div>
                <a href="#" @click.prevent="addItem" class="itemAdd_field">
                    <i></i>
                    <span><?=Loc::getMessage('add_field')?></span>
                </a>
            </div>
        </div>
    </div>
</script>

<script type="x/template" id="stand-assistant">
    <div class="serviceItem">
        <div class="serviceItem__title">{{ section.NAME }}</div>

        <div class="serviceItem__block" v-for="selectedItem in selectedItems">
            <div class="serviceItem__row">
                <div class="serviceItem__left">
                    <div class="serviceItem__beforeDate">
                        <div class="serviceItem__subtitle">{{ section.SUBTITLE || '&nbsp;' }}</div>
                        <select id="ass_sel" v-styler="selectedItem.ID" class="styler">
                            <option value=""><?=Loc::getMessage('not selected')?></option>
                            <option :value="item.ID" v-for="item in items">
                                {{ item.NAME }} &nbsp;&nbsp;&nbsp; {{ item.PRICE | format_currency ' ' currency_format}}
                            </option>
                        </select>
                    </div>
                    <div class="setDateBlock">
                        <div class="serviceItem__subtitle"><?=Loc::getMessage('dates')?></div>
                        <div class="setDate hasDatepicker" v-pickmeup="selectedItem.calendar">
                            <div class="loolee">
                                <div class="looleeHead">
                                    <label class="styler"><input type="checkbox" class="changeMode">
                                        <span></span><?=Loc::getMessage('daterange')?></label>
                                    <a href="#" class="cButton buttonClear dateClear"><?=Loc::getMessage('clear')?></a>
                                    <a href="#" class="cButton buttonOk looleeClose">ОК</a>
                                </div>
                                <div class="dpBlock" data-mode="multiple"></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="serviceItem__right">
                    <div class="itemCount">
                        <div class="serviceItem__subtitle"><?=Loc::getMessage('quantity')?></div>
                        <div class="itemCount__button itemCount__down justcnt" @click="decQty(selectedItem)"></div>
                        <div class="itemCount__button itemCount__up justcnt" @click="incQty(selectedItem)"></div>
                        <input v-model="selectedItem.QUANTITY" type="text" class="itemCount__input styler">
                    </div>
                </div>
            </div>
            <div class="serviceItem__row">
                <div class="serviceItem__left">
                    <div class="serviceItem__cost serviceItem__cost-small" v-if="selectedItem.ID">
                        <div class="serviceItem__subtitle"><?=Loc::getMessage('price')?></div>
                        <div class="serviceItem__cost-value">
                            {{ items[selectedItem.ID].PRICE | format_currency ' ' currency_format}}
                        </div>
                    </div>
                    <div class="serviceItem__desc">* <?=Loc::getMessage('price_one_p_one_d')?></div>
                    <div v-show="selectedItem.calendar.dates">
                        <div id="dateResult">{{ selectedItem.calendar.datesType == 'multiple' ? selectedItem.calendar.dates.join(", ") : selectedItem.calendar.dates.join(" - ") }}</div>
                    </div>
                </div>
                <a href="#" @click.prevent="addItem" class="itemAdd_field"><i></i><span><?=Loc::getMessage('add_field')?></span></a>
            </div>
        </div>
    </div>
</script>