<? use Bitrix\Main\Localization\Loc; ?>

<!-- <pre>{{ selectedItems | json }}</pre> -->

<script type="x/template" id="electrics-and-communications">
    <div class="servicescontainer serviceContainer" v-if="sections">
        <div @click="toggleVisible" data-module="pagesubtitle-dropdown" class="pagesubtitle customizable_border open":class="{'open': visible == false}">
			{{ section.NAME }}
        </div>
        <div class="pagesubtitleopencontainer">
            <electrics-and-communications-item v-for="section in sections | orderBy SORT" :section="section"></electrics-and-communications-item>
            <? /*
			<input @click.prevent="save" type="button" class="styler saveButton" value="<?= Loc::getMessage('save') ?>" />
			*/ ?>
        </div>
    </div>
</script>

<? // Электричество и коммуникации // ?>
<script type="x/template" id="electrics-and-communications-item">
    <div class="serviceItem" v-if="items">
        <div class="serviceItem__title">
			{{ section.NAME }}
		</div>
        <div class="serviceItem__block" v-for="selectedItem in selectedItems">
            <div class="serviceItem__row">
                <div class="serviceItem__left">
                    <div class="serviceItem__subtitle">
						{{ section.SUBTITLE }}
					</div>
                    <select v-styler="selectedItem.ID" class="styler">
                        <option value="">
                            <?= Loc::getMessage('not selected') ?>
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
                <div style="margin-top: 10px;" v-if="selectedItem.ID">
                    <div class="serviceItem__cost">
                        <div class="serviceItem__subtitle">
							<?= Loc::getMessage('price') ?>
						</div>
                        <div class="serviceItem__cost-value">
                            {{ section.ITEMS[selectedItem.ID].PRICE | format_currency ' ' currency_format}}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="serviceItem__bottom">
            <a href="#" @click.prevent="addItem(section)" class="itemAdd_field">
                <i></i>
                <span><?= Loc::getMessage('add_field') ?></span>
            </a>
        </div>
    </div>
</script>
