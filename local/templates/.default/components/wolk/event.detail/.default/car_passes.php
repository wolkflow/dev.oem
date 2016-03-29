<?use Bitrix\Main\Localization\Loc;?>
<script type="x/template" id="car-passes">
    <!-- Секция: car passes -->
    <div class="servicescontainer serviceContainer">
        <div @click="toggleVisible" data-module="pagesubtitle-dropdown" class="pagesubtitle" :class="{'open': visible == true}">
            {{ section.NAME }}
        </div>
        <div class="pagesubtitleopencontainer">
            <vip-passes></vip-passes>
            <car-passes-to-loading-unloading-zone></car-passes-to-loading-unloading-zone>
            <input @click="save" type="button" class="styler saveButton" value="<?=LOc::getMessage('save')?>">
        </div>
    </div>
    <!-- Секция: car passes -->

</script>

<script type="x/template" id="vip-passes">
    <!-- VIP passes -->
    <div class="serviceItem" v-if="items">
        <div class="serviceItem__title">{{ section.NAME }}</div>

        <div class="serviceItem__block" v-for="selectedItem in selectedItems">
            <div class="serviceItem__row">
                <div class="serviceItem__left">
                    <div class="serviceItem__subtitle"><?=Loc::getMessage('type')?></div>
                    <select v-styler="selectedItem.ID" class="styler">
                        <option value=""><?=Loc::getMessage('not selected')?></option>
                        <option :value="item.ID" v-for="item in items">
                            {{ item.NAME }} &nbsp;&nbsp;&nbsp; {{ item.PRICE | format_number ' ' }}
                        </option>
                    </select>
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
                            {{ section.ITEMS[selectedItem.ID].PRICE | format_number ' ' }}
                        </div>
                    </div>
                    <div class="serviceItem__desc">* <?=Loc::getMessage('vis_pass_desc')?></div>
                </div>
                <a href="#" @click.prevent="addItem" class="itemAdd_field"><i></i><span><?=Loc::getMessage('add_field')?></span></a>
            </div>
        </div>
    </div>
    <!--// .VIP passes -->
</script>

<script type="x/template" id="car-passes-to-loading-unloading-zone">
    <!-- car passes -->
    <div class="serviceItem" v-if="items">
        <div class="serviceItem__title">{{ section.NAME }}</div>

        <div class="serviceItem__block" v-for="selectedItem in selectedItems">
            <div class="serviceItem__row">
                <div class="serviceItem__left">
                    <div class="serviceItem__subtitle"><?=Loc::getMessage('type')?></div>
                    <select v-styler="selectedItem.ID" class="styler">
                        <option value=""><?=Loc::getMessage('not selected')?></option>
                        <option :value="item.ID" v-for="item in items">
                            {{ item.NAME }} &nbsp;&nbsp;&nbsp; {{ item.PRICE | format_number ' ' }}
                        </option>
                    </select>
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
                            {{ section.ITEMS[selectedItem.ID].PRICE | format_number ' ' }}
                        </div>
                    </div>
                    <div class="serviceItem__desc">* <?=Loc::getMessage('passes_desc')?>
                    </div>
                </div>
                <a href="#" @click.prevent="addItem" class="itemAdd_field"><i></i><span><?=Loc::getMessage('add_field')?></span></a>
            </div>
        </div>
    </div>
    <!--// .car passes -->
</script>