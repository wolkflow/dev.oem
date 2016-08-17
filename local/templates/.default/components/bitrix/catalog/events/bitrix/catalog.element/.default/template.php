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

$lang = strtoupper(\Bitrix\Main\Context::getCurrent()->getLanguage());
?>
<div class="indexpage">
    <div class="pagetitle">
		<?= $arResult['PROPERTIES']['LANG_HEADER_'.$lang]['VALUE'] ?>
		<? /*
        <?= Loc::getMessage('welcome') ?><br>
        Online Exhibitors Manual!
		*/ ?>
    </div>
    <div class="pagedescription">
		<?= $arResult['PROPERTIES']['LANG_SUBHEADER_'.$lang]['~VALUE']['TEXT'] ?>
        <? /* Helper::includeFile('home_desc_'.$lang) */ ?>
    </div>
    <div class="indexpage__generalinfocontainer">
        <div class="pagesubtitle">
            <?= Loc::getMessage('General information') ?>
        </div>
	    <!-- Блоки в которые надо вывести список -->
		<!--
		    Список (который на 41 строке) нужно автоматически делить на 2 и выводить в 2 разных блока.
		 -->
         
        <?  // Разбиение документов.
            $arResult['DOCUMENTS'] = array_chunk($arResult['DOCUMENTS'], count($arResult['DOCUMENTS']) / 2 + 1, true);
        ?>
         
        <? if (!empty($arResult['DOCUMENTS'])) { ?>
            <div class="indexInfo">
                <? $i = 1 ?>
                <div class="indexInfo__left">
                    <ul>
                    <? foreach ($arResult['DOCUMENTS'][0] as $document) { ?>
                        <li>
                            <a href="javascript:void(0)" data-modal="#document-<?= $document['ID'] ?>">
                                <?= $i++ ?>. <?= $document['TITLE'] ?> <? // Loc::getMessage('Order and Delivery Conditions')?>
                            </a>
                        </li>
                    <? } ?>
                    </ul>
                </div>
                <div class="indexInfo__right">
                    <ul>
                    <? foreach ($arResult['DOCUMENTS'][1] as $document) { ?>
                        <li>
                            <a href="javascript:void(0)" data-modal="#document-<?= $document['ID'] ?>">
                                <?= $i++ ?>. <?= $document['TITLE'] ?> <? // Loc::getMessage('Order and Delivery Conditions')?>
                            </a>
                        </li>
                    <? } ?>
                    </ul>
                </div>
                <div class="clear"></div>
            </div>
            
            <? foreach ($arResult['DOCUMENTS'] as $docchunk) { ?>
                <? foreach ($docchunk as $document) { ?>
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
            <? } ?>
        <? } ?>
	    <!--// .Блоки в которые надо вывести список -->
        
        
        <? /*
		<? if (!empty($arResult['DOCUMENTS'])) { ?>
			<div class="indexpage__generalinfocolumns">
				<? $i = 1 ?>
                <? // Вывод двух частей документов. // ?>
				<? foreach ($arResult['DOCUMENTS'] as $docschunk) { ?>
                    
                    <? // Вывод спсика документов из одной части. // ?>
                    <? foreach ($docschunk as $document) { ?>
                        <div class="indexpage__generalinfocolumn q11">
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
                <? } ?>
			</div>
		<? } ?>
        */ ?>
    </div>
    
    <div class="indexpage__choosestandcontainer">
        <!-- Выбор стенда -->
        <div class="indexpage__choosestand system">
            <div class="indexpage__choosestandtitlecontainer">
                <div class="indexpage__choosestandtitle"><?=Loc::getMessage('System stand')?></div>
                <form class="indexpage__choosestandform">
                    <input type="hidden" name="ORDER_TYPE" value="standart" />
                    <div class="indexpage__choosestandinputscontainer">
                        <div class="indexpage__choosestandinputcontainer">
                            <div class="indexpage__choosestandinputtitle">
								<?=Loc::getMessage('Stand width')?> <span>(<?=Loc::getMessage('m')?>)</span>
							</div>
                            <input required name="WIDTH" type="text" value="" />
						</div>
                        <div class="indexpage__choosestandinputcontainer">
                            <div class="indexpage__choosestandinputtitle">
								<?=Loc::getMessage('Stand length')?> <span>(<?=Loc::getMessage('m')?>)</span>
							</div>
                            <input required name="DEPTH" type="text" value="" />
						</div>
                    </div>

                    <div class="indexpage__choosestandtypecontainer">
                        <div class="indexpage__choosestandtypetitle">
							<?= Loc::getMessage('Stand type') ?>
						</div>
                        <label for="row" class="indexpage__choosestandtype">
                            <input checked type="radio" value="row" name="standtype" id="row"><span><?= Loc::getMessage('Row') ?></span>
                            <span class="indexpage__choosestandtypeicon"></span>
                        </label>
                        <label for="corner" class="indexpage__choosestandtype">
                            <input type="radio" value="corner" name="standtype" id="corner"><span><?= Loc::getMessage('Corner') ?></span>
                            <span class="indexpage__choosestandtypeicon"></span>
                        </label>
                        <label for="peninsular" class="indexpage__choosestandtype">
                            <input type="radio" value="head" name="standtype" id="peninsular"><span><?= Loc::getMessage('Head') ?></span>
                            <span class="indexpage__choosestandtypeicon"></span>
                        </label>
                        <label for="island" class="indexpage__choosestandtype">
                            <input type="radio" value="island" name="standtype" id="island"><span><?= Loc::getMessage('Insel') ?></span>
                            <span class="indexpage__choosestandtypeicon"></span>
                        </label>
                    </div>

                    <button type="submit" class="indexpage__choosestandnextbutton"><?= Loc::getMessage('next') ?></button>
                </form>
            </div>
            <img src="/local/templates/.default/build/images/index/stand-system.jpg" />
        </div>

        <div class="indexpage__choosestand individual">
            <div class="indexpage__choosestandtitlecontainer">
                <div class="indexpage__choosestandtitle"><?= Loc::getMessage('individual stand') ?></div>

                <form class="indexpage__choosestandform">
                    <input type="hidden" name="ORDER_TYPE" value="individual" />
                    <div class="indexpage__choosestandinputscontainer">
                        <div class="indexpage__choosestandinputcontainer">
                            <div class="indexpage__choosestandinputtitle">
								<?= Loc::getMessage('Stand width') ?> <span>(<?= Loc::getMessage('m') ?>)</span>
							</div>
                            <input required name="WIDTH" type="text" value="" />
						</div>
                        <div class="indexpage__choosestandinputcontainer">
                            <div class="indexpage__choosestandinputtitle">
								<?= Loc::getMessage('Stand length') ?> <span>(<?= Loc::getMessage('m') ?>)</span>
							</div>
                            <input required name="DEPTH" type="text" value="" />
						</div>
                    </div>
                    <button type="submit" class="indexpage__choosestandnextbutton"><?= Loc::getMessage('next') ?></button>
                </form>
            </div>
			<img src="<?= DEFAULT_TEMPLATE_PATH ?>/build/images/index/stand-individual.jpg" />
		</div>
	</div>
    <!--// .Выбор стенда -->
</div>

