<?php

use Bitrix\Main\Localization\Loc;
use Bitrix\Main\Page\Asset;
use Bitrix\Main\Page\AssetLocation;
use Bitrix\Main\Web\Json;

$langs = Json::encode([
	'filePlaceholder' => Loc::getMessage('file_placeholder'),
	'fileNumber' => Loc::getMessage('file_number'),
	'selectPlaceholder' => Loc::getMessage('select_placeholder'),
	'selectSearchNotFound' => Loc::getMessage('search_not_found'),
	'selectSearchPlaceholder' => Loc::getMessage('search_placeholder'),
	'sketchtitle' => Loc::getMessage('sketchtitle'),
]);

$am = Asset::getInstance();
$am->addString(<<<JS
	<script>
		langs = $langs
	</script>
JS
, true, AssetLocation::AFTER_JS_KERNEL);

?>

<div class="profilecontainer__column left">
    <? foreach ($arResult['EVENTS'] as $n => $event) { ?>
        <? if ($n > count($arResult['EVENTS']) / 2) { ?>
            </div>
            <div class="profilecontainer__column right">
        <? } ?>
        <div class="pagesubsubtitle">
			<a href="/events/<?= $event['CODE'] ?>/"><?= $event['NAME'] ?></a>
		</div>
        <div class="profilecontainer__itemscontainer">
            <? foreach ($event['ORDERS'] as $order) { ?>
                <div class="profilecontainer__item">
                    <div class="profilecontainer__itemnumber">
						<?= $order['ORDER']['ID'] ?>
					</div>
                    <div class="profilecontainer__itemstatus">
						<?= Loc::getMessage('status') ?>: <?= $arResult['STATUSES'][$order['ORDER']['STATUS_ID']] ?: Loc::getMessage('empty_status') ?>
					</div>
                    
                    <div class="profilecontainer__changebutton">
						<? if ($order['ORDER']['PROPS']['TYPE']['VALUE'] != 'QUICK') { ?>
							<a href="javascript:void(0)" class="js-order-show" data-oid="<?= $order['ORDER']['ID'] ?>">
								<? if ($order['ORDER']['STATUS_ID'] == 'N') { ?>
									<?= Loc::getMessage('сhangeview_order') ?>
								<? } else { ?>
									<?= Loc::getMessage('view_order') ?>
								<? } ?>
							</a>
						<? } else { ?>
							<a href="javascript:void(0)" class="js-order-show" data-oid="<?= $order['ORDER']['ID'] ?>">
								<?= Loc::getMessage('view_order') ?>
							</a>
						<? } ?>
                    </div>                    
                </div>
            <? } ?>
        </div>
    <? } ?>
</div>


<div class="hide">
    <div class="modal modalFull modalOrder" id="js-order-modal-id">
        <div class="modalClose arcticmodal-close"></div>
        <div id="js-order-title-id" class="modalTitle">
			<?= Loc::getMessage('сhangeview_order') ?>
			<span class="js-order-number"></span>
		</div>
        <div id="js-order-content-id" class="modalContent">
		</div>
	</div>
</div>
