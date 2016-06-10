<?
use Bitrix\Main\Localization\Loc;
use Wolk\Core\Helpers\Text as TextHelper;

$curLang = strtoupper(\Bitrix\Main\Context::getCurrent()->getLanguage());
?>
<div class="breadcrumbs">
    <div class="breadcrumbs__container" :class="{ 'indStand': selectedStand.ID == 0 }">
        <a
            @click="setStep(step.NUM)"
            v-for="step in steps | visibleSteps selectedStand.ID"
            href="javascript:void(0)" 
            class="breadcrumbs__button"
           :class="{ 'active': step.NUM == curStep, 'hidden': ([2,4].indexOf(parseInt(step.NUM)) != -1) && selectedStand.ID == 0 }"
        > 
			<span class="breadcrumbs__buttoncontainer">
				{{ $index + 1 }}. {{ step.NAME }}
			</span>
        </a>
    </div>
</div>

<pre style="display: none;">{{selectedStand | json}}</pre>

<div class="catalogdeadline" v-show="hasMargins">
	<div class="catalogdeadline__deadlinecontainer">
		<div class="catalogdeadline__deadlinetitle customizable_border">
			<?= Loc::getMessage('deadline') ?>
			<span class="catalogdeadline__deadlinedate">
                <? if (\Bitrix\Main\Context::getCurrent()->getLanguage() == 'ru') { ?>
                    <? $date = strtotime(reset($arResult['EVENT']['PROPS']['MARGIN_DATES']['VALUE'])) ?>
                    <?= date('j', $date) ?>
                    <?= TextHelper::i18nmonth(date('n', $date), false) ?>,
                    <?= date('Y', $date) ?>
                <? } else { ?>
                    <? $date = strtotime(reset($arResult['EVENT']['PROPS']['MARGIN_DATES']['VALUE'])) ?>
                    <?= TextHelper::i18nmonth(date('n', $date)) ?>
                    <?= date('j', $date) ?><sup><?= Loc::getMessage('weekday') ?></sup>,
                    <?= date('Y', $date) ?>
                <? } ?>
			</span>
		</div>
        <div class="catalogdeadline__deadlinedescription">
            <span v-for="(margindate, marginpercent) in curEvent.MARGIN_DATES">
                <?= Loc::getMessage('surcharge_message') ?><br>
            </span>
        </div>
    </div>
    <div class="catalogdeadline__timetablebutton customizable" data-modal="#timetable">
        <?= Loc::getMessage('timetable') ?>
    </div>
</div>

<!--STEP 1-->
<div id="step1" v-show="curStep == 1">
    <div class="standspagetop" id="preselect" v-if="selected">
        <div class="pagedescription">
            <? if ($arResult['INDIVIDUAL_STAND']) { ?>
                <?= Loc::getMessage('selected_individual_stand') ?>
            <? } else { ?>
                <?= Loc::getMessage('prepaid_stand') ?>
            <? } ?>
        </div>
        <div v-show="selectedStand.ID > 0">
            <div class="pagetitle"><?= Loc::getMessage('Your current stand type') ?></div>
            <div class="standspagetop__currentstandcontainer customizable_border">
                <div class="standspagetop__currentstanddescription">
                    <p>{{{ selectedStand.PROPS['LANG_DESCRIPTION_' + curLang]['~VALUE'].TEXT }}}</p>
                    <ul v-show="selectedStand.EQUIPMENT">
						<?= Loc::getMessage('Including') ?>:
                        <li v-for="eq in selectedStand.EQUIPMENT">
							{{ eq.COUNT }} &times; {{ eq.NAME }}
						</li>
                    </ul>
                </div>
                <img :src="selectedStand.PREVIEW_PICTURE" class="standspagetop__photo" />
                <a href="javascript:void(0)" @click="nextStep" class="standspagetop__continuebutton customizable">
                    <?= Loc::getMessage('continue') ?>
                </a>
            </div>
        </div>
		<a v-show="selectedStand.ID == 0" href="javascript:void(0)" @click="nextStep" class="standspagetop__continuebutton customizable">
			<?= Loc::getMessage('continue') ?>
		</a>        
    </div>

    <div class="standstypescontainer">
        <div class="pagetitle"><?= Loc::getMessage('Another system stand types') ?></div>
        <div class="standstypescontainer__standscontainer standsTypesRow">
            <? $s_id = 0;
            foreach ($arResult['ITEMS'] as $stand) { ?>
                <div
                    class="standstypescontainer__standcontainer <? if ($s_id % 2 == 0) { ?>standsTypesLeft<? } else { ?>standsTypesRight<? } ?>">
					<? if ($s_id < 2) { ?>
						<div class="standsTypes__window">
							<? Helper::includeFile('choose_wall_conf_'.$curLang) ?>
							<form method="get" name="standsTypes__window_<?=$s_id?>" action="">
								<div class="chooseType__row">
								
									<label for="row_<?=$s_id?>" class="chooseType__label">
										<input id="row_<?=$s_id?>" type="radio" value="row" name="standtype"><span><?=Loc::getMessage('row')?></span>
										<span class="chooseType__icon"></span>
									</label>
									<label for="head_<?=$s_id?>" class="chooseType__label">
										<input id="head_<?=$s_id?>" type="radio" value="head" name="standtype"><span><?=Loc::getMessage('head')?></span>
										<span class="chooseType__icon"></span>
									</label>
								</div>
								<div class="chooseType__row">
									<label for="corner_<?=$s_id?>" class="chooseType__label">
										<input id="corner_<?=$s_id?>" type="radio" value="corner" name="standtype"><span><?=Loc::getMessage('corner')?></span>
										<span class="chooseType__icon"></span>
									</label>
									<label for="insel_<?=$s_id?>" class="chooseType__label">
										<input id="insel_<?=$s_id?>" type="radio" value="insel" name="standtype"><span><?=Loc::getMessage('insel')?></span>
										<span class="chooseType__icon"></span>
									</label>
								</div>
								<div class="standsTypes__save">
									<input @click.prevent="setType($event.target, <?=$s_id?>)" type="button" value="<?=Loc::getMessage('save')?>" class="styler">
								</div>
							</form>
						</div>
					<? } ?>

                    <div class="pagesubtitle customizable_border"><?= $stand['NAME'] ?></div>
                    <div class="standstypescontainer__pricecontiner">
						<?= FormatCurrency($stand['PRICE']['PRICE'], $arResult['EVENT']['CURRENCY']['NAME']) ?>
                        <span>
							<?= FormatCurrency($stand['BASE_PRICE']['PRICE'], $arResult['EVENT']['CURRENCY']['NAME']) ?> / <?= Loc::getMessage('m2') ?>
                        </span>
                        <div class="standstypescontainer__choosebutton customizable"
                             :class="{'current': selectedStand.ID == <?= $stand['ID'] ?>}"
                             @click="setSelected('<?= $stand['ID'] ?>', <?=$s_id?>);">
                            {{selectedStand.ID == <?= $stand['ID'] ?> ? '<?= Loc::getMessage('chosen') ?>' :
                            '<?= Loc::getMessage('choose') ?>'}}
                        </div>
                    </div>
                    <img height="138" src="<?= $stand['PREVIEW_PICTURE'] ?>" class="standstypescontainer__photo" />

                    <div class="standstypescontainer__description">
                        <p><?= $stand['PROPS']["LANG_DESCRIPTION_{$curLang}"]['~VALUE']['TEXT'] ?></p>
                        <ul>
							<?= Loc::getMessage('Including') ?>:
                            <? foreach ($stand['OFFER']['EQUIPMENT'] as $eq) { ?>
                                <li>
									<?= $eq['COUNT'] ?> &times; <?= $eq['NAME'] ?>
								</li>
                            <? } ?>
                        </ul>
                    </div>
                </div>
                <? $s_id++; ?>
			<? } ?>
        </div>
    </div>
</div>

<div class="pagetitle" v-show="curStep == 3"><?= Loc::getMessage('Services') ?></div>
<div class="pagetitle" v-show="curStep == 2"><?= Loc::getMessage('Additional equipment') ?></div>
<div class="main" v-show="[2,3,4].indexOf(parseInt(curStep)) != -1">

    <!--STEP 2-->
	<? /*
    <div class="standartequipmentcontainer" v-show="curStep == 2">
        <div v-if="selectedStand.ID" class="standartequipmentcontainer__itemcontainer"
             v-for="eq in selectedStand.EQUIPMENT">
            <div class="pagesubtitle">{{ eq.PROPS['LANG_TITLE_'+curLang].VALUE }}</div>
            <div class="standartequipmentcontainer__itemtopcontainer">
                <img :src="eq.PREVIEW_PICTURE_SMALL" class="standartequipmentcontainer__itemphoto"/>

                <div class="itemquantitycontainer">
                    <div class="itemquantitycontainer__itemquantitytitle"><?= Loc::getMessage('Quantity') ?></div>
                    <div @click="decEqQty(eq)" class="itemquantitycontainer__itemquantitybutton minus"></div>
                    <div class="itemquantitycontainer__itemquantity">{{ eq.QUANTITY }}</div>
                    <div @click="incEqQty(eq)" class="itemquantitycontainer__itemquantitybutton plus"></div>
                </div>
            </div>
            <div class="standartequipmentcontainer__itemdescription">
                <p>{{{ eq.PROPS['LANG_DESCRIPTION_' + curLang]['~VALUE'].TEXT }}}</p>
                <div class="standartequipmentcontainer__itemcolor" v-show="eq.PROPS.hasOwnProperty('LANG_EQ_COLOR_' + curLang) && eq.PROPS['LANG_EQ_COLOR_' + curLang].VALUE">
                    <b><?= Loc::getMessage('color') ?>:</b> {{ eq.PROPS.hasOwnProperty('LANG_EQ_COLOR_' + curLang) ? eq.PROPS['LANG_EQ_COLOR_' + curLang].VALUE : '' }}
                </div>
            </div>
        </div>
        <div class="pagetitle"><?= Loc::getMessage('Review your configuration') ?></div>
        <div class="reviewconfigurationcontainer">
            <div class="reviewconfigurationcontainer__configuration">
                <span class="reviewconfigurationcontainer__configurationtitle">
					<?= Loc::getMessage('system_booth') ?>:
				</span>
                {{ selectedStand['LANG_NAME_' + curLang] || selectedStand.NAME}}
            </div>
            <div class="reviewconfigurationcontainer__configuration">
                <span class="reviewconfigurationcontainer__configurationtitle">
					<?= Loc::getMessage('width') ?> &amp; <?= Loc::getMessage('depth') ?>:
				</span>
                {{ selectedParams.WIDTH }} &times; {{ selectedParams.DEPTH }}
            </div>
            <div class="reviewconfigurationcontainer__configuration">
                <span class="reviewconfigurationcontainer__configurationtitle">
					<?= Loc::getMessage('type') ?>:
                </span>
				{{ selectedParams.TYPE || '<?= Loc::getMessage('individual') ?>' | t }}
            </div>
            <div class="reviewconfigurationcontainer__configuration">
                <span class="reviewconfigurationcontainer__configurationtitle"><?=Loc::getMessage('exhibition')?>: </span> {{ curEvent.NAME }}
            </div>
            <div class="reviewconfigurationcontainer__configuration">
                <span class="reviewconfigurationcontainer__configurationtitle"><?=Loc::getMessage('location')?>: </span> {{curEvent.LOCATION}}
            </div>
        </div>
    </div>
	*/ ?>

    <!--STEP 2-->
	<? // Выбор оборудования // ?>
    <div id="step3" v-show="curStep == 2">
        <div class="equipmentcontainer">
            <div class="options_group" v-for="section in options.SECTIONS | orderBy 'SORT'">
                <div
					v-if="section.ITEMS" 
					@click="toggleSectionVisible(section)" 
					data-module="pagesubtitle-dropdown"
                    class="pagesubtitle moduleinited customizable_border"
                    :class="{'open': section.visible == true || !section.hasOwnProperty('visible')}"
				>
					{{ section.NAME }}
                </div>
                <div class="pagesubtitleopencontainer">
                    <additional-equipment v-for="item in section.ITEMS | orderBy 'SORT'" :item="item" :section="section"></additional-equipment>
                </div>
            </div>
        </div>
    </div>

    <!--STEP 3-->
	<? // Выбор услуг // ?>
    <div id="step4" v-show="curStep == 3">
        <electrics-and-communications></electrics-and-communications>
		<telecommunications></telecommunications>
        <graphics v-if="selectedStand.ID > 0"></graphics>
        <hanging-structure></hanging-structure>
        <temporary-staff></temporary-staff>
        <car-passes></car-passes>
    </div>
</div>

<aside class="siteAside" data-sticky_column>
    <div class="basketcontainer" v-if="[2,3].indexOf(parseInt(curStep)) != -1">
        <basket></basket>
    </div>
</aside>

<div style="clear:both;" v-show="[2,3,4].indexOf(parseInt(curStep)) != -1"></div>

<!--STEP 4-->
<? // Создание скетча // ?>
<div class="sketchpage" id="step5" v-show="curStep == 4" transition="fade">
    <? include 'sketch.php' ?>
</div>

<!--STEP 5-->
<? // Формирование заказа // ?>
<div class="orderpage" id="order" v-show="curStep == 5">
    <div class="pagetitle"><?= Loc::getMessage('order') ?></div>
    <div class="pagedescription">
        <? Helper::includeFile('orderpage_textdesc_'.$curLang) ?>
    </div>
    <div class="ordercontainer">
        <div class="ordercontainer__columnscontainer">
            <div class="ordercontainer__column right">
				<? /*
                <div class="pagesubtitle">
					<?= Loc::getMessage('standart_equipment') ?>
                    <div class="pagesubtitle__addbutton customizable" @click="setStep(2)"></div>
                </div>
                <div class="ordercontainer__itemscontainer">
                    <div class="ordercontainer__item" v-for="eq in selectedStand.EQUIPMENT">
                        <div class="ordercontainer__itemtotalprice">
                            {{ allServices[eq.ID].PRICE * (eq.QUANTITY - eq.COUNT) | format_currency ' ' currency_format }}
                        </div>
                        <div class="ordercontainer__itemname">
                            {{ allServices[eq.ID].NAME }} | {{ allServices[eq.ID].PRICE | format_currency ' ' currency_format }} &times; {{ eq.QUANTITY }}
                        </div>
                        <div class="ordercontainer__changebutton">
                            <a @click.prevent="setStep(2)" href="javascript:void(0)">
								<?= Loc::getMessage('change') ?>
							</a>
                        </div>
                    </div>
                </div>
				*/ ?>
                <div class="pagesubtitle">
					<?= Loc::getMessage('additional_equipment') ?>
                    <div class="pagesubtitle__addbutton customizable" @click="setStep(2)"></div>
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
            <div class="ordercontainer__column">
                <div class="pagesubtitle">
					<?= Loc::getMessage('stand_type') ?>
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
					<?= Loc::getMessage('stand') ?> №
				</div>
                <div class="ordertotalcontainer__number">
                    <input type="text" v-model="standNum" />
                </div>
            </div>
            <div class="ordertotalcontainer__pavillioncontainer">
                <div class="ordertotalcontainer__title">
					<?= Loc::getMessage('pavillion') ?>
				</div>
                <div class="ordertotalcontainer__number">
                    <input type="text" v-model="pavillion" />
                </div>
            </div>
            <div class="ordertotalcontainer__placeorder" data-modal="<?= ($USER->IsAuthorized()) ? ('#placeLogin') : ('#placeUnlogin') ?>">
                <?= Loc::getMessage('place_order') ?>
            </div>
        </div>
        <div class="ordertotalcontainer__total" v-show="summaryPrice">
            <?= Loc::getMessage('total') ?>: <span>{{summaryPrice | format_currency ' ' currency_format }}</span>
        </div>
		<div class="ordertotalcontainer__surcharge" v-show="curEvent.SURCHARGE > 0 && totalSurchargePrice">
            <?= Loc::getMessage('surcharge') ?>: <span>{{ curEvent.SURCHARGE }} % ({{ moneySurcharge }})</span>
        </div>
		<? // Если есть наценка, то этот блок просто показывает с НДС, иначе это показывает последняя строка - итого. // ?>
		<div class="ordertotalcontainer__total" v-show="curEvent.SURCHARGE > 0 && totalSurchargePrice">
			<?= Loc::getMessage('total_with_vat') ?>: <span>{{totalSurchargePrice | format_currency ' ' currency_format }}</span>
		</div>
		<div class="ordertaxcontainer__total" v-show="taxPrice">
			<?= Loc::getMessage('tax') ?>:
			<span>{{taxPrice | format_currency ' ' currency_format }}</span>
		</div>
        <div class="ordertotalcontainer__surchargetotal" v-show="totalPrice">
            <div class="ordertotalcontainer__surchargetotaltitle" v-show="curEvent.SURCHARGE > 0">
				<?= Loc::getMessage('total_with_sur') ?>:
			</div>
			<div class="ordertotalcontainer__surchargetotaltitle" v-show="curEvent.SURCHARGE <= 0">
				<?= Loc::getMessage('total_summ') ?>:
			</div>
            <div class="ordertotalcontainer__surchargetotalcount">
                {{ totalPrice | format_currency ' ' currency_format }}
            </div>
        </div>
    </div>
</div>

<div class="hide">
    <div class="modal" id="timetable">
        <div class="modalClose arcticmodal-close"></div>
        <div class="modalTitle">
			<?= Loc::getMessage('event_timetable') ?>
		</div>
        {{{ curEvent.SCHEDULE }}}
    </div>
	
    <!-- Окно: не залогинен -->
    <div class="modal placeOrder placeOrder__unlogin" id="placeUnlogin">
        <div class="modalClose arcticmodal-close"></div>
        <div class="modalTitle"><?=Loc::getMessage('place_order')?></div>
        <form>
            <div class="placeOrder__text">
                <? Helper::includeFile('placeOrder_not_logged_in_text_'.$curLang) ?>
            </div>
            <label>
                <input id="g_ag" type="checkbox" class="styler" v-styler="guest_agreement">
                <? Helper::includeFile('rules_text_with_link_'.$curLang) ?>
            </label>
            <div class="placeOrder__buttons" v-if="guest_agreement">
                <button class="styler arcticmodal-close" data-modal="#modalLogin">
					<?= Loc::getMessage('login') ?>
				</button>
                <button class="styler arcticmodal-close" data-modal="#modalRegister">
					<?= Loc::getMessage('register') ?>
				</button>
            </div>
        </form>
    </div>

    <!-- Окно: залогинен -->
    <div class="modal placeOrder placeOrder__login" id="placeLogin">
        <div class="modalClose arcticmodal-close"></div>
        <div class="modalTitle"><?= Loc::getMessage('place_order') ?></div>
        <form>
            <div class="placeOrder__text">
                <? Helper::includeFile('placeOrder_logged_in_text_'.$curLang) ?>
            </div>
            <label>
                <input type="checkbox" class="styler" v-styler="agreement" />
                <? Helper::includeFile('rules_text_with_link_'.$curLang) ?>
            </label>
            <div class="placeOrder__buttons" v-if="agreement">
                <button id="js-place-order-id" class="styler" @click.prevent="placeOrder('')">
					<?= Loc::getMessage('place_order') ?>
				</button>
            </div>
			<? /*
			<div class="placeOrder_docs">
				<a href="" data-modal="#termsConditions" class="footersection__terms"><?= Loc::getMessage('terms_conditions') ?></a>
				<a href="" data-modal="#generalInfo" class="footersection__information"><?= Loc::getMessage('general_information') ?></a>
			</div>
			*/ ?>
        </form>
    </div>

    <div class="modal modalRegister" id="modalRegister">
        <div class="modalClose arcticmodal-close"></div>
        <div class="modalPrev arcticmodal-close" data-modal="#placeUnlogin">
			<?= Loc::getMessage('back') ?>
		</div>
        <div class="modalTitle">
			<?= Loc::getMessage('register') ?>
		</div>
		
        <form>
            <div class="userForm__left">
                <div class="formRow">
                    <label for="comName">
						<?= Loc::getMessage('company_name') ?>*
					</label>
                    <input type="text" class="styler" id="comName" required v-model="userData.companyName" />
                </div>
                <div class="formRow">
                    <label for="comAddr">
						<?= Loc::getMessage('company_address') ?>*
					</label>
                    <input type="text" class="styler" id="comAddr" required v-model="userData.companyAddress" />
                </div>
                <div class="formRow">
                    <label for="comName">
						<?= Loc::getMessage('name') ?>*
					</label>
                    <input type="text" class="styler" id="comName" required v-model="userData.name" />
                </div>
                <div class="formRow">
                    <label for="comLastName">
						<?= Loc::getMessage('last_name') ?>*
					</label>
                    <input type="text" class="styler" id="comLastName" required v-model="userData.lastName" />
                </div>
                <div class="formRow">
                    <label for="comPhone">
						<?= Loc::getMessage('phone_number') ?>
					</label>
                    <input type="text" class="styler" id="comPhone" v-model="userData.phone" />
                </div>
            </div>
            <div class="userForm__right">
                <div class="formRow">
                    <label for="comMail">
						<?= Loc::getMessage('email') ?>*
					</label>
                    <input type="text" class="styler" id="comMail" v-model="userData.email" />
                </div>
                <div class="formRow">
                    <label for="comMail">
						<?= Loc::getMessage('confirm_email') ?>*
					</label>
                    <input type="text" class="styler" id="email_confirm" v-model="userData.email_confirm" />
                </div>
                <div class="formRow">
                    <label for="comVat">
						<?= Loc::getMessage('vat_id') ?>
					</label>
                    <input type="text" class="styler" id="comVat" v-model="userData.vatId" />
                </div>
                <div class="formRow">
                    <label for="comPass">
						<?= Loc::getMessage('password') ?>*
					</label>
                    <input pattern=".{6,}" type="password" class="styler" id="comPass" v-model="userData.password" />
                </div>
                <div class="formRow">
                    <label for="comPassRe">
						<?= Loc::getMessage('confirm_password') ?>*
					</label>
                    <input type="password" class="styler" id="comPassRe" v-model="userData.password_confirm" />
                </div>
                <div class="formRow">
                    <label>&nbsp;</label>
                    <input type="button" class="styler modalSend" value="<?= Loc::getMessage('register') ?>" @click="placeOrder('register')" />
                </div>
            </div>
            <div class="clear"></div>
            <div class="userForm__note">
				* <?= Loc::getMessage('userform_note') ?>
			</div>
            <div class="errortext"></div>
        </form>
    </div>
	

    <!-- Окно: вход -->
    <div class="modal modalLogin" id="modalLogin">
        <div class="modalClose arcticmodal-close"></div>
        <div class="modalPrev arcticmodal-close" data-modal="#placeUnlogin">
			<?= Loc::getMessage('back') ?>
		</div>
        <div class="modalTitle">
			<?= Loc::getMessage('login') ?>
		</div>
		
        <form>
            <div class="formRow">
                <label for="userLogin"><?= Loc::getMessage('login') ?></label>
                <input type="text" class="styler" id="userLogin" v-model="userData.login" />
            </div>
            <div class="formRow">
                <label for="userPassword"><?= Loc::getMessage('password') ?></label>
                <input type="password" class="styler" id="userPassword" v-model="userData.password" />
            </div>
            <div class="formRow">
                <input type="button" class="styler full-width" value="<?= Loc::getMessage('login') ?>" @click="placeOrder('login')" />
            </div>
            <div class="clear"></div>
            <div class="errortext"></div>
        </form>
    </div>
    

    <div class="modal modalSuccessOrder" id="modalSuccessOrder">
        <div class="modalTitle">
			<?= Loc::getMessage('success') ?>!
		</div>
        <p>
			<?= Loc::getMessage('order_placed') ?>
		</p>
        <a href="/events/{{curEvent.CODE}}/" class="styler modalSend">
            <?= Loc::getMessage('home') ?>
        </a>
        <br>
        <a href="/personal/orders-history.php">
            <?= Loc::getMessage('review_order') ?>
        </a>
        <div class="clear"></div>
    </div>
    <div class="modal modalError" id="modalSketchError">
        <div class="modalClose arcticmodal-close"></div>
        <div class="modalTitle">
			<?= Loc::getMessage('error') ?>!
		</div>
        <div class="modalContent">
			<?= Loc::getMessage('put_all_eq') ?>
		</div>
    </div>

    <div class="modal modalError" id="modalColorError">
        <div class="modalClose arcticmodal-close"></div>
        <div class="modalTitle">
			<?= Loc::getMessage('error') ?>!
		</div>
        <div class="modalContent">
			<?= Loc::getMessage('select_color') ?>
		</div>
    </div>
</div>

<? include 'additional_equipment.php' ?>

<? include 'basket.php' ?>

<? include 'electrics_and_communications.php' ?>

<? include 'telecommunications.php' ?>

<? include 'graphics.php' ?>

<? include 'hanging_structure.php' ?>

<? include 'temporary_staff.php' ?>

<? include 'car_passes.php' ?>
