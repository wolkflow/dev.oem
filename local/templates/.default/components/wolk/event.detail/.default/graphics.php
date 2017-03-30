<? use Bitrix\Main\Localization\Loc; ?>

<script type="x/template" id="graphics">
    <div class="servicescontainer serviceContainer">
        <div @click="toggleVisible" data-module="pagesubtitle-dropdown" class="pagesubtitle customizable_border open" :class="{'open': visible == false}">
			 {{ section.NAME }}
        </div>
        <div class="pagesubtitleopencontainer">
            <fascia-name></fascia-name>
			<logotype></logotype>
            <banner></banner>
            <laminating></laminating>
			<posting></posting>
            <full-color-printing></full-color-printing>
			
            <input type="button" class="styler saveButton" value="<?= Loc::getMessage('save') ?>" @click.prevent="save" />
        </div>
    </div>
</script>



<? // Надпись на фриз // ?>
<script type="x/template" id="fascia-name">
    <div class="serviceItem" v-if="item">
        <div class="serviceItem">
            <div class="serviceItem__title">{{ section.NAME }}</div>
            <div class="serviceItem__block">
                <div class="serviceItem__row">
                    <div class="serviceItem__left">
                        <div class="serviceItem__subtitle">
							<?= Loc::getMessage('text') ?>
						</div>
                        <div class="itemText_custom">
                            <input v-model="text" type="text" class="styler" />
                        </div>
                    </div>
                    <div class="serviceItem__right">
                        <div class="serviceItem__subtitle">
							<?= Loc::getMessage('color') ?>
						</div>
                        <input type="hidden" v-model="selectedColor">
                        <button :style="{ background: selectedColor ? 'rgb('+ allColors[selectedColor].UF_CODE +')' : '#7f7f7f' }" class="styler itemColor__custom"
                                data-modal="#color"
                                id="colorTriger1">
                            {{ selectedColor ? '<?= Loc::getMessage('change color') ?>' : '<?= Loc::getMessage('choose color') ?>' }}
                        </button>
                        <div v-show="selectedColor" class="itemColor__custom-name">{{ selectedColor ? allColors[selectedColor].UF_NUM : '' }} {{ selectedColor }}</div>
                    </div>
                    <div class="equipmentcontainer__itemsize">
                        <?= Loc::getMessage('FASCIA_NOTE') ?>
                    </div>
                </div>
            </div>
        </div>

        <div class="hide">
            <div class="modal" id="color">
                <div class="modalClose arcticmodal-close"></div>
                <div class="modalTitle"><?= Loc::getMessage('choose_color_fascia') ?></div>
                <div class="colorsArray">
                    <ul>
                        <li v-for="color in colors | orderBy 'UF_SORT'" @click="selectColor(color.UF_XML_ID)" :class="{'active': color.UF_XML_ID == selectedColor}">
                            <span :style="{ background: color.UF_BACKGROUND ? 'url('+color.UF_BACKGROUND+')' : 'rgb('+ color.UF_CODE +')' }"></span>
							<div class="colorTip">{{ color.UF_NUM }} {{ color.NAME }}<br><b>sRGB:</b> {{ color.UF_CODE }}</div>
                            <div class="colorTitle">{{ color.UF_NUM }}</div>
                        </li>
                    </ul>
                    <div class="colorsNote">
						<?= Loc::getMessage('colors_note') ?>
					</div>
                </div>
            </div>
        </div>
    </div>
</script>


<? // Логотипы // ?>
<script type="x/template" id="logotype">
    <div class="serviceItem" v-if="items">
        <div class="serviceItem__title">
			<?= Loc::getMessage('logotype') ?>
		</div>
        <div class="serviceItem__block" v-for="selectedItem in selectedItems">
            <div class="serviceItem__row">
                <div class="serviceItem__left">
                    <div class="serviceItem__subtitle">
						<?= Loc::getMessage('extent') ?>
					</div>
                    <div class="itemText_custom">
                        <select v-styler="selectedItem.ID" class="styler">
							<option value="">
								<?= Loc::getMessage('not selected') ?>
							</option>
							<option :value="item.ID" v-for="item in items">
								{{ item.NAME }} &nbsp;&nbsp;&nbsp; {{ item.PRICE | format_currency ' ' currency_format }}
							</option>
						</select>
                    </div>
                </div>
                <div class="serviceItem__right">
                    <div class="itemCount">
                        <div class="serviceItem__subtitle">
							<?= Loc::getMessage('quantity') ?>
						</div>
                        <div class="itemCount__button itemCount__down justcnt" @click="decQty(selectedItem)"></div>
                        <div class="itemCount__button itemCount__up justcnt" @click="incQty(selectedItem)"></div>
                        <input v-model="selectedItem.QUANTITY" type="text" class="itemCount__input styler" number />
                    </div>
                </div>
            </div>
            <div class="serviceItem__row">
                <div class="serviceItem__left-short" v-if="selectedItem.ID">
                    <div class="serviceItem__subtitle">
						<?= Loc::getMessage('price') ?>
					</div>
                    <div class="serviceItem__cost-value">
						{{ items[selectedItem.ID].PRICE | format_currency ' ' currency_format}}
					</div>
                </div>
                <div class="serviceItem__right-large">
                    <div class="serviceItem__subtitle">
						<?= Loc::getMessage('file_and_formats') ?>
					</div>
                    <input v-fileupload="selectedItem.FILE" type="file" class="styler" />
                </div>
            </div>
            <div class="serviceItem__bottom-inputs"></div>

            <div class="serviceItem__subtitle">
				<?= Loc::getMessage('comments') ?>
			</div>
			<textarea v-model="selectedItem.COMMENTS" class="styler" placeholder="<?= Loc::getMessage('logoCommentPlaceholder') ?>"></textarea>
        </div>

        <a href="javascript:void(0)" @click.prevent="addItem" class="itemAdd_field itemAdd__filed-left">
            <i></i>
            <span><?= Loc::getMessage('one_more_logo') ?></span>
        </a>
    </div>
</script>



<? // Баннеры // ?>
<script type="x/template" id="banner">
    <div class="serviceItem" v-if="items">
        <div class="serviceItem__title">
			<?= Loc::getMessage('banner') ?>
		</div>
        <div class="serviceItem__block" v-for="selectedItem in selectedItems">
            <div class="serviceItem__row">
                <div class="serviceItem__left">
                    <div class="serviceItem__subtitle">
						<?= Loc::getMessage('extend_choose') ?>
					</div>
                    <div class="itemText_custom">
                        <select v-styler="selectedItem.ID" class="styler">
							<option value="">
								<?= Loc::getMessage('not selected') ?>
							</option>
							<option :value="item.ID" v-for="item in items">
								{{ item.NAME }} &nbsp;&nbsp;&nbsp; {{ item.PRICE | format_currency ' ' currency_format }}
							</option>
						</select>
                    </div>
                </div>
                <div class="serviceItem__right">
                    <div class="itemCount">
                        <div class="serviceItem__subtitle">
							<?= Loc::getMessage('quantity') ?>
						</div>
                        <div class="itemCount__button itemCount__down justcnt" @click="decQty(selectedItem)"></div>
                        <div class="itemCount__button itemCount__up justcnt" @click="incQty(selectedItem)"></div>
                        <input v-model="selectedItem.QUANTITY" type="text" class="itemCount__input styler" number />
                    </div>
                </div>
            </div>
            <div class="serviceItem__row">
                <div class="serviceItem__left-short" v-if="selectedItem.ID">
                    <div class="serviceItem__subtitle">
						<?= Loc::getMessage('price') ?>
					</div>
                    <div class="serviceItem__cost-value">
						{{ items[selectedItem.ID].PRICE | format_currency ' ' currency_format}}
					</div>
                </div>
                <div class="serviceItem__right-large">
                    <div class="serviceItem__subtitle">
						<?= Loc::getMessage('file_and_formats') ?>
					</div>
                    <input v-fileupload="selectedItem.FILE" type="file" class="styler" />
                </div>
            </div>
            <div class="serviceItem__bottom-inputs"></div>

            <div class="serviceItem__subtitle">
				<?= Loc::getMessage('comments') ?>
			</div>
			<textarea v-model="selectedItem.COMMENTS" class="styler" placeholder="<?= Loc::getMessage('logoCommentPlaceholder') ?>"></textarea>
        </div>

        <a href="javascript:void(0)" @click.prevent="addItem" class="itemAdd_field itemAdd__filed-left">
            <i></i>
            <span><?= Loc::getMessage('one_more_logo') ?></span>
        </a>
    </div>
</script>



<? // Ламинирование цветной пленкой // ?>
<script type="x/template" id="laminating">
    <div class="serviceItem" v-if="item">
        <div class="serviceItem__title">{{ section.NAME }} <span>{{price | format_currency ' ' currency_format}} (<?= Loc::getMessage('m2') ?>)</span></div>

        <div class="serviceItem__block" v-for="item in items">
            <div class="serviceItem__row">
				<div>
					<div class="serviceItem__col-8 lamCount">
						<div class="itemCount">
							<div class="serviceItem__subtitle">
								<?= Loc::getMessage('space') ?> (<?= Loc::getMessage('m2') ?>)
							</div>
							<input v-model="item.QUANTITY" type="text" class="itemCount__input styler" />
						</div>
					</div>
					<div class="serviceItem__col-7 lamColor">
						<div class="serviceItem__subtitle"><?=Loc::getMessage('color')?></div>
						<input type="hidden" v-model="item.COLOR">
						<button :style="{ background: item.COLOR ? 'rgb(' + allColors[item.COLOR].UF_CODE + ')' : '#7f7f7f' }" class="styler itemColor__custom"
								data-modal="#color_lam{{$index}}"
								id="colorTriger2">
							{{ item.COLOR ? '<?=Loc::getMessage('change color')?>' : '<?=Loc::getMessage('choose color')?>' }}
						</button>
						<div v-show="item.COLOR" class="itemColor__custom-name">{{ item.COLOR ? allColors[item.COLOR].UF_NUM : '' }} {{ item.COLOR }}</div>
					</div>
				</div>
            </div>
            <div class="serviceItem__row"></div>
            <div class="serviceItem__row">
                <div class="serviceItem__subtitle">
					<?= Loc::getMessage('comments') ?>
				</div>
                <textarea v-model="item.COMMENTS" class="styler" placeholder="<?= Loc::getMessage('placeholder_comments') ?>"></textarea>
            </div>

            <div class="hide">
                <div class="modal" id="color_lam{{$index}}">
                    <div class="modalClose arcticmodal-close"></div>
                    <div class="modalTitle"><?=Loc::getMessage('choose_color_for_laminating')?></div>
                    <div class="colorsArray">
                        <ul>
                            <li v-for="color in colors | orderBy 'UF_SORT'" @click="selectColor(item, color.UF_XML_ID)" :class="{'active': color.UF_XML_ID == item.COLOR}">
								<span :style="{ background: color.UF_BACKGROUND ? 'url('+color.UF_BACKGROUND+')' : 'rgb('+ color.UF_CODE +')' }"></span>
								<div class="colorTip">{{ color.UF_NUM }} {{ color.NAME }}<br><b>sRGB:</b> {{ color.UF_CODE }}</div>
                                <div class="colorTitle">{{ color.UF_NUM }}</div>
                            </li>
                        </ul>
                        <div class="colorsNote"><?=Loc::getMessage('colors_note')?></div>
                    </div>
                </div>
            </div>
        </div>

        <a href="#" @click.prevent="addItem" class="itemAdd_field itemAdd__filed-left">
            <i></i>
            <span><?=Loc::getMessage('one_more_panel_type')?></span>
        </a>
    </div>
</script>



<? // Оклейка материалом заказчика // ?>
<script type="x/template" id="posting">
    <div class="serviceItem" v-if="item">
        <div class="serviceItem__title">{{ section.NAME }} <span>{{price | format_currency ' ' currency_format}} (<?= Loc::getMessage('m2') ?>)</span></div>

        <div class="serviceItem__block" v-for="item in items">
            <div class="serviceItem__row">
				<div>
					<div class="serviceItem__col-8 lamCount">
						<div class="itemCount">
							<div class="serviceItem__subtitle">
								<?= Loc::getMessage('space') ?> (<?= Loc::getMessage('m2') ?>)
							</div>
							<input v-model="item.QUANTITY" type="text" class="itemCount__input styler" />
						</div>
					</div>
				</div>
            </div>
            <div class="serviceItem__row"></div>
            <div class="serviceItem__row">
                <div class="serviceItem__subtitle">
					<?= Loc::getMessage('comments') ?>
				</div>
                <textarea v-model="item.COMMENTS" class="styler" placeholder="<?= Loc::getMessage('placeholder_comments') ?>"></textarea>
            </div>
        </div>

        <a href="#" @click.prevent="addItem" class="itemAdd_field itemAdd__filed-left">
            <i></i>
            <span><?= Loc::getMessage('one_more_panel_type') ?></span>
        </a>
    </div>
</script>


<? // Полноцветная печать // ?>
<script type="x/template" id="full-color-printing">
    <div class="serviceItem" v-if="item">
        <div class="serviceItem__title">{{ section.NAME }} <span>{{price | format_currency ' ' currency_format}} (<?= Loc::getMessage('m2') ?>)</span></div>
        <div class="serviceItem__block" v-for="item in items">
            <div class="serviceItem__row">
                <div class="serviceItem__left">
                    <div class="serviceItem__left-child">
                        <div class="serviceItem__subtitle"><?=Loc::getMessage('width')?> <span>(mm)</span></div>
                        <input v-model="item.WIDTH" type="text" class="styler">
                    </div>
                    <div class="serviceItem__left-child">
                        <div class="serviceItem__subtitle"><?=Loc::getMessage('height')?> <span>(mm)</span></div>
                        <input v-model="item.HEIGHT" type="text" class="styler">
                    </div>
                </div>
                <div class="serviceItem__right">
                    <div class="itemCount">
                        <div class="serviceItem__subtitle">
							<?= Loc::getMessage('quantity') ?>
						</div>
                        <div class="itemCount__button itemCount__down justcnt" @click="decQty(item)"></div>
                        <div class="itemCount__button itemCount__up justcnt" @click="incQty(item)"></div>
                        <input v-model="item.QUANTITY" type="text" class="itemCount__input styler" number />
                    </div>
                </div>
            </div>

            <div class="serviceItem__row">
                <div class="serviceItem__subtitle">
					<?= Loc::getMessage('link_to_your_mockup') ?>
				</div>
                <input v-model="item.LINK" type="text" class="styler" />
                <p class="thisDesc">
					<?= Loc::getMessage('mockup_requirements') ?>
				</p>
            </div>
            <div class="serviceItem__row">
                <div class="serviceItem__subtitle">
					<?= Loc::getMessage('comments') ?>
				</div>
				<textarea v-model="item.COMMENTS" class="styler" placeholder="<?= Loc::getMessage('placeholder_mockup') ?>"></textarea>
            </div>
        </div>

        <a href="javascript:void(0)" @click.prevent="addItem" class="itemAdd_field itemAdd__filed-left">
            <i></i>
            <span><?= Loc::getMessage('one_more_print') ?></span>
        </a>

        <div class="clear"></div>
        <? // Контактное лицо // ?>
		<? $language = strtoupper(\Bitrix\Main\Context::getCurrent()->getLanguage()); ?>
		<? $contacts = $arResult['EVENT']['PROPS']['LANG_CONTACTS_'.$language]['VALUE'] ?>
		<? if (!empty($contacts)) { ?>
			<div class="serviceItem__block serviceItem__manager">
				<div class="serviceItem__row">
					<div class="serviceItem__subtitle"><?= Loc::getMessage('show_manager_contacts') ?></div>
					<p>
						<? foreach ($contacts as $contact) { ?>
							<ul class="modalContactsBlock">
								<li class="contactTitle">
									<?= $contact['POST'] ?>
								</li>
								<li>
									<?= $contact['NAME'] ?>
								</li>
								<li>
									P: <?= $contact['PHONE'] ?>
								</li>
								<li>
									E: <a href="mailto:<?= $contact['EMAIL'] ?>"><?= $contact['EMAIL'] ?></a>
								</li>
							</ul>
						<? } ?>
						
						<? // {{{ event.MANAGER_CONTACTS.TEXT }}} ?>
					</p>
				</div>
			</div>
		<? } ?>
    </div>
</script>




