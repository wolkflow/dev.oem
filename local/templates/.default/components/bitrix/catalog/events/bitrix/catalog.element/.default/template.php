<? use Bitrix\Main\Localization\Loc;

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
?>
<div class="indexpage">
    <div class="pagetitle">
        <?= Loc::getMessage('welcome') ?><br>
        Online Exhibitors Manual!
    </div>
    <div class="pagedescription">
        <? Helper::includeFile('home_desc_'.strtoupper(\Bitrix\Main\Context::getCurrent()->getLanguage())) ?>
    </div>
    <div class="indexpage__generalinfocontainer">
        <div class="pagesubtitle">
            <?=Loc::getMessage('General information')?>
        </div>
		<? if (!empty($arResult['DOCUMENTS'])) { ?>
			<div class="indexpage__generalinfocolumns">
				<? $i = 1 ?>
				<? foreach ($arResult['DOCUMENTS'] as $document) { ?>
					<div class="indexpage__generalinfocolumn">
						<a href="javascript:void(0)" data-modal="#document-<?= $document['ID'] ?>">
							<?= $i++ ?>. <?= $document['TITLE'] ?> <? // Loc::getMessage('Order and Delivery Conditions')?>
						</a>
					</div>
					
					<div class="hide">
						<div class="modal modalContact" id="document-<?= $document['ID'] ?>">
							<div class="modalClose arcticmodal-close"></div>
							<div class="modalTitle"><?= $document['TITLE'] ?></div>
							<div class="modalContent">
								<div class="generalInfoContent">
									<?= $document['HTML'] ?>
								</div>
							</div>
						</div>
					</div>
				<? } ?>
			</div>
		<? } ?>
    </div>
    <div class="indexpage__choosestandcontainer">
        <!-- Выбор стенда -->
        <div class="indexpage__choosestand system">
            <div class="indexpage__choosestandtitlecontainer">
                <div class="indexpage__choosestandtitle"><?=Loc::getMessage('System stand')?></div>
                <form class="indexpage__choosestandform">
                    <input type="hidden" name="ORDER_TYPE" value="standart">
                    <div class="indexpage__choosestandinputscontainer">
                        <div class="indexpage__choosestandinputcontainer">
                            <div class="indexpage__choosestandinputtitle"><?=Loc::getMessage('Stand width')?> <span>(<?=Loc::getMessage('m')?>)</span></div>
                            <input required name="WIDTH" type="text" value=""></div>
                        <div class="indexpage__choosestandinputcontainer">
                            <div class="indexpage__choosestandinputtitle"><?=Loc::getMessage('Stand length')?> <span>(<?=Loc::getMessage('m')?>)</span></div>
                            <input required name="DEPTH" type="text" value=""></div>
                    </div>

                    <div class="indexpage__choosestandtypecontainer">
                        <div class="indexpage__choosestandtypetitle"><?=Loc::getMessage('Stand type')?></div>
                        <label for="row" class="indexpage__choosestandtype">
                            <input checked type="radio" value="row" name="standtype" id="row"><span><?=Loc::getMessage('Row')?></span>
                            <span class="indexpage__choosestandtypeicon"></span>
                        </label>
                        <label for="corner" class="indexpage__choosestandtype">
                            <input type="radio" value="corner" name="standtype" id="corner"><span><?=Loc::getMessage('Corner')?></span>
                            <span class="indexpage__choosestandtypeicon"></span>
                        </label>
                        <label for="head" class="indexpage__choosestandtype">
                            <input type="radio" value="head" name="standtype" id="head"><span><?=Loc::getMessage('Head')?></span>
                            <span class="indexpage__choosestandtypeicon"></span>
                        </label>
                        <label for="insel" class="indexpage__choosestandtype">
                            <input type="radio" value="insel" name="standtype" id="insel"><span><?=Loc::getMessage('Insel')?></span>
                            <span class="indexpage__choosestandtypeicon"></span>
                        </label>
                    </div>

                    <button type="submit" class="indexpage__choosestandnextbutton"><?=Loc::getMessage('next')?></button>
                </form>
            </div>
            <img src="/local/templates/.default/build/images/index/stand-system.jpg">
        </div>

        <div class="indexpage__choosestand individual">
            <div class="indexpage__choosestandtitlecontainer">
                <a href="#" class="indexpage__choosestandtitle"><?=Loc::getMessage('individual stand')?></a>

                <form class="indexpage__choosestandform">
                    <input type="hidden" name="ORDER_TYPE" value="individual">
                    <div class="indexpage__choosestandinputscontainer">
                        <div class="indexpage__choosestandinputcontainer">
                            <div class="indexpage__choosestandinputtitle"><?=Loc::getMessage('Stand width')?> <span>(<?=Loc::getMessage('m')?>)</span></div>
                            <input required name="WIDTH" type="text" value=""></div>
                        <div class="indexpage__choosestandinputcontainer">
                            <div class="indexpage__choosestandinputtitle"><?=Loc::getMessage('Stand length')?> <span>(<?=Loc::getMessage('m')?>)</span></div>
                            <input required name="DEPTH" type="text" value=""></div>
                    </div>
                    <button type="submit" class="indexpage__choosestandnextbutton"><?=Loc::getMessage('next')?></button>
                </form>

            </div>
            <img src="/local/templates/.default/build/images/index/stand-individual.jpg"></div>
    </div>
    <!--// .Выбор стенда -->
</div>