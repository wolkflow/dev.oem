<? use Bitrix\Main\Localization\Loc; ?>
<script type="x/template" id="additional-equipment">
    <div class="pagesubsubtitle">{{ item.NAME }}</div>
    <div class="equipmentcontainer__itemcontainer">
        <div class="equipmentcontainer__itemrightside">
            <div class="equipmentcontainer__itemprice">{{ price | format_currency ' ' currency_format }}</div>
            <div class="equipmentcontainer__itemcolorcontainer" v-if="colors">
                <div class="equipmentcontainer__itemcolortitle"><?= Loc::getMessage('color') ?></div>
                <div class="equipmentcontainer__itemcolordropdown" v-if="colorsLength > 1">
                    <select v-styler="selectedColor" class="styler">
                        <option :value=""><?= Loc::getMessage('not selected') ?></option>
                        <option v-for="(colorValueId, color) in colors" value="{{ colorValueId }}">
                            {{ color }}
                        </option>
                    </select>
                </div>
                <span v-else>{{ colors.VALUE }}</span>
                <input type="hidden" v-model="selectedColor" :value="colors.ID">
            </div>
            <div class="itemquantitycontainer">
                <div class="itemCount">
                    <div class="serviceItem__subtitle"><?= Loc::getMessage('quantity') ?></div>
                    <div class="itemCount__button itemCount__down" @click="decQty"></div>
                    <div class="itemCount__button itemCount__up" @click="incQty"></div>
                    <input v-model="item.QUANTITY" type="text" class="itemCount__input styler" number
                           :value="item.QUANTITY">
                </div>
            </div>
            <div @click="addToCart" class="equipmentcontainer__additembutton"><?= Loc::getMessage('save') ?></div>
        </div>
        <div class="equipmentcontainer__itemleftside">
            <div class="equipmentcontainer__itemphotocontainer">
                <a class="photoZoom" :href="item.PICTURE.BIG"></a>
                <img :src="item.PICTURE.SMALL" class="equipmentcontainer__itemphoto" />
            </div>
            <div class="equipmentcontainer__itemsize">
<!--                <div class="equipmentcontainer__itemsizetitle">Size</div>-->
                {{{ description }}}
            </div>
        </div>
    </div>
</script>