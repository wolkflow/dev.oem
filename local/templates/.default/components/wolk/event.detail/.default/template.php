<?
use Bitrix\Main\Page\Asset;
use Bitrix\Main\Page\AssetLocation;
use Bitrix\Main\Web\Json;
use Wolk\Core\Helpers\ArrayHelper;

//$items = Json::encode(array_map(function($val) {
//	return ArrayHelper::only($val, ['ID', 'NAME', 'IBLOCK_ID', 'CODE', 'PRICES']);
//}, $arResult['ITEMS']));
//
//Asset::getInstance()->addString(<<<JS
//	<script>
//		var stands = $items;
//	</script>
//JS
//	, true, AssetLocation::AFTER_JS);

#echo "<pre>"; print_r($arResult); die;
$curLang = strtoupper(\Bitrix\Main\Context::getCurrent()->getLanguage());
?>
<div class="standspagetop">
	<? if($preselect = $arResult['EVENT']['PROPS']['PRESELECT']['VALUE']): ?>
		<div class="pagedescription">
			You have a pre-paid stand which you can continue with by clicking “Continue” or choose
			a different one from the list below.
		</div>
		<div class="pagetitle">Your current stand type</div>
		<div class="standspagetop__currentstandcontainer">
			<div class="standspagetop__currentstanddescription">
				<?= $arResult['ITEMS'][$preselect]['DESCRIPTION'] ?>
				<ul>Including:
					<? foreach($arResult['ITEMS'][$preselect]['OFFER']['EQUIPMENT'] as $eq): ?>
						<li><? if($eq['COUNT'] > 1): ?><?= $eq['COUNT'] ?> x <? endif; ?><?= $eq['NAME'] ?></li>
					<? endforeach; ?>
				</ul>
			</div>
			<img src="<?= $arResult['ITEMS'][$preselect]['PREVIEW_PICTURE'] ?>" class="standspagetop__photo" alt="">
			<a href="" class="standspagetop__continuebutton">continue</a>
		</div>
	<? endif; ?>
</div>

<div class="standstypescontainer">
	<div class="pagetitle">Another system stand types</div>
	<div class="standstypescontainer__standscontainer">
		<? foreach($arResult['ITEMS'] as $stand): ?>
			<div class="standstypescontainer__standcontainer">
				<div class="pagesubtitle"><?= $stand['NAME'] ?></div>
				<div class="standstypescontainer__pricecontiner"><?= FormatCurrency(
						$stand['OFFER']['RESULT_PRICE']['PRICE'],
						$stand['OFFER']['RESULT_PRICE']['CURRENCY']
					) ?>
					<span><?= FormatCurrency(
							$stand['OFFER']['PRICE']['PRICE'],
							$stand['OFFER']['PRICE']['CURRENCY']
						) ?>/m2</span>

					<div class="standstypescontainer__choosebutton">choose</div>
				</div>
				<img height="138" src="<?= $stand['PREVIEW_PICTURE'] ?>" class="standstypescontainer__photo" alt="">

				<div class="standstypescontainer__description">
					<?= $stand['PROPS']["LANG_DESCRIPTION_{$curLang}"] ?>
					<ul>Including:
						<? foreach($stand['OFFER']['EQUIPMENT'] as $eq): ?>
							<li><? if($eq['COUNT'] > 1): ?><?= $eq['COUNT'] ?> x <? endif; ?><?= $eq['NAME'] ?></li>
						<? endforeach; ?>
					</ul>
				</div>
			</div>
		<? endforeach; ?>
	</div>
</div>