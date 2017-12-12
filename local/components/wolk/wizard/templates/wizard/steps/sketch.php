<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<? $this->setFrameMode(true); ?>

<? use Bitrix\Main\Localization\Loc; ?>
<? use Wolk\Core\Helpers\Text as TextHelper ?>

<div class="pagetitle">
    <?= Loc::getMessage('TITLE_SKETCH') ?>
</div>
<div class="pagedescription">
    <? Helper::includeFile('sketch_desc_' . \Bitrix\Main\Context::getCurrent()->getLanguage()); ?>
	
	<div class="active pagetitle__button customizable" data-modal="#js-sketch-help-id">
        <?= Loc::getMessage('SKETCH_HELP') ?>
    </div>
</div>

<div
    id="designer"
    style="margin-top: 40px; width: 940px; height: 680px;"
    onmouseout="javascript: $sketch.stopDragging();"
></div>


<? if (!empty($arResult['FASCIA'])) { ?>
	<div class="fasciacontainer">
		<? foreach ($arResult['FASCIA'] as $basketitem) { ?>
			<? $product = $basketitem->getElement() ?>
			<div class="js-pricetype-symbols">
				<div class="js-product-block js-product-block-<?= $product->getSectionID() ?>" data-bid="<?= $basketitem->getID() ?>" data-price-type="symbols">
					<div class="equipmentcontainer__itemcontainer">
						<div class="js-symbols-wrapper itemCount" data-pid="<?= $basketitem->getProductID() ?>" data-limit="<?= $arResult['EVENT']->getPayLimitSymbols() ?>">
							<div class="serviceItem__subtitle">
								<input type="hidden" class="js-product-element" value="<?= $basketitem->getProductID() ?>" />
							</div>
						</div>
						<div class="js-property-block">
							<div class="js-param-block js-param-text" data-code="TEXT">
								<div class="serviceItem__left">
									<div class="serviceItem__subtitle">
										<?= Loc::getMessage('TEXT') ?>        
									</div>
									<div class="itemText_custom">
										<input class="js-param-required js-param-value js-param-x-value js-text styler" name="TEXT" type="text" value="<?= $basketitem->getParam('TEXT') ?>" />
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		<? } ?>
	</div>
<? } ?>

<br/>

<form id="js-form-sketch-id" class="js-form" method="post" action="<?= $arResult['LINKS']['NEXT'] ?>" enctype="multipart/form-data">
    <input id="js-sketch-scene-id" type="hidden" name="SKETCH_SCENE" />
    <input id="js-sketch-image-id" type="hidden" name="SKETCH_IMAGE" />
    
	<hr/>
	
    <div class="renders">
        <div id="js-renders-images-id" class="render-images">
			<div id="js-render-image-id" class="render-image"></div>
			<button id="js-render-id" style="width: auto;" data-code="<?= $arResult['EVENT']->getCode() ?>" class="button styler customizable">
				<?= Loc::getMessage('RENDER') ?>
			</button>
		</div>
    </div>
    
    <div class="sketchAfter">
        <div class="sketchAfterLeft">
            <div class="commentsForm">
                <div class="commentsForm__title">
                    <?= Loc::getMessage('COMMENTS') ?>
                </div>
                <textarea id="js-order-comments-id" name="COMMENTS" placeholder="<?= Loc::getMessage('ADDITIONAL_INFO') ?>"><?= strip_tags($arResult['COMMENTS']) ?></textarea>
            </div>
        </div>
        <div class="sketchAfterRight">
            <div class="pagetitle">
                <?= Loc::getMessage('CONFIGURATION') ?>
            </div>
            <div class="reviewconfigurationcontainer customizable_border">
                <div class="reviewconfigurationcontainer__configuration">
                    <span class="reviewconfigurationcontainer__configurationtitle">
                        <?= Loc::getMessage('SYSTEM_STAND') ?>:
                    </span>
                    <?= $arResult['STAND']->getTitle() ?>
                </div>
                <div class="reviewconfigurationcontainer__configuration">
                    <span class="reviewconfigurationcontainer__configurationtitle">
                        <?= Loc::getMessage('WIDTH') ?> &amp; <?= Loc::getMessage('DEPTH') ?>:
                    </span>
                    <?= $arResult['WIDTH'] ?> &times; <?= $arResult['DEPTH'] ?>
                </div>
                <div class="reviewconfigurationcontainer__configuration">
                    <span class="reviewconfigurationcontainer__configurationtitle">
                        <?= Loc::getMessage('TYPE') ?>:
                    </span>
                    <?= Loc::getMessage('TYPE_' . strtoupper($arResult['SFORM'])) ?>
                </div>
                <div class="reviewconfigurationcontainer__configuration">
                    <span class="reviewconfigurationcontainer__configurationtitle">
                        <?= Loc::getMessage('EXHIBITION') ?>:
                    </span>
                    <?= $arResult['EVENT']->getTitle() ?>
                </div>
                <div class="reviewconfigurationcontainer__configuration">
                    <span class="reviewconfigurationcontainer__configurationtitle">
                        <?= Loc::getMessage('LOCATION') ?>:
                    </span>
                    <?= $arResult['EVENT']->getPlaceTitle() ?>
                </div>
            </div>
        </div>
        <div class="clear"></div>
    </div>
	
	<div class="sketchNav">
        <a href="<?= $arResult['LINKS']['PREV'] ?>" class="basketcontainer__backstepbutton customizable">
            <?= Loc::getMessage('PREV') ?>
        </a>
        <a id="js-sketch-save-id" href="<?= $arResult['LINKS']['NEXT'] ?>" class="basketcontainer__nextstepbutton customizable">
            <?= Loc::getMessage('NEXT') ?>
        </a>
    </div>
</form>

<div class="hide">
    <div class="modal" id="js-sketch-help-id">
        <div class="modalClose arcticmodal-close"></div>
        <div class="modalTitle">
			<?= Loc::getMessage('SKETCH_HELP_HEAD') ?>
		</div>
		<div class="modalBody sketch-help">
			<?= Loc::getMessage('SKETCH_HELP_TEXT') ?>
			<br/>
			<div class="legend">
				<img src="/local/templates/.default/build/images/help/panel.jpg" width="300" />
				<br/>
				<?= Loc::getMessage('SKETCH_HELP_LEGEND_PANEL') ?>
			</div>
			<div class="legend">
				<img src="/local/templates/.default/build/images/help/move.jpg" width="300" />
				<br/>
				<?= Loc::getMessage('SKETCH_HELP_LEGEND_MOVE') ?>
			</div>
			<div class="legend">
				<img src="/local/templates/.default/build/images/help/rotate.jpg" width="300" />
				<br/>
				<?= Loc::getMessage('SKETCH_HELP_LEGEND_ROTATE') ?>
			</div>
			<div class="legend">
				<img src="/local/templates/.default/build/images/help/button-1.jpg" width="50" />
				<br/>
				<?= Loc::getMessage('SKETCH_HELP_LEGEND_BUTTON_1') ?>
			</div>
			<div class="legend">
				<img src="/local/templates/.default/build/images/help/button-2.jpg" width="50" />
				<br/>
				<?= Loc::getMessage('SKETCH_HELP_LEGEND_BUTTON_2') ?>
			</div>
			<div class="legend">
				<img src="/local/templates/.default/build/images/help/button-3.jpg" width="50" />
				<br/>
				<?= Loc::getMessage('SKETCH_HELP_LEGEND_BUTTON_3') ?>
			</div>
			<div class="legend">
				<img src="/local/templates/.default/build/images/help/button-4.jpg" width="50" />
				<br/>
				<?= Loc::getMessage('SKETCH_HELP_LEGEND_BUTTON_4') ?>
			</div>
			<div class="legend">
				<img src="/local/templates/.default/build/images/help/button-5.jpg" width="50" />
				<br/>
				<?= Loc::getMessage('SKETCH_HELP_LEGEND_BUTTON_5') ?>
			</div>
			<div class="legend">
				<img src="/local/templates/.default/build/images/help/button-6.jpg" width="50" />
				<br/>
				<?= Loc::getMessage('SKETCH_HELP_LEGEND_BUTTON_6') ?>
			</div>
		</div>
	</div>
</div>

<?	// Определение количества оборудования на скетче.
	$count = 0;
	foreach ($arResult['OBJECTS'] as $object) {
		$count += $object['quantity'];
	}
?>

<script>
	// Объект скетча.
	var $sketch = ru.octasoft.oem.designer.Main;

    $(document).ready(function() {
		
		function SaveSketchRemote()
		{
			var scene = $sketch.getScene();
            var image = $sketch.saveJPG();
			
			var result = $.ajax({
				url: '/remote/',
				type: 'post',
				data: {
					'action': 'update-basket-sketch',
					'code': '<?= $arResult['EVENT']->getCode() ?>',
					'SKETCH_SCENE': JSON.stringify(scene),
					'SKETCH_IMAGE': image,
					'COMMENTS': $('#js-order-comments-id').val(),
				},
				dataType: 'json',
				async: false,
				cache: false
			});
			
			return result;
		}
		
		
		// Переход на страницу заказа.
		$(document).on('click', '.js-step', function(e) {
			e.preventDefault();
			
			var $that = $(this);
			
			if ($that.hasClass('js-step-order')) {
				var scene = $sketch.getScene();
				if (!$sketch.validate()) {
					ShowError('<?= Loc::getMessage('ERROR') ?>', '<?= Loc::getMessage('ERROR_SKETCH_REQUIRED') ?>');
					return false;
				} else {
					$('#js-sketch-save-id').trigger('click');
				}
			}
			
			$that.prop('disabled', 'disabled');
			
			$.when(SaveSketchRemote()).done(function() {
                $that.prop('disabled', false);
			});
			
			location = $that.prop('href');
		});
		
		
		// Сохранение данных и переход на страницу заказа.
        $(document).on('click', '#js-sketch-save-id', function(e) {
            e.preventDefault();
            
            var $that = $(this);
            var $form = $that.closest('.js-form');
            
            var scene = $sketch.getScene();
            var image = $sketch.saveJPG();
			
            if (!$sketch.validate()) {
                ShowError('<?= Loc::getMessage('ERROR') ?>', '<?= Loc::getMessage('ERROR_SKETCH_REQUIRED') ?>');
				return false;
            }
            
            var savesketch = function() {
				$form.find('#js-sketch-scene-id').val(JSON.stringify(scene));
                $form.find('#js-sketch-image-id').val(image);
			}
			
			$.when(savesketch()).done(function() {
                $form.submit();
			});
        });
        
		
        // Запрос рендеров схемы стенда.
        $(document).on('click', '#js-render-id', function(e) {
            e.preventDefault();
			
			var $that = $(this);
			
			$.when(SaveSketchRemote()).done(function() {
				var objs = $sketch.getScene();
				var data = {
					'action': 'render', 
					'code': '<?= $arResult['EVENT']->getCode() ?>',
					'objs': JSON.stringify(objs)
				};
                
                $.ajax({
                    url: '/remote/',
                    type: 'post',
                    data: data,
                    dataType: 'json',
                    async: true,
                    cache: false,
					beforeSend: function() {
						$('#js-render-image-id').html('').addClass('pre-loader');
						$that.prop('disabled', 'disabled');
					},
                    success: function(response) {
                        if (response.status) {
                            $('#js-render-image-id').removeClass('pre-loader').html('<a href="' + response.data['file'] + '" target="_blank"><img src="' + response.data['path'] + '" width="60" height="60" /></a>');
                        } else {
                            // Ошибка загрузки файла.
                        }
						$that.prop('disabled', false);
                    },
                });
			});
        });
    });
	
	
	
    /*
     * Обработчики скетча.
     */
    var sketchitems = <?= json_encode(array_values($arResult['OBJECTS'])) ?>;
    var sketchgoods = <?= json_encode($arResult['OBJECTS']) ?>;
	
    var loadsketch = function() {
		
        var gridX = parseFloat(<?= (float) ($arResult['WIDTH']) ?: 5 ?>);
        var gridY = parseFloat(<?= (float) ($arResult['DEPTH']) ?: 5 ?>);

        (window.resizeEditor = function(items) {
            var height = Math.max(60 + (items.length * 135), $(window).height());
			
            $('#designer').height(height);
			
            window.editorScrollTop    = $('#designer').offset().top - 30;
            window.editorScrollBottom = window.editorScrollTop - 30 + height - $(window).height();
            if (window.editorScrollBottom < window.editorScrollTop) {
                window.editorScrollTop = window.editorScrollBottom;
            }
        })(sketchitems);

        window.onEditorReady = function() {
            $(window).on("scroll", function(e) {
                $sketch.scroll(window.editorScrollTop, window.editorScrollBottom, $(this).scrollTop());
            });
			
			// Инициализация скетча.
            $sketch.init({
                w: gridX,
                h: gridY,
                type: '<?= $arResult['SFORM'] ?>',
                items: sketchitems,
                placedItems: <?= (!empty($arResult['PLACED'])) ? (json_encode($arResult['PLACED'])) : ('{}') ?>,
				language: {
					rightColumnLabel: '<?= Loc::getMessage('SKETCH_LANG_HEADER') ?>',
					ordered: '<?= Loc::getMessage('SKETCH_LANG_ORDERED') ?>',
					placed: '<?= Loc::getMessage('SKETCH_LANG_PLACED') ?>',
					shelfPopupLabel: '<?= Loc::getMessage('SKETCH_LANG_LABEL') ?>'
				},
				useQuantity: true,
				invalidColor: 0xAA1111,
				onCartAdd: function (self) {
					var quantity = parseInt(self.qty);
					
					$.ajax({
						url: '/remote/',
						type: 'post',
						data: {
							'action':   'update-basket-quantity',
							'sessid':   BX.bitrix_sessid(),
							'bid':      self.figure.id,
							'eid':      <?= $arResult['EVENT']->getID() ?>,
							'code':     '<?= $arResult['EVENT']->getCode() ?>',
							'type':     '<?= $arResult['CONTEXT']->getType() ?>',
							'quantity': (quantity + 1)
						},
						dataType: 'json',
						beforeSend: function() {
							blockremote = true;
						},
						success: function(response) {
							if (response.status) {
								self.plus();
							}
						}
					});
				},
				onCartRemove: function(bid) {
					var quantity = parseInt(self.qty);
					
					$.ajax({
						url: '/remote/',
						type: 'post',
						data: {
							'action':   'update-basket-quantity',
							'sessid':   BX.bitrix_sessid(),
							'bid':      self.figure.id,
							'eid':      <?= $arResult['EVENT']->getID() ?>,
							'code':     '<?= $arResult['EVENT']->getCode() ?>',
							'type':     '<?= $arResult['CONTEXT']->getType() ?>',
							'quantity': (quantity - 1)
						},
						dataType: 'json',
						beforeSend: function() {
							blockremote = true;
						},
						success: function(response) {
							if (response.status) {
								self.minus();
							}
						}
					});
				}
            });
        };
        lime.embed('designer', 0, 0, '', '/');

        setTimeout(function() { window.resizeEditor(sketchitems); }, 300);
    }
	
    loadsketch();
</script>
