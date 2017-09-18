<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>

<? use Bitrix\Main\Localization\Loc; ?>

<pre>
	<? // print_r($arResult) ?>
</pre>

<div class="ordercontainer">
	<div class="ordercontainer__columnscontainer">
		
		<div class="ordercontainer__column right">
			<? if (!empty($arResult['ITEMS'][Wolk\OEM\Products\Section::TYPE_EQUIPMENTS])) { ?>
				<div class="pagesubtitle">
					<?= Loc::getMessage('EQUIPMENT') ?>
					<? if ($arResult['ORDER']['STATUS_ID'] == 'N') { ?>
						<div class="pagesubtitle__addbutton">
							<a href="<?= $arResult['ORDER']['LINK'] ?>"></a>
						</div>
					<? } ?>
				</div>
				<? foreach ($arResult['ITEMS'][Wolk\OEM\Products\Section::TYPE_EQUIPMENTS] as $section) { ?>
					<div class="ordercontainer__itemscontainer">
						<div class="pagesubsubtitle">
							<?= $section['SELF']->getTitle() ?>
						</div>
						<? foreach ($section['ITEMS'] as $item) { ?>
							<div class="ordercontainer__item" v-for="item in items">
								<div class="ordercontainer__itemtotalprice">
									<?= FormatCurrency($item['BASKET']['PRICE'] * $item['BASKET']['QUANTITY'], $arResult['ORDER']['CURRENCY']) ?>
								</div>
								<div class="ordercontainer__itemname">
									<?= $item['SELF']->getTitle() ?> | <?= FormatCurrency($item['BASKET']['PRICE'], $arResult['ORDER']['CURRENCY']) ?>
									&times;
									<?= $item['BASKET']['QUANTITY'] ?>
								</div>
								<? if ($arResult['ORDER']['STATUS_ID'] == 'N') { ?>
									<div class="ordercontainer__changebutton">
										<a href="<?= $arResult['ORDER']['LINK'] ?>"><?= Loc::getMessage('change') ?></a>
									</div>
								<? } ?>
							</div>
						<? } ?>
					</div>
				<? } ?>
			<? } ?>
		</div>
	
		<? /*
		<div class="ordercontainer__column right">
			<div class="pagesubtitle">
				<?= Loc::getMessage('additional equipment') ?>
				<? if ($arResult['ORDER']['STATUS_ID'] == 'N') { ?>
					<div class="pagesubtitle__addbutton">
						<a href="<?= $arResult['ORDER']['LINK'] ?>"></a>
					</div>
				<? } ?>
			</div>
			<? foreach ($arResult['BASKETS'] as $basket) { ?>
				<? if ($basket['PROPS']['INCLUDED']['VALUE'] == 'Y' || $basket['PROPS']['STAND']['VALUE'] == 'Y') { continue; } ?>
				<? $product = new Wolk\OEM\Products\Base($basket['PRODUCT_ID']) ?>
				<div class="ordercontainer__itemscontainer">
					<div v-if="!$.isEmptyObject(items)" class="pagesubsubtitle">
						{{ options.SECTIONS[sectionId].NAME }}
					</div>
					<div class="ordercontainer__item" v-for="item in items">
						<div class="ordercontainer__itemtotalprice">
							<?= FormatCurrency($basket['PRICE'] * $basket['QUANTITY'], $arResult['ORDER']['CURRENCY']) ?>
						</div>
						<div class="ordercontainer__itemname">
							<?= $product->getTitle() ?> | <?= FormatCurrency($basket['PRICE'], $arResult['ORDER']['CURRENCY']) ?>
							&times;
							<?= $basket['QUANTITY'] ?>
						</div>
						<? if ($arResult['ORDER']['STATUS_ID'] == 'N') { ?>
							<div class="ordercontainer__changebutton">
								<a href="<?= $arResult['ORDER']['LINK'] ?>">
									<?= Loc::getMessage('CHANGE') ?>
								</a>
							</div>
						<? } ?>
					</div>
				</div>
			<? } ?>
		</div>
		*/ ?>
		
		<div class="ordercontainer__column">
			<div class="pagesubtitle">
				<?= Loc::getMessage('stand type') ?>
			</div>
			<div class="ordercontainer__itemscontainer">
				<div class="pagesubsubtitle">
					<?= Loc::getMessage('system stand') ?>
				</div>
				<div class="last ordercontainer__item">
					<div class="ordercontainer__itemtotalprice">
						<?= FormatCurrency($arResult['STAND']['BASKET']['PRICE'] * $arResult['STAND']['BASKET']['QUANTITY'], $arResult['ORDER']['CURRENCY']) ?>
					</div>
					<div class="ordercontainer__itemname">
						<?= $arResult['STAND']['ITEM']->getTitle() ?>
						<?= $arResult['STAND']['WIDTH'] ?> &times; <?= $arResult['STAND']['DEPTH'] ?>
					</div>
					<? if ($arResult['ORDER']['STATUS_ID'] == 'N') { ?>
						<div class="ordercontainer__changebutton">
							<a href="<?= $arResult['ORDER']['LINK'] ?>">
								<?= Loc::getMessage('CHANGE') ?>
							</a>
						</div>
					<? } ?>
				</div>
			</div>
			
			<? if (!empty($arResult['ITEMS'][Wolk\OEM\Products\Section::TYPE_SERVICES])) { ?>
				<div class="pagesubtitle">
					<?= Loc::getMessage('services') ?>
					<? if ($arResult['ORDER']['STATUS_ID'] == 'N') { ?>
						<div class="pagesubtitle__addbutton">
							<a href="<?= $arResult['ORDER']['LINK'] ?>"></a>
						</div>
					<? } ?>
				</div>
				<? foreach ($arResult['ITEMS'][Wolk\OEM\Products\Section::TYPE_SERVICES] as $section) { ?>
					<div class="ordercontainer__itemscontainer">
						<div class="pagesubsubtitle">
							<?= $section['SELF']->getTitle() ?>
						</div>
						<? foreach ($section['ITEMS'] as $item) { ?>
							<div class="ordercontainer__item" v-for="item in items">
								<div class="ordercontainer__itemtotalprice">
									<?= FormatCurrency($item['BASKET']['PRICE'] * $item['BASKET']['QUANTITY'], $arResult['ORDER']['CURRENCY']) ?>
								</div>
								<div class="ordercontainer__itemname">
									<?= $item['SELF']->getTitle() ?> | <?= FormatCurrency($item['BASKET']['PRICE'], $arResult['ORDER']['CURRENCY']) ?>
									&times;
									<?= $item['BASKET']['QUANTITY'] ?>
								</div>
								<? if ($arResult['ORDER']['STATUS_ID'] == 'N') { ?>
									<div class="ordercontainer__changebutton">
										<a href="<?= $arResult['ORDER']['LINK'] ?>"><?= Loc::getMessage('change') ?></a>
									</div>
								<? } ?>
							</div>
						<? } ?>
					</div>
				<? } ?>
			<? } ?>
		</div>
	</div>
	<? if ($arResult['ORDER']['STATUS_ID'] == 'N') { ?>
		<div class="ordercontainer__changebutton changeallorder">
			<a class="changebutton" href="<?= $arResult['ORDER']['LINK'] ?>">
				<?= Loc::getMessage('change_order') ?>
			</a>
		</div>
	<? } ?>
	<div class="ordertotalcontainer">
	<div class="ordertotalcontainer__standandpavillion">
		<div class="ordertotalcontainer__standcontainer">
			<div class="ordertotalcontainer__title">
				<?= ucfirst(Loc::getMessage('STAND')) ?> №
			</div>
			<div class="ordertotalcontainer__number">
				<input disabled type="text" value="<?= $arResult['ORDER']['PROPS']['STANDNUM']['VALUE'] ?>" />
			</div>
		</div>
		<div class="ordertotalcontainer__pavillioncontainer">
			<div class="ordertotalcontainer__title">
				<?= ucfirst(Loc::getMessage('PAVILION')) ?>
			</div>
			<div class="ordertotalcontainer__number">
				<input disabled type="text" value="<?= $arResult['ORDER']['PROPS']['PAVILION']['VALUE'] ?>" />
			</div>
		</div>
	</div>

	<div class="ordertotalcontainer">
		<div class="ordertotalcontainer__total">
			<?= Loc::getMessage('TOTAL') ?>: 
			<span><?= number_format($arResult['PRICES']['TOTAL_WITH_SUR'], 2, '.', '') ?></span>
		</div>
		<div class="ordertaxcontainer__total">
			<?= Loc::getMessage('TAX') ?>: 
			<span><?= number_format($arResult['PRICES']['TAX'], 2, '.', '') ?></span>
		</div>
		<div class="ordertotalcontainer__total">
			<?= Loc::getMessage('TOTAL_WITH_VAT') ?>: 
			<span><?= number_format($arResult['PRICES']['TOTAL'], 2, '.', '') ?></span>
		</div>
		<div class="ordertotalcontainer__surcharge">
			<?= Loc::getMessage('SURCHARGE') ?>: 
			<span><?= number_format($arResult['PRICES']['SURCHARGE'], 2, '.', '') ?> % (<?= $arResult['ORDER']['PROPS']['SURCHARGE']['VALUE']?>)</span>
		</div>
		<div class="ordertotalcontainer__surchargetotal">
			<div class="ordertotalcontainer__surchargetotaltitle">
				<?= Loc::getMessage('TOTAL_WITH_SURCHARGE') ?>:
			</div>
			<div class="ordertotalcontainer__surchargetotalcount">
				<?= number_format($arResult['PRICES']['TOTAL'], 2, '.', '') ?>
			</div>
		</div>
	</div>
</div>

