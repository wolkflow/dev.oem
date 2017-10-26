<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<? $this->setFrameMode(true); ?>

<? use Bitrix\Main\Localization\Loc; ?>
<? use Wolk\Core\Helpers\Text as TextHelper ?>

<? $lang = \Bitrix\Main\Context::getCurrent()->getLanguage() ?>

<?  // Языковые переменные.
    $GLOBALS['JSVARS']['LANGS']['CHOOSEN'] = Loc::getMessage('CHOOSEN');
    $GLOBALS['JSVARS']['LANGS']['CHOOSE']  = Loc::getMessage('CHOOSE');
?>

<div id="step1">
    <form action="<?= $arResult['LINKS']['NEXT'] ?>" method="post">
        <? if ($arResult['CONTEXT']->getType() == \Wolk\OEM\Context::TYPE_INDIVIDUAL) { ?>
            
            <div class="standspagetop" id="preselect">
			
				<div id="js-preselect-wrapper-id" style="display: none;">
					<input id="js-form-input-stand-id" type="hidden" name="STAND" value="<?= (!empty($arResult['PRESTAND'])) ? ($arResult['PRESTAND']->getID()) : ('') ?>" />
					
					<div class="pagedescription">
						<?= Loc::getMessage('PRESELECT_STAND_NOTE') ?>
					</div>
					
					<? // Выбор стандартного стенда // ?>
					<div>
						<div class="pagetitle">
							<?= Loc::getMessage('CURRENT_STAND') ?>
						</div>
						<div id="js-prestand-id" class="standspagetop__currentstandcontainer customizable_border">
							<div class="standspagetop__currentstanddescription">
								<p class="js-stand-description"></p>
								<ul class="js-stand-includes"></ul>
							</div>
							<img src="" class="standspagetop__photo js-stand-image" />
							<a href="javascript:void(0)" class="standspagetop__continuebutton customizable js-wizard-next-step js-submit">
								<?= Loc::getMessage('CONTINUE') ?>
							</a>
						</div>
					</div>
				</div>
				
				<div id="js-noselect-wrapper-id">
					<div class="pagedescription">
						<?= Loc::getMessage('SELECTED_INDIVIDUAL_STAND_NOTE') ?>
					</div>
					<a href="javascript:void(0)" class="standspagetop__continuebutton customizable js-submit">
						<?= Loc::getMessage('CONTINUE') ?>
					</a>
				</div>
            </div>
            
        <? } else { ?>
			
			<div class="standspagetop" id="preselect">
			
				<div id="js-preselect-wrapper-id">
					<input id="js-form-input-stand-id" type="hidden" name="STAND" value="<?= (!empty($arResult['PRESTAND'])) ? ($arResult['PRESTAND']->getID()) : ('') ?>" />
					
					<? if (!empty($arResult['PRESTAND'])) { ?>
						<div class="pagedescription">
							<?= Loc::getMessage('PRESELECT_STAND_NOTE') ?>
						</div>
					<? } ?>
					
					<? // Выбор стандартного стенда // ?>
					<div>
						<div class="pagetitle">
							<?= Loc::getMessage('CURRENT_STAND') ?>
						</div>
						<? if (!empty($arResult['PRESTAND']) && !empty($arResult['PREOFFER'])) { ?>
							<div id="js-prestand-id" class="standspagetop__currentstandcontainer customizable_border">
								<div class="standspagetop__currentstanddescription">
									<p class="js-stand-description">
										<?= $arResult['PRESTAND']->getDescription($lang) ?>
									</p>
									<? $products = $arResult['PREOFFER']->getBaseProducts() ?>
									<? if (!empty($products)) { ?>
										<ul class="js-stand-includes">
											<?= Loc::getMessage('INCLUDES') ?>:
											<? foreach ($products as $product) { ?>
												<li>
													<?= $product->getCount() ?> &times; <?= $product->getTitle() ?>
												</li>
											<? } ?>
										</ul>
									<? } ?>
								</div>
								<img src="/i/?src=<?= $arResult['PRESTAND']->getPreviewImageSrc() ?>&w=420&h=270" class="standspagetop__photo js-stand-image" />
								<a href="javascript:void(0)" class="standspagetop__continuebutton customizable js-wizard-next-step js-submit">
									<?= Loc::getMessage('CONTINUE') ?>
								</a>
							</div>
						<? } ?>
					</div>
				</div>
			</div>
        <? } ?>
        
        <? // Выбор стеднов // ?>
        <div class="standstypescontainer">
            <? if (!$arResult['EVENT']->showExternalLink()) { ?>
                
                <div class="pagetitle">
                    <?= Loc::getMessage('BETTER_STANDART') ?>
                    <div class="pagetitle-note">
                        <?= Loc::getMessage('BETTER_STANDART_NOTE') ?>
                    </div>
                </div>
                <div id="js-stands-wrapper-id" class="standstypescontainer__standscontainer standsTypesRow">
                    <? $c = 0 ?>
                    <? foreach ($arResult['STANDS'] as $standoffer) { ?>
                        <? $stand = $standoffer->getStand() ?>
                        <div class="js-stand-block standstypescontainer__standcontainer <?= ($c % 2 == 0) ? ('standsTypesLeft') : ('standsTypesRight') ?>">
                            
                            <div class="pagesubtitle customizable_border">
                                <?= $stand->getTitle() ?>
                            </div>
                            <div class="standstypescontainer__pricecontiner">
                                <?= FormatCurrency($standoffer->getPrice(), $arResult['CURRENCY']) ?>
                                <span>
                                      <?= FormatCurrency($standoffer->getPriceArea($arResult['AREA']), $arResult['CURRENCY']) ?> 
                                    / <?= Loc::getMessage('M2') ?>
                                </span>
                                <div data-id="<?= $stand->getID() ?>" class="js-stand-choose-button standstypescontainer__choosebutton customizable <?= ($arResult['DATA']['STAND'] == $stand->getID()) ? ('current') : ('') ?>">
                                    <?= ($arResult['DATA']['STAND'] == $stand->getID()) ? (Loc::getMessage('CHOOSEN')) : (Loc::getMessage('CHOOSE')) ?>
                                </div>
                            </div>
                            
                            <img height="138" src="/i/?src=<?= $stand->getPreviewImageSrc() ?>&w=420&h=270" class="standstypescontainer__photo js-stand-image" />

                            <div class="standstypescontainer__description">
                                <p class="js-stand-description">
                                    <?= $stand->getDescription() ?>
                                </p>
                                <? $products = $standoffer->getBaseProducts() ?>
                                <? if (!empty($products)) { ?>
                                    <ul class="js-stand-includes">
                                        <?= Loc::getMessage('INCLUDES') ?>:
                                        <? foreach ($products as $product) { ?>
                                            <li>
                                                <?= $product->getCount() ?> &times; <?= $product->getTitle() ?>
                                            </li>
                                        <? } ?>
                                    </ul>
                                <? } ?>
                            </div>
                        </div>
                        <? $c++ ?>
                    <? } ?>
                </div>
            
            <? } else { ?>
            
                <div class="pagedescription">
                    <p>
                        <?= Loc::getMessage('EXTERLAN_TEXT') ?>
                        <a href="http://<?= $arResult['EVENT']->getExternalLink() ?>" target="_blank">
                            <?= $arResult['EVENT']->getExternalLink() ?>
                        </a>
                    </p>
                </div>
                
            <? } ?>
        </div>
    </form>
</div>
