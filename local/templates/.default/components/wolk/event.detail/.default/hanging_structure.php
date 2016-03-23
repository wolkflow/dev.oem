<? use Bitrix\Main\Localization\Loc; ?>
<script type="x/template" id="hanging-structure">
    <!-- Секция: hanging structure -->
    <div class="servicescontainer serviceContainer">
        <div @click="toggleVisible" data-module="pagesubtitle-dropdown" class="pagesubtitle"
             :class="{'open': visible == true}">{{ section.NAME }}
        </div>
        <div class="pagesubtitleopencontainer">
            <suspension-points></suspension-points>

            <advertising-materials-file></advertising-materials-file>

            <hanging-structure-mock-up></hanging-structure-mock-up>

            <hanging-structure-details></hanging-structure-details>

            <input @click="save" type="button" class="styler saveButton" value="<?=Loc::getMessage('save')?>">
        </div>
    </div>
    <!-- //Секция: hanging structure-->
</script>

<script type="x/template" id="suspension-points">
    <!-- Suspention points -->
    <div class="serviceItem" v-if="items">
        <div class="serviceItem__title">{{ section.NAME }}</div>

        <div class="serviceItem__block" v-for="selectedItem in selectedItems">
            <div class="serviceItem__row">
                <div class="serviceItem__left">
                    <div class="serviceItem__subtitle">&nbsp;</div>
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
                        <div class="itemCount__button itemCount__down" @click="decQty(selectedItem)"></div>
                        <div class="itemCount__button itemCount__up" @click="incQty(selectedItem)"></div>
                        <input v-model="selectedItem.QUANTITY" type="text" class="itemCount__input styler">
                    </div>
                </div>
                <div style="margin-top: 10px;" v-if="selectedItem.ID">
                    <div class="serviceItem__cost">
                        <div class="serviceItem__subtitle"><?=Loc::getMessage('price')?></div>
                        <div class="serviceItem__cost-value">
                            {{ section.ITEMS[selectedItem.ID].PRICE | format_number ' ' }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="serviceItem__bottom">
            <a href="#" @click.prevent="addItem" class="itemAdd_field"><i></i><span><?=Loc::getMessage('add_field')?></span></a>
        </div>
    </div>
    <!--// .Suspention points -->
</script>

<script type="x/template" id="advertising-materials-file">
    <!-- advertising materials File upload -->
    <div class="serviceItem" v-if="item">
        <div class="serviceItem__title">{{ section.NAME }}</div>

        <div class="serviceItem__block" v-for="selectedItem in selectedItems">
            <div class="serviceItem__row">
                <div class="serviceItem__left">
                    <div class="serviceItem__subtitle"><?=Loc::getMessage('file_and_formats')?></div>
                    <input v-fileupload="selectedItem.FILE_ID" type="file" class="styler">
                </div>
                <div class="serviceItem__right">
                    <div class="itemCount">
                        <div class="serviceItem__subtitle"><?=Loc::getMessage('quantity')?></div>
                        <div class="itemCount__button itemCount__down" @click="decQty(selectedItem)"></div>
                        <div class="itemCount__button itemCount__up" @click="incQty(selectedItem)"></div>
                        <input v-model="selectedItem.QUANTITY" type="text" class="itemCount__input styler">
                    </div>
                </div>
            </div>
            <div class="serviceItem__row">
                <div class="serviceItem__subtitle"><?=Loc::getMessage('comments')?></div>
				<textarea v-model="selectedItem.COMMENTS" class="styler" placeholder=""></textarea>
            </div>
        </div>

        <a href="#" @click.prevent="addItem" class="itemAdd_field itemAdd__filed-left">
            <i></i>
            <span><?=Loc::getMessage('one_more_file')?></span>
        </a>
    </div>
    <!--// .advertising materials File upload -->
</script>

<script type="x/template" id="hanging-structure-mock-up">
    <!-- hanging structure mock-up File upload -->
    <div class="serviceItem" v-if="item">
        <div class="serviceItem__title">{{ section.NAME }}</div>

        <div class="serviceItem__block" v-for="selectedItem in selectedItems">
            <div class="serviceItem__row">
                <div class="serviceItem__left">
                    <div class="serviceItem__subtitle"><?=Loc::getMessage('file_and_formats')?></div>
                    <input v-fileupload="selectedItem.FILE_ID" type="file" class="styler">
                </div>
                <div class="serviceItem__right">
                    <div class="itemCount">
                        <div class="serviceItem__subtitle"><?=Loc::getMessage('quantity')?></div>
                        <div class="itemCount__button itemCount__down" @click="decQty(selectedItem)"></div>
                        <div class="itemCount__button itemCount__up" @click="incQty(selectedItem)"></div>
                        <input v-model="selectedItem.QUANTITY" type="text" class="itemCount__input styler">
                    </div>
                </div>
            </div>
            <div class="serviceItem__row">
                <div class="serviceItem__subtitle"><?=Loc::getMessage('comments')?></div>
                <textarea class="styler" v-model="selectedItem.COMMENTS" placeholder=""></textarea>
            </div>
        </div>


        <a href="#" @click.prevent="addItem" class="itemAdd_field itemAdd__filed-left">
            <i></i>
            <span><?=Loc::getMessage('one_more_file')?></span>
        </a>
    </div>
    <!--// .hanging structure mock-up File upload -->
</script>

<script type="x/template" id="hanging-structure-details">
    <!-- hanging structure details -->
    <div class="serviceItem" v-if="item">
        <div class="serviceItem__title"><?=Loc::getMessage('hanging_structure_details')?></div>

        <div class="serviceItem__block">
            <div class="serviceItem__row">
                <div class="serviceItem__subtitle"><?=Loc::getMessage('company_name')?></div>
                <input v-model="fields.companyName" type="text" class="styler">
            </div>
            <div class="serviceItem__row">
                <div class="serviceItem__col-3">
                    <div class="serviceItem__subtitle"><?=Loc::getMessage('pavillion')?> №</div>
                    <input v-model="fields.pavilionNum" type="text" class="styler">
                </div>
                <div class="serviceItem__col-3">
                    <div class="serviceItem__subtitle"><?=Loc::getMessage('hall')?> №</div>
                    <input v-model="fields.hallNum" type="text" class="styler">
                </div>
                <div class="serviceItem__col-3">
                    <div class="serviceItem__subtitle"><?=Loc::getMessage('stand')?> №</div>
                    <input v-model="fields.standNum" type="text" class="styler">
                </div>
            </div>
            <div class="serviceItem__row">
                <div class="serviceItem__col-3">
                    <div class="serviceItem__subtitle"><?=Loc::getMessage('size')?> <span>(mm)</span></div>
                    <input v-model="fields.size" type="text" class="styler" placeholder="L x W x H">
                </div>
                <div class="serviceItem__col-3">
                    <div class="serviceItem__subtitle"><?=Loc::getMessage('material')?></div>
                    <input v-model="fields.material" type="text" class="styler">
                </div>
                <div class="serviceItem__col-3">
                    <div class="serviceItem__subtitle"><?=Loc::getMessage('weight')?> <span>(kg)</span> <i class="tip" title="Tip"></i>
                    </div>
                    <input v-model="fields.weight" type="text" class="styler">
                </div>
            </div>
            <div class="serviceItem__row">
                <div class="serviceItem__subtitle"><?=Loc::getMessage('List of the equipment placing on the structure')?></div>
                        <textarea v-model="fields.listOfTheEquipmentPlacingOnTheStructure" class="styler"
                                  placeholder="Please indicate weight of each item (lightings, advertising equipment, decoration elements)"></textarea>
            </div>
            <div class="serviceItem__row">
                <div class="serviceItem__col-2">
                    <div class="serviceItem__subtitle"><?=Loc::getMessage('material')?></div>
                    <input v-model="fields.material2" type="text" class="styler">
                </div>
                <div class="serviceItem__col-2">
                    <div class="serviceItem__subtitle"><?=Loc::getMessage('Weight per point')?> <span>(kg)</span></div>
                    <input v-model="fields.weightPerPoint" type="text" class="styler">
                </div>
            </div>
            <div class="serviceItem__row">
                <div class="serviceItem__col-2">
                    <div class="serviceItem__subtitle"><?=Loc::getMessage('height')?> <span>(mm)</span> <i class="tip" title="Tip"></i>
                    </div>
                    <input v-model="fields.height" type="text" class="styler">
                </div>
                <div class="serviceItem__col-2">
                    <div class="serviceItem__subtitle"><?=Loc::getMessage('Total weight')?>  <span>(kg)</span>
					<i class="tip" title="Tip"></i></div>
                    <input v-model="fields.totalWeight" type="text" class="styler">
                </div>
            </div>
            <div class="serviceItem__row">
                <div class="serviceItem__subtitle"><?=Loc::getMessage('Person in charge of the project of the structure')?></div>
                <input v-model="fields.personInChargeOfTheProjectOfTheStructure" type="text" class="styler">
            </div>
            <div class="serviceItem__row">
                <div class="serviceItem__subtitle"><?=Loc::getMessage('Person in charge of mounting works')?></div>
                <input v-model="fields.personInChargeOfMountingWorks" type="text" class="styler">
            </div>
            <div class="serviceItem__row">
                <div class="serviceItem__subtitle"><?=Loc::getMessage('Mobile phone')?></div>
                <input v-model="fields.mobilePhone" type="text" class="styler">
            </div>
        </div>
    </div>
    <!--// .hanging structure details -->
</script>