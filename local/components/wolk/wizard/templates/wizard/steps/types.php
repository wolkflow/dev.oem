<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<? $this->setFrameMode(true); ?>

<? use Bitrix\Main\Localization\Loc; ?>
<? use Wolk\Core\Helpers\Text as TextHelper ?>

<? $lang = \Bitrix\Main\Context::getCurrent()->getLanguage() ?>

<div class="indexpage">
    <div class="pagetitle">
		<?= $arResult['EVENT']->getHeader() ?>
    </div>
    <div class="pagedescription">
		<?= $arResult['EVENT']->getSubHeader() ?>
    </div>
    <div class="indexpage__generalinfocontainer">
        <div class="pagesubtitle">
            <?= Loc::getMessage('GENERAL_INFORMATION') ?>
        </div>
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
                                    <?= $i++ ?>. <?= $document['TITLE'] ?>
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
                                    <?= $i++ ?>. <?= $document['TITLE'] ?>
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
                                <div class="generalInfoContent pretty">
                                    <?= $document['HTML'] ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <? } ?>
            <? } ?>
        <? } ?>
    </div>
    
    <? // Выбор стенда // ?>
    <div class="indexpage__choosestandcontainer">
        
        <div class="indexpage__choosestand system">
            <div class="indexpage__choosestandtitlecontainer">
                <div class="indexpage__choosestandtitle">
                    <?= Loc::getMessage('TYPE_STANDARD_STAND') ?>
                </div>
                <form method="post" action="<?= $arResult['LINKS']['NEXT'] ?>" class="indexpage__choosestandform js-stand-select-form">
                    <input type="hidden" name="TYPE" value="standard" />
					<div class="indexpage__choosestandinputscontainer">
                        <div class="indexpage__choosestandinputcontainer">
                            <div class="indexpage__choosestandinputtitle">
								<?= Loc::getMessage('WIDTH') ?> <span>(<?= Loc::getMessage('MEASURE_M') ?>)</span>
							</div>
                            <input required name="WIDTH" type="text" value="" class="js-stand-width" />
						</div>
                        <div class="indexpage__choosestandinputcontainer">
                            <div class="indexpage__choosestandinputtitle">
								<?= Loc::getMessage('DEPTH') ?> <span>(<?= Loc::getMessage('MEASURE_M') ?>)</span>
							</div>
                            <input required name="DEPTH" type="text" value="" class="js-stand-depth" />
						</div>
                    </div>

                    <div class="indexpage__choosestandtypecontainer">
                        <div class="indexpage__choosestandtypetitle">
							<?= Loc::getMessage('STAND_TYPE') ?>
						</div>
                        <label for="row" class="indexpage__choosestandtype">
                            <input checked type="radio" value="row" name="SFORM" id="row" class="js-stand-sform" />
                            <span><?= Loc::getMessage('TYPE_ROW') ?></span>
                            <span class="indexpage__choosestandtypeicon"></span>
                        </label>
                        <label for="corner" class="indexpage__choosestandtype">
                            <input type="radio" value="corner" name="SFORM" id="corner" class="js-stand-sform" />
                            <span><?= Loc::getMessage('TYPE_CORNER') ?></span>
                            <span class="indexpage__choosestandtypeicon"></span>
                        </label>
                        <label for="head" class="indexpage__choosestandtype">
                            <input type="radio" value="head" name="SFORM" id="head" class="js-stand-sform" />
                            <span><?= Loc::getMessage('TYPE_HEAD') ?></span>
                            <span class="indexpage__choosestandtypeicon"></span>
                        </label>
                        <label for="island" class="indexpage__choosestandtype">
                            <input type="radio" value="island" name="SFORM" id="island" class="js-stand-sform" />
                            <span><?= Loc::getMessage('TYPE_ISLAND') ?></span>
                            <span class="indexpage__choosestandtypeicon"></span>
                        </label>
                    </div>
                    <button type="submit" class="indexpage__choosestandnextbutton">
						<?= Loc::getMessage('NEXT') ?>
					</button>
                </form>
            </div>
            <img src="/local/templates/.default/build/images/index/stand-system.jpg" />
        </div>
        
        
        <div class="indexpage__choosestand individual">
            <div class="indexpage__choosestandtitlecontainer">
                <div class="indexpage__choosestandtitle">
                    <?= Loc::getMessage('TYPE_INDIVIDUAL_STAND') ?>
                </div>

                <form method="post" action="<?= $arResult['LINKS']['NEXT'] ?>" class="indexpage__choosestandform js-stand-select-form">
                    <input type="hidden" name="TYPE" value="individual" />
					<div class="indexpage__choosestandinputscontainer">
                        <div class="indexpage__choosestandinputcontainer">
                            <div class="indexpage__choosestandinputtitle">
								<?= Loc::getMessage('WIDTH') ?> <span>(<?= Loc::getMessage('MEASURE_M') ?>)</span>
							</div>
                            <input required name="WIDTH" type="text" value="" class="js-stand-width" />
						</div>
                        <div class="indexpage__choosestandinputcontainer">
                            <div class="indexpage__choosestandinputtitle">
								<?= Loc::getMessage('DEPTH') ?> <span>(<?= Loc::getMessage('MEASURE_M') ?>)</span>
							</div>
                            <input required name="DEPTH" type="text" value="" class="js-stand-depth" />
						</div>
                    </div>
                    <button type="submit" class="indexpage__choosestandnextbutton">
						<?= Loc::getMessage('NEXT') ?>
					</button>
                </form>
            </div>
			<img src="<?= DEFAULT_TEMPLATE_PATH ?>/build/images/index/stand-individual.jpg" />
		</div>
	</div>
    
</div>
