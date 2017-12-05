<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<? $this->setFrameMode(true); ?>

<? use Bitrix\Main\Localization\Loc; ?>
<? use Wolk\Core\Helpers\Text as TextHelper ?>
<? use Wolk\OEM\Basket as Basket ?>
<? use Wolk\OEM\Products\Section as Section ?>

<div id="step">
    <div class="orderpage">
        <div class="pagetitle"><?= Loc::getMessage('ORDER') ?></div>
		
		<? if ($arResult['SKETCHERROR']) { ?>	
			<div class="pagedescription">
				<?= Loc::getMessage('ATTENTION') ?>
			</div>
			<div class="ordercontainer noborder">
				<div class="ordercontainer__columnscontainer">
					<?= Loc::getMessage('ERROR_SKETCH_REQUIRED_IN_ORDER') ?>
					<a href="<?= $arResult['LINKS']['PREV'] ?>" class="ordertotalcontainer__placeorder customizable">
						<?= Loc::getMessage('PREV') ?>
					</a>
				</div>
			</div>
		<? } else { ?>
			<div class="pagedescription">
				<? Helper::includeFile('orderpage_textdesc_' . \Bitrix\Main\Context::getCurrent()->getLanguage()) ?>
			</div>
			<div class="ordercontainer">
				<div class="ordercontainer__columnscontainer">
					<div class="ordercontainer__column right">
						
						<? // Оборудование. // ?>
						<? if (in_array('equipments', $arResult['STEPS'])) { ?>
							<div class="pagesubtitle">
								<?= Loc::getMessage('TITLE_EQUIPMENT') ?>
								<div class="pagesubtitle__addbutton customizable" onclick="javascript: location.href = '<?= $arResult['STEPLINKS']['equipments'] ?>#equipment';"></div>
							</div>
							<? if (!empty($arResult['PRODUCTS'][Wolk\OEM\Products\Section::TYPE_EQUIPMENTS])) { ?>
								<? foreach ($arResult['PRODUCTS'][Wolk\OEM\Products\Section::TYPE_EQUIPMENTS] as $item) { ?>
									<div class="ordercontainer__itemscontainer js-product-block" data-bid="<?= $item['BASKET']->getID() ?>">
										<div class="pagesubsubtitle">
											<?= $item['ITEM']->getTitle() ?>
										</div>
										<div class="ordercontainer__item" v-for="item in items">
											<div class="ordercontainer__itemtotalprice">
												<span class="js-product-cost">
													<?= FormatCurrency($item['BASKET']->getCost(), $arResult['CURRENCY']) ?>
												</span>
											</div>
											<div class="ordercontainer__itemname">
												<?= $item['ITEM']->getTitle() ?> 
												<? if ($item['BASKET']->hasParam(Basket::PARAM_COLOR)) { ?>
													<? $param = $item['BASKET']->getParam(Basket::PARAM_COLOR) ?>
													<? $color = new \Wolk\OEM\Dicts\Color($param['ID']) ?>
													<span class="product-param">
														(<?= $color->getName() ?>, <?= $color->getNumber() ?>)
													</span>
												<? } ?>
												| <?= FormatCurrency($item['BASKET']->getPrice(), $arResult['CURRENCY']) ?>
												&times;
												<span class="js-product-quantity">
													<?= $item['BASKET']->getQuantity() ?>
												</span>
											</div>
											<div class="ordercontainer__changebutton">
												<a href="<?= $arResult['STEPLINKS']['equipments'] ?>#s-<?= $item['ITEM']->getSectionID() ?>">
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
						<? } ?>
						
						
						<? // Маркетинг. // ?>
						<? if (in_array('marketings', $arResult['STEPS'])) { ?>
							<div class="pagesubtitle">
								<?= Loc::getMessage('TITLE_MARKETING') ?>
								<div class="pagesubtitle__addbutton customizable" onclick="javascript: location.href = '<?= $arResult['STEPLINKS']['marketings'] ?>#marketing';"></div>
							</div>
							<? if (!empty($arResult['PRODUCTS'][Wolk\OEM\Products\Section::TYPE_MARKETINGS])) { ?>
								<? foreach ($arResult['PRODUCTS'][Wolk\OEM\Products\Section::TYPE_MARKETINGS] as $item) { ?>
									<div class="ordercontainer__itemscontainer js-product-block" data-bid="<?= $item['BASKET']->getID() ?>">
										<div class="pagesubsubtitle">
											<?= $item['ITEM']->getSection()->getTitle() ?>
										</div>
										<div class="ordercontainer__item">
											<div class="ordercontainer__itemtotalprice">
												<span class="js-product-cost">
													<?= FormatCurrency($item['BASKET']->getCost(), $arResult['CURRENCY']) ?>
												</span>
											</div>
											<div class="ordercontainer__itemname">
												<?= $item['ITEM']->getTitle() ?> 
												<? if ($item['BASKET']->hasParam(Basket::PARAM_COLOR)) { ?>
													<? $param = $item['BASKET']->getParam(Basket::PARAM_COLOR) ?>
													<? $color = new \Wolk\OEM\Dicts\Color($param['ID']) ?>
													<span class="product-param">
														(<?= $color->getName() ?>, <?= $color->getNumber() ?>)
													</span>
												<? } ?>
												| <?= FormatCurrency($item['BASKET']->getPrice(), $arResult['CURRENCY']) ?> 
												&times;
												<span class="js-product-quantity" data-quantity="<?= $item['BASKET']->getQuantity() ?>">
													<?= $item['BASKET']->getQuantity() ?>
												</span>
											</div>
											<div class="ordercontainer__changebutton">
												<? $section = $item['ITEM']->getSection() ?>
												<? if (in_array($section->getPriceType(), [Section::PRICETYPE_QUANTITY, Section::PRICETYPE_SQUARE])) { ?>
													<a href="javascript:void(0)" class="js-basket-dec" data-bid="<?= $item['BASKET']->getID() ?>" data-sid="<?= $item['ITEM']->getSectionID() ?>" data-template="order">-</a>
													<a href="javascript:void(0)" class="js-basket-inc" data-bid="<?= $item['BASKET']->getID() ?>" data-sid="<?= $item['ITEM']->getSectionID() ?>" data-template="order">+</a>
													|
												<? } ?>
												<a href="<?= $arResult['STEPLINKS']['services'] ?>#s-<?= $item['ITEM']->getSectionID() ?>">
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
						<? } ?>
					</div>
					
					<div class="ordercontainer__column">
					
						<? // Стенды. // ?>
						<? if ($arResult['CONTEXT']->getType() != Wolk\OEM\Context::TYPE_INDIVIDUAL && !empty($arResult['STAND'])) { ?>
							<div class="pagesubtitle">
								<?= Loc::getMessage('STAND_TYPE') ?>
							</div>
							<div class="ordercontainer__itemscontainer">
								<div class="pagesubsubtitle">
									<?= Loc::getMessage('SYSTEM_STAND') ?>
								</div>
								<div class="last ordercontainer__item">
									<div class="ordercontainer__itemtotalprice">
										<?= FormatCurrency($arResult['STAND']['BASKET']->getCost(), $arResult['CURRENCY']) ?>
									</div>
									<div class="ordercontainer__itemname">
										<?= $arResult['STAND']['ITEM']->getTitle() ?>
									</div>
									<div class="ordercontainer__changebutton">
										<a href="<?= $arResult['STEPLINKS']['stands'] ?>">
											<?= Loc::getMessage('CHANGE') ?>
										</a>
									</div>
								</div>
							</div>
						<? } ?>
						
						<? // Услуги. // ?>
						<? if (in_array('services', $arResult['STEPS'])) { ?>
							<div class="pagesubtitle">
								<?= Loc::getMessage('TITLE_SERVICES') ?>
								<div class="pagesubtitle__addbutton customizable" onclick="javascript: location.href = '<?= $arResult['STEPLINKS']['services'] ?>#services';"></div>
							</div>
							<? if (!empty($arResult['PRODUCTS'][Wolk\OEM\Products\Section::TYPE_SERVICES])) { ?>
								<? foreach ($arResult['PRODUCTS'][Wolk\OEM\Products\Section::TYPE_SERVICES] as $item) { ?>
									<div class="ordercontainer__itemscontainer js-product-block" data-bid="<?= $item['BASKET']->getID() ?>">
										<div class="pagesubsubtitle">
											<?= $item['ITEM']->getSection()->getTitle() ?>
										</div>
										<div class="ordercontainer__item">
											<div class="ordercontainer__itemtotalprice">
												<span class="js-product-cost">
													<?= FormatCurrency($item['BASKET']->getCost(), $arResult['CURRENCY']) ?>
												</span>
											</div>
											<div class="ordercontainer__itemname">
												<?= $item['ITEM']->getTitle() ?> 
												<? if ($item['BASKET']->hasParam(Basket::PARAM_COLOR)) { ?>
													<? $param = $item['BASKET']->getParam(Basket::PARAM_COLOR) ?>
													<? $color = new \Wolk\OEM\Dicts\Color($param['ID']) ?>
													<span class="product-param">
														(<?= $color->getName() ?>, <?= $color->getNumber() ?>)
													</span>
												<? } ?>
												| <?= FormatCurrency($item['BASKET']->getPrice(), $arResult['CURRENCY']) ?> 
												&times;
												<span class="js-product-quantity" data-quantity="<?= $item['BASKET']->getQuantity() ?>">
													<?= $item['BASKET']->getQuantity() ?>
												</span>
											</div>
											<div class="ordercontainer__changebutton">
												<? $section = $item['ITEM']->getSection() ?>
												<? if (in_array($section->getPriceType(), [Section::PRICETYPE_QUANTITY, Section::PRICETYPE_SQUARE])) { ?>
													<a href="javascript:void(0)" class="js-basket-dec" data-bid="<?= $item['BASKET']->getID() ?>" data-sid="<?= $item['ITEM']->getSectionID() ?>" data-template="order">-</a>
													<a href="javascript:void(0)" class="js-basket-inc" data-bid="<?= $item['BASKET']->getID() ?>" data-sid="<?= $item['ITEM']->getSectionID() ?>" data-template="order">+</a>
													|
												<? } ?>
												<a href="<?= $arResult['STEPLINKS']['services'] ?>#s-<?= $item['ITEM']->getSectionID() ?>">
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
						<? } ?>
					</div>
				</div>
			</div>
			
			
			<? // Стоимость заказа. // ?>
			<div class="ordertotalcontainer">
				<div class="ordertotalcontainer__standandpavillion">
					<div class="ordertotalcontainer__standcontainer">
						<div class="ordertotalcontainer__title">
							<?= Loc::getMessage('STAND') ?> №
						</div>
						<div class="ordertotalcontainer__number">
							<input class="js-form-remote-input" type="text" name="STANDNUM" value="<?= (!empty($arResult['STANDNUM'])) ? ($arResult['STANDNUM']) : ('0') ?>" />
						</div>
					</div>
					<div class="ordertotalcontainer__pavillioncontainer">
						<div class="ordertotalcontainer__title">
							<?= Loc::getMessage('PAVILION') ?>
						</div>
						<div class="ordertotalcontainer__number">
							<input class="js-form-remote-input" type="text" name="PAVILION" value="<?= (!empty($arResult['PAVILION'])) ? ($arResult['PAVILION']) : ('0') ?>" />
						</div>
					</div>
					<div class="ordertotalcontainer__placeorder customizable" data-modal="<?= ($USER->IsAuthorized()) ? ('#place-auth') : ('#place-unauth') ?>">
						<?= Loc::getMessage('PLACE_ORDER') ?>
					</div>
				</div>

				<div id="js-summary-wrapper-id">
					<div class="ordertotalcontainer__total">
						<?= Loc::getMessage('PRICE_TOTAL') ?>: 
						<span>
							<?= FormatCurrency($arResult['PRICES']['PRICE'], $arResult['CURRENCY']) ?>
						</span>
					</div>
					
					<? if ($arResult['PRICES']['SURCHARGE_PRICE'] > 0) { ?>
						<div class="ordertotalcontainer__surcharge">
							<?= Loc::getMessage('SURCHRGE') ?>: 
							<span>
								<?= FormatCurrency($arResult['PRICES']['SURCHARGE_PRICE'], $arResult['CURRENCY']) ?>
							</span>
						</div>
						<div class="ordertotalcontainer__total">
							<?= Loc::getMessage('PRICE_TOTAL_WITH_VAT') ?>: 
							<span>
								<?= FormatCurrency($arResult['PRICES']['SUMMARY'], $arResult['CURRENCY']) ?>
							</span>
						</div>
					<? } ?>
					
					<div class="ordertaxcontainer__total">
						<?= Loc::getMessage('VAT') ?>:
						<span>
							<?= FormatCurrency($arResult['PRICES']['VAT_PRICE'], $arResult['CURRENCY']) ?>
						</span>
					</div>
					
					<div class="ordertotalcontainer__surchargetotal" v-show="totalPrice">
						<? if ($arResult['PRICES']['SURCHARGE_PRICE'] > 0) { ?>
							<div class="ordertotalcontainer__surchargetotaltitle">
								<?= Loc::getMessage('PRICE_TOTAL_WITH_SURCHARGE') ?>:
							</div>
						<? } else { ?>
							<div class="ordertotalcontainer__surchargetotaltitle">
								<?= Loc::getMessage('PRICE_TOTAL_WITH_VAT') ?>:
							</div>
						<? } ?>
						<div class="ordertotalcontainer__surchargetotalcount">
							<?= FormatCurrency($arResult['PRICES']['SUMMARY'], $arResult['CURRENCY']) ?>
						</div>
					</div>
				</div>
			</div>
		<? } ?>
    </div>
</div>


<? if (!$arResult['SKETCHERROR']) { ?>

	<? // --- Модальные окна --- // ?>
	<div class="hide">

		<? // Окно: залогинен // ?>
		<div class="modal placeOrder placeOrder__login" id="place-auth">
			<div class="modalClose arcticmodal-close"></div>
			<div class="modalTitle">
				<?= Loc::getMessage('PLACE_ORDER') ?>
			</div>
			<form class="js-modal-block js-remote-order-form">
				<div class="placeOrder__text">
					<? Helper::includeFile('placeOrder_logged_in_text_'.$arResult['CONTEXT']->getLang()) ?>
				</div>
				<label>
					<input id="js-order-place-checkbox-auth-id" type="checkbox" class="styler" v-styler="agreement" />
					<? Helper::includeFile('rules_text_with_link_'.$arResult['CONTEXT']->getLang()) ?>
				</label>
				<div id="js-order-place-block-auth-id" class="placeOrder__buttons hide">
					<button id="js-place-order-id" class="styler customizable">
						<?= Loc::getMessage('PLACE_ORDER') ?>
					</button>
				</div>
			</form>
		</div>
		

		<? // Окно: не залогинен // ?>
		<div class="modal placeOrder placeOrder__unlogin" id="place-unauth">
			<div class="modalClose arcticmodal-close"></div>
			<div class="modalTitle">
				<?= Loc::getMessage('PLACE_ORDER') ?>
			</div>
			<form class="js-modal-block js-remote-order-form">
				<div class="placeOrder__text">
					<?= Loc::getMessage('PLACE_ORDER_NOT_LOGIN_TEXT') ?>
				</div>
				<label>
					<input id="js-order-place-checkbox-unauth-id" type="checkbox" class="styler" />
					<? Helper::includeFile('rules_text_with_link_'.$arResult['CONTEXT']->getLang()) ?>
				</label>
				<div id="js-order-place-block-unauth-id" class="placeOrder__buttons hide">
					<button class="styler x-arcticmodal-close customizable" data-modal="#modal-login">
						<?= Loc::getMessage('LOGIN') ?>
					</button>
					<button class="styler x-arcticmodal-close customizable" data-modal="#modal-register">
						<?= Loc::getMessage('REGISTER') ?>
					</button>
				</div>
			</form>
		</div>
		
		
		<? // Окно: вход // ?>
		<div class="modal modalLogin" id="modal-login">
			<div class="modalClose arcticmodal-close"></div>
			<div class="modalPrev arcticmodal-close" data-modal="#place-unauth">
				<?= Loc::getMessage('BACK') ?>
			</div>
			<div class="modalTitle">
				<?= Loc::getMessage('LOGIN') ?>
			</div>
			<form id="js-form-login-id" class="js-remote-order-form js-modal-block">
				<input type="hidden" name="preaction" value="login" />
				<div class="formRow">
					<label for="userLogin">
						<?= Loc::getMessage('USER_LOGIN') ?>
					</label>
					<input type="text" class="styler" name="AUTH[LOGIN]" />
				</div>
				<div class="formRow">
					<label for="userPassword"><?= Loc::getMessage('USER_PASSWORD') ?></label>
					<input type="password" class="styler"  name="AUTH[PASSWORD]" />
				</div>
				<div class="formRow">
					<input type="submit" class="styler full-width customizable" value="<?= Loc::getMessage('LOGIN') ?>" />
				</div>
				<div class="clear"></div>
				<div class="errortext"></div>
			</form>
		</div>
		
		
		<? // Окно: регистрация // ?>
		<div class="modal modalRegister" id="modal-register">
			<div class="modalClose arcticmodal-close"></div>
			<div class="modalPrev arcticmodal-close" data-modal="#place-unauth">
				<?= Loc::getMessage('BACK') ?>
			</div>
			<div class="modalTitle">
				<?= Loc::getMessage('REGISTER') ?>
			</div>
			<form id="js-form-register-id" class="js-remote-order-form js-modal-block">
				<input type="hidden" name="preaction" value="register" />
				<div class="userForm__left">
					<div class="formRow">
						<label for="comName">
							<?= Loc::getMessage('COMPANY_NAME') ?>*
						</label>
						<input type="text" class="styler" name="AUTH[WORK_COMPANY]" />
					</div>
					<div class="formRow">
						<label for="comAddr">
							<?= Loc::getMessage('COMPANY_ADDRESS') ?>*
						</label>
						<input type="text" class="styler" name="AUTH[WORK_STREET]" />
					</div>
					<div class="formRow">
						<label for="comName">
							<?= Loc::getMessage('NAME') ?>*
						</label>
						<input type="text" class="styler" name="AUTH[NAME]" />
					</div>
					<div class="formRow">
						<label for="comLastName">
							<?= Loc::getMessage('LAST_NAME') ?>*
						</label>
						<input type="text" class="styler" name="AUTH[LAST_NAME]" />
					</div>
					<div class="formRow">
						<label for="comPhone">
							<?= Loc::getMessage('PERSONAL_MOBILE') ?>
						</label>
						<input type="text" class="styler" name="AUTH[PERSONAL_MOBILE]" />
					</div>
				</div>
				<div class="userForm__right">
					<div class="formRow">
						<label for="comMail">
							<?= Loc::getMessage('EMAIL') ?>*
						</label>
						<input type="text" class="styler" name="AUTH[EMAIL]" />
					</div>
					<div class="formRow">
						<label for="comMail">
							<?= Loc::getMessage('CONFIRM_EMAIL') ?>*
						</label>
						<input type="text" class="styler" name="AUTH[CONFIRM_EMAIL]" />
					</div>
					<div class="formRow">
						<label for="comVat">
							<?= Loc::getMessage('VAT_ID') ?>
						</label>
						<input type="text" class="styler" name="AUTH[UF_VAT]" />
					</div>
					<div class="formRow">
						<label for="comPass">
							<?= Loc::getMessage('USER_PASSWORD') ?>*
						</label>
						<input pattern=".{6,}" type="password" name="AUTH[PASSWORD]" />
					</div>
					<div class="formRow">
						<label for="comPassRe">
							<?= Loc::getMessage('USER_CONFIRM_PASSWORD') ?>*
						</label>
						<input type="password" class="styler" name="AUTH[PASSWORD_CONFIRM]" />
					</div>
					<div class="formRow">
						<label>&nbsp;</label>
						<input type="submit" class="styler modalSend customizable" value="<?= Loc::getMessage('REGISTER') ?>" />
					</div>
				</div>
				<div class="clear"></div>
				<div class="userForm__note">
					* <?= Loc::getMessage('USERFORM_NOTE') ?>
				</div>
				<div class="errortext"></div>
			</form>
		</div>
		
		
		
		<? // Окно: заказ офрмлен // ?>
		<div class="modal modalSuccessOrder" id="modal-order-success">
			<div class="modalTitle">
				<?= Loc::getMessage('SUCCESS') ?>
			</div>
			<p>
				<?= Loc::getMessage('ORDER_PLACED') ?>
			</p>
			<a href="/events/<?= $arResult['EVENT']->getCode() ?>/" class="styler modalSend">
				<?= Loc::getMessage('HOME') ?>
			</a>
			<br>
			<a href="/personal/orders/">
				<?= Loc::getMessage('REVIEW_ORDER') ?>
			</a>
			<div class="clear"></div>
		</div>
		
		
		
		<? // Окно: ошибка оформления заказа // ?>
		<div class="modal modalError" id="modal-order-error">
			<div class="modalClose arcticmodal-close"></div>
			<div class="modalTitle">
				<?= Loc::getMessage('ERROR') ?>
			</div>
			<div class="modalContent">
				<?= Loc::getMessage('ORDER_PLACE_ERROR') ?>
			</div>
		</div>
		
	</div>
<? } ?>