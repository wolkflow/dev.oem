<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<? $this->setFrameMode(true); ?>

<? use Bitrix\Main\Localization\Loc; ?>
<? use Wolk\Core\Helpers\Text as TextHelper ?>

<div class="pagetitle">
    <?= Loc::getMessage('TITLE_SKETCH') ?>
    <div class="active pagetitle__button customizable" data-modal="#js-sketch-help-id">
        <?= Loc::getMessage('SKETCH_HELP') ?>
    </div>
</div>
<div class="pagedescription">
    <? Helper::includeFile('sketch_desc_' . \Bitrix\Main\Context::getCurrent()->getLanguage()); ?>
</div>

<div
    id="designer"
    style="margin-top: 40px; width: 940px; height: 680px;"
    onmouseout="javascript: ru.octasoft.oem.designer.Main.stopDragging();"
></div>


<? if (!empty($arResult['FASCIA'])) { ?>
	<div class="fasciacontainer">
		<? foreach ($arResult['FASCIA'] as $basketitem) { ?>
			<? $product = $basketitem->getElement() ?>
			<div class="js-pricetype-symbols">
				<div class="js-product-block js-product-block-<?= $product->getSectionID() ?>" data-bid="<?= $basketitem->getID() ?>" data-price-type="symbols">
					<div class="equipmentcontainer__itemcontainer">
						<div class="js-symbols-wrapper js-product-element itemCount" data-pid="<?= $basketitem->getProductID() ?>" data-limit="<?= $arResult['EVENT']->getPayLimitSymbols() ?>">
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
										<input class="js-param-required js-param-value js-param-x-value js-text styler" name="TEXT" type="text" value="<?= $basketitem->getParam('TEXT') ?>">
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

<form class="js-form" method="post" action="<?= $arResult['LINKS']['NEXT'] ?>" enctype="multipart/form-data">
    <input id="js-sketch-scene-id" type="hidden" name="SKETCH_SCENE" />
    <input id="js-sketch-image-id" type="hidden" name="SKETCH_IMAGE" />
    
	<hr/>
	
    <div class="renders">
        <div id="js-renders-images-id" class="render-images"></div>
        <br/>
        <button id="js-render-id" data-code="<?= $arResult['EVENT']->getCode() ?>" class="button styler customizable">
            <?= Loc::getMessage('RENDER') ?>
        </button>
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
                    <?= $arParams['WIDTH'] ?> &times; <?= $arParams['DEPTH'] ?>
                </div>
                <div class="reviewconfigurationcontainer__configuration">
                    <span class="reviewconfigurationcontainer__configurationtitle">
                        <?= Loc::getMessage('TYPE') ?>:
                    </span>
                    <?= Loc::getMessage('TYPE_' . strtoupper($arParams['TYPE'])) ?>
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
		<div class="modalBody">
			<?= Loc::getMessage('SKETCH_HELP_TEXT') ?>
		</div>
	</div>
</div>

<script>
    $(document).ready(function() {
		
		// Переход на страницу заказа.
		$(document).on('click', '.js-step-order', function(e) {
			var scene = ru.octasoft.oem.designer.Main.getScene();
            var image = ru.octasoft.oem.designer.Main.saveJPG();

			if (scene.objects.length < <?= count($arResult['OBJECTS']) ?>) {
				e.preventDefault();
                ShowError('<?= Loc::getMessage('ERROR') ?>', '<?= Loc::getMessage('ERROR_SKETCH_REQUIRED') ?>');
				return false;
            }
		});
		
		// Сохранение данных и переход на страницу заказа.
        $(document).on('click', '#js-sketch-save-id', function(e) {
            e.preventDefault();
            
            var $that = $(this);
            var $form = $that.closest('.js-form');
            
            var scene = ru.octasoft.oem.designer.Main.getScene();
            var image = ru.octasoft.oem.designer.Main.saveJPG();
            
            if (scene.objects.length < <?= count($arResult['OBJECTS']) ?>) {
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

            var objs = ru.octasoft.oem.designer.Main.getScene();
            var code = $(this).data('code');
            
            $('#js-renders-images-id').html('');
            
            for (var i = 1; i <= 4; i++) {
                var data = {'action': 'render', 'code': code, 'view': i, 'objs': JSON.stringify(objs)};
                
                $.ajax({
                    url: '/remote/',
                    type: 'post',
                    data: data,
                    dataType: 'json',
                    async: true,
                    cache: false,
					beforeSend: function() {
						$that.addClass('button-loader');
						$that.prop('disabled', 'disabled');
					},
                    success: function(response) {
                        if (response.status) {
                            $('#js-renders-images-id').append('<div class="render-image"><a href="' + response.data['path'] + '" target="_blank"><img src="' + response.data['path'] + '" width="100" height="100" /></a></div>');
                        } else {
                            // Ошибка загрузки файла.
                        }
						$that.removeClass('button-loader');
						$that.prop('disabled', false);
                    },
                });
            }
        });
    });

    /*
     * Обработчики скетча.
     */
    var sketchitems = <?= json_encode(array_values($arResult['OBJECTS'])) ?>;
    
    var loadsketch = function() {

        var gridX = parseFloat(<?= (float) ($arResult['WIDTH']) ?: 5 ?>);
        var gridY = parseFloat(<?= (float) ($arResult['DEPTH']) ?: 5 ?>);

        (window.resizeEditor = function(items) {
            var height =  Math.max(120 + (items.length * 135), $(window).height());
			
            $('#designer').height(height);

            window.editorScrollTop = $('#designer').offset().top - 30;
            window.editorScrollBottom = window.editorScrollTop - 30 + height - $(window).height();
            if (window.editorScrollBottom < window.editorScrollTop) {
                window.editorScrollTop = window.editorScrollBottom;
            }
        })(sketchitems);

        window.onEditorReady = function() {
            $(window).on("scroll", function(e) {
                ru.octasoft.oem.designer.Main.scroll(window.editorScrollTop, window.editorScrollBottom, $(this).scrollTop());
            });

            ru.octasoft.oem.designer.Main.init({
                w: gridX,
                h: gridY,
                type: '<?= $arResult['SFORM'] ?>',
                items: sketchitems,
                placedItems: <?= (!empty($arResult['PLACED'])) ? (json_encode($arResult['PLACED'])) : ('{}') ?>
            });
        };
        lime.embed('designer', 0, 0, '', '/');

        setTimeout(function() { window.resizeEditor(sketchitems); }, 300);
    }

    loadsketch();
</script>
