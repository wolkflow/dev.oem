<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>

<script type="text/javascript">
    $(document).ready(function () {
	
        var sketchitems = <?= json_encode(array_values($arResult['SKETCH']['items'])) ?>;
		
        /*
         * Обработчики скетча.
         
		if (typeof window.devicePixelRatio != 'undefined' && window.devicePixelRatio > 2) {
			var meta = document.getElementById('viewport');
			meta.setAttribute('content', 'width=device-width, initial-scale=' + (2 / window.devicePixelRatio) + ', user-scalable=no');
		}
		*/
		
		var gridX   = <?= (int) ($arResult['PROPS']['width']['VALUE']) ?: 5 ?>;
		var gridY   = <?= (int) ($arResult['PROPS']['depth']['VALUE']) ?: 5 ?>;
		
		// compute initial editor's height
		(window.resizeEditor = function(items) {
			var editorH = Math.max(120 + (items.length * 135), 675);
			$('#designer').height(editorH);
		})(sketchitems);

		window.onEditorReady = function() {
			ru.octasoft.oem.designer.Main.init({
				w: gridX,
				h: gridY,
				hideControls: true,
				type: '<?= (!empty($order['PROPS']['standType']['VALUE'])) ? ($order['PROPS']['standType']['VALUE']) : ('row') ?>',
				items: sketchitems,
				placedItems: <?= (!empty($arResult['SKETCH']['objects'])) ? (json_encode($arResult['SKETCH']['objects'])) : ('{}') ?>
			});
		};
		lime.embed('designer', 0, 0, '', '/');
    });
</script>

<div id="designer" style="margin-top: 5px; width: 940px; height: 680px;" onmouseout="ru.octasoft.oem.designer.Main.stopDragging()"></div>