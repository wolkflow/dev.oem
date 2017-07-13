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

<script>
    /*
     * Обработчики скетча.
     */
    var sketchitems = <?= json_encode(array_values($arResult['OBJECTS'])) ?>;

    var loadsketch = function() {

        var gridX = parseInt(<?= (int) ($arParams['WIDTH'])  ?: 5 ?>);
        var gridY = parseInt(<?= (int) ($arParams['HEIGHT']) ?: 5 ?>);

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
                type: '<?= $arParams['SFORM'] ?>',
                items: sketchitems,
                placedItems: <?= (!empty($arResult['PLACED'])) ? (json_encode($arResult['PLACED'])) : ('{}') ?>
            });
        };
        lime.embed('designer', 0, 0, '', '/');

        setTimeout(function() { window.resizeEditor(sketchitems); }, 300);
    }

    loadsketch();
</script>