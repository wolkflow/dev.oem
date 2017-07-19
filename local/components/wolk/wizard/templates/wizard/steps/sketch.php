<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<? $this->setFrameMode(true); ?>

<? use Bitrix\Main\Localization\Loc; ?>
<? use Wolk\Core\Helpers\Text as TextHelper ?>

<div class="pagetitle">
    <?= Loc::getMessage('TITLE_SKETCH') ?>
    <div class="active pagetitle__button customizable">
        <?= Loc::getMessage('SKETCH_HELP') ?>
    </div>
</div>
<div class="pagedescription">
    <? // Helper::includeFile('sketch_desc_'.\Bitrix\Main\Context::getCurrent()->getLanguage()); ?>
</div>

<div
    id="designer"
    style="margin-top: 40px; width: 940px; height: 680px;"
    onmouseout="javascript: ru.octasoft.oem.designer.Main.stopDragging();"
></div>


<div class="sketchNav">
    <a href="<?= $arResult['LINKS']['PREV'] ?>" class="button styler prev">
        <?= Loc::getMessage('PREV') ?>
    </a>
    <a id="js-sketch-save-id" href="<?= $arResult['LINKS']['NEXT'] ?>" class="button styler prev">
        <?= Loc::getMessage('NEXT') ?>
    </a>
</div>

<div class="renders">
    <div id="js-renders-images-id"></div>
    <button id="js-render-id" data-code="<?= $arResult['EVENT']->getCode() ?>">
        Рендер
    </button>
</div>

<div class="sketchAfter">
    <div class="sketchAfterLeft">
        <div class="commentsForm">
            <div class="commentsForm__title">
                <?= Loc::getMessage('COMMENTS') ?>
            </div>
            <textarea placeholder="<?= Loc::getMessage('ADDITIONAL_INFO') ?>"><?= strip_tags($arResult['ORDER']['ORDER_DATA']['USER_DESCRIPTION']) ?></textarea>
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



<script>
    $(document).ready(function() {
        $(document).on('click', '#js-sketch-save-id', function(e) {
            e.preventDefault();
            
            var scene = ru.octasoft.oem.designer.Main.getScene();
            var image = ru.octasoft.oem.designer.Main.saveJPG();
            
            if (scene.objects.length < <?= count($arResult['OBJECTS']) ?>) {
                showError('Ошибка', 'Не все объекты размещены на схеме');
            }
            
            // JSON.stringify(
        });
        
        
        $(document).on('click', '#js-render-id', function(e) {
            var objs = ru.octasoft.oem.designer.Main.getScene();
            var code = $(this).data('code');
            var data = {'action': 'render', 'code': code, 'view': 1, 'objs': JSON.stringify(objs)};
            
            $.ajax({
                url: '/remote/',
                type: 'post',
                data: data,
                dataType: 'json',
                async: true,
                cache: false,
                success: function(response) {
                    if (response.status) {
                        $('#js-renders-images-id').html('<a target="_blank" href="' + response.data['path'] + '"><img src="' + response.data['path'] + '" width="100" height="100" /></a>');
                    } else {
                        // Ошибка загрузки файла.
                    }
                },
            });
        });
    });

    /*
     * Обработчики скетча.
     */
    var sketchitems = <?= json_encode(array_values($arResult['OBJECTS'])) ?>;
    
    var loadsketch = function() {

        var gridX = parseInt(<?= (int) ($arResult['WIDTH']) ?: 5 ?>);
        var gridY = parseInt(<?= (int) ($arResult['DEPTH']) ?: 5 ?>);

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