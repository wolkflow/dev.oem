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
        <div class="pagesubtitle customizable_border">
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


	    <? //+++++ИК ?>
		<? if ($arResult['EVENT']->getCode() == 'showroom') { ?>
			<a href="javascript:void(0)" class="btn-view js-btn-view">
				<?= Loc::getMessage('VIEW_INTERACTIVE_MAP') ?>
			</a>
			
			<div class="hide">
				<div class="modal modalContact" id="intMap">
					<div class="modalClose arcticmodal-close"></div>
					<a href="javascript:void(0)" class="open-3d js-open-3d">
						<?= Loc::getMessage('VIEW_3D_MAP') ?>
					</a>
					<div class="modalTitle">
						<?= Loc::getMessage('INTERACTIVE_MAP') ?>
					</div>
					<div class="modalContent">
						<div class="intMap">
							<div class="intMapFilter">
								<a id="js-map-filters-id" href="javascript:void(0)" class="intMapFilterTrigger js-intMapFilterTrigger">
									<?= Loc::getMessage('INTERACTIVE_MAP_CATEGOREIS') ?>
								</a>
								<ul class="intMapFilterList">
									<li>
										<input type="checkbox" id="shops" data-type="SHOP" checked="" />
										<div class="switch">
											<div class="switchLabel"><i></i></div>
										</div>
										<label for="shops">Магазины</label>
									</li>
									<li>
										<input type="checkbox" id="hotel" data-type="HOTEL" checked="" />
										<div class="switch">
											<div class="switchLabel"><i></i></div>
										</div>
										<label for="hotel">Гостиница</label>
									</li>
									<li>
										<input type="checkbox" id="metro" data-type="METRO" checked="" />
										<div class="switch">
											<div class="switchLabel"><i></i></div>
										</div>
										<label for="metro">Метро</label>
									</li>
									<li>
										<input type="checkbox" id="ppaid" data-type="PPAID" checked="" />
										<div class="switch">
											<div class="switchLabel"><i></i></div>
										</div>
										<label for="ppaid">Парковка платная</label>
									</li>
									<li>
										<input type="checkbox" id="pfree" data-type="PFREE" checked="" />
										<div class="switch">
											<div class="switchLabel"><i></i></div>
										</div>
										<label for="pfree">Парковка бесплатная</label>
									</li>
									<li>
										<input type="checkbox" id="pvip" data-type="PVIP" checked="" />
										<div class="switch">
											<div class="switchLabel"><i></i></div>
										</div>
										<label for="pvip">Парковка VIP</label>
									</li>
									<li>
										<input type="checkbox" id="banks" data-type="BANKS" checked="" />
										<div class="switch">
											<div class="switchLabel"><i></i></div>
										</div>
										<label for="banks">Банки</label>
									</li>
									<li>
										<input type="checkbox" id="atm" data-type="ATM" checked="" />
										<div class="switch">
											<div class="switchLabel"><i></i></div>
										</div>
										<label for="atm">Банкомат</label>
									</li>
									<li>
										<input type="checkbox" id="restaurant" data-type="RESTAURANT" checked="" />
										<div class="switch">
											<div class="switchLabel"><i></i></div>
										</div>
										<label for="restaurant">Ресторан</label>
									</li>
									<li>
										<input type="checkbox" id="service" data-type="SERVICE" checked="" />
										<div class="switch">
											<div class="switchLabel"><i></i></div>
										</div>
										<label for="service">Сервисный центр</label>
									</li>
									<li>
										<input type="checkbox" id="pharmacy" data-type="PHARMACY" checked="" />
										<div class="switch">
											<div class="switchLabel"><i></i></div>
										</div>
										<label for="pharmacy">Аптека</label>
									</li>
								</ul>
							</div>
							<div class="intMapInner">
								<div id="mapMarks"></div>
							</div>
						</div>
						<div class="intMapTitle">
							<div class="intMapImage">
								<img src="/local/templates/.default/build/images/tmp_inttitle.png" />
							</div>
							<?= $arResult['PLACE']->getTitle() ?>
						</div>
					</div>
				</div>
				<div class="modal modalContact" id="map3d">
					<div class="modalClose arcticmodal-close"></div>
					<a href="javascript:void(0)" class="open-int js-btn-view arcticmodal-close">
						<?= Loc::getMessage('INTERACTIVE_MAP') ?>
					</a>
					<div class="modalTitle">
						<?= Loc::getMessage('VIEW_3D_MAP') ?>
					</div>
					<div class="modalContent">
						<img src="/local/templates/.default/build/images/tmp_ddd.png" class="mod3d_image" />
					</div>
				</div>
			</div>
	    <? } ?>

    </div>
	
    <? // Выбор стенда // ?>
    <div class="indexpage__choosestandcontainer">
        
		<? if ($arResult['EVENT']->isUseTypeStandard()) { ?>
			<div class="indexpage__choosestand system">
				<div class="indexpage__choosestandtitlecontainer">
					<div class="indexpage__choosestandtitle customizable">
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
						<button type="submit" class="indexpage__choosestandnextbutton customizable">
							<?= Loc::getMessage('NEXT') ?>
						</button>
					</form>
				</div>
				<img src="/local/templates/.default/build/images/index/stand-system.jpg" />
			</div>
		<? } ?>
        
        <? if ($arResult['EVENT']->isUseTypeIndividual()) { ?>
			<div class="indexpage__choosestand individual">
				<div class="indexpage__choosestandtitlecontainer">
					<div class="indexpage__choosestandtitle customizable">
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
						<button type="submit" class="indexpage__choosestandnextbutton customizable">
							<?= Loc::getMessage('NEXT') ?>
						</button>
					</form>
				</div>
				<img src="<?= DEFAULT_TEMPLATE_PATH ?>/build/images/index/stand-individual.jpg" />
			</div>
		<? } ?>
		
	</div>
</div>




<? if ($arResult['EVENT']->getCode() == 'showroom') { ?>

	<script type="text/javascript">
		ymaps.ready(function () {
			var myMap = new ymaps.Map('mapMarks', {
				center: [55.753215, 37.622504],
				zoom: 9,
				controls: ['zoomControl']
			});

			// Создаём макет содержимого.
			MyIconContentLayout = ymaps.templateLayoutFactory.createClass(
				'<div style="color: #FFFFFF; font-weight: bold;">$[properties.iconContent]</div>'
			);
			
			var placemark;
			
		   <? foreach ($arResult['MAPOBJECTS'] as $mapobject) { ?>
				placemark = new ymaps.Placemark([<?= $mapobject->getCoordLng() ?>, <?= $mapobject->getCoordLat() ?>], {
					hintContent: '<?= $mapobject->getTitle() ?>',
					balloonContent: '<?= $mapobject->getTitle() ?>'
				}, {
					// Необходимо указать данный тип макета.
					iconLayout: 'default#image',
					// Своё изображение иконки метки.
					iconImageHref: '/local/templates/.default/build/images/ymap/mk-<?= $mapobject->getTypeCode() ?>.png',
					// Размеры метки.
					iconImageSize: [30, 45],
					// Смещение левого верхнего угла иконки относительно её "ножки" (точки привязки).
					iconImageOffset: [-15, -45]
				});
				
				myMap.geoObjects.add(placemark);
		   <? } ?>
		});
		
		
		$(document).ready(function() {
			$(document).on('click', '.js-btn-view', function() {
				$('#intMap').arcticmodal();
			});
			
			$(document).on('click', '.js-map-filter', function() {
				console.log('js');
				var $list = $(this).next('.intMapFilterList');
				if ($list.hasClass('active')) {
					$list.removeClass('active');
				} else {
					$list.addClass('active');
				}
				return false;
			});
			
			$(document).on('click', '.js-open-3d', function() {
				$('#map3d').arcticmodal();
			});
		});
	</script>
<? } ?>