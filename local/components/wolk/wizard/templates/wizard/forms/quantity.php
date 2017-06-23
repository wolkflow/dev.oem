<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<? use Bitrix\Main\Localization\Loc; ?>
<? use Wolk\Core\Helpers\Text as TextHelper ?>

<div class="js-product-block js-product-block-<?= $section->getID() ?>" data-bid="<?= (!empty($basketitem)) ? ($basketitem->getID()) : ('') ?>">

    <? if (count($products) == 1) { ?>

        <? $product = reset($products) ?>

        <div class="equipmentcontainer__itemcontainer">
            <div class="equipmentcontainer__itemrightside">
                <div class="equipmentcontainer__itemprice">
                    <?= FormatCurrency($product->getPrice(), $arResult['CURRENCY']) ?>
                </div>
                <div class="itemquantitycontainer">
                    <div class="js-quantity-wrapper itemCount" data-pid="<?= $product->getID() ?>">
                        <div class="serviceItem__subtitle">
                            <?= Loc::getMessage('QUANTITY') ?>
                        </div>
                        <div class="js-quantity-dec itemCount__button itemCount__down"></div>
                        <div class="js-quantity-inc itemCount__button itemCount__up"></div>

                        <input id="<?= $product->getID() ?>" type="text" class="js-quantity itemCount__input styler" value="<?= (!empty($basketitem)) ? ($basketitem->getQuantity()) : ('0') ?>" />
                    </div>

                    <? // продукция, включенная в стенд. // ?>
                    <? if (array_key_exists($product->getID(), $arResult['BASE'])) { ?>
                        <div class="equipmentcontainer__standartnote">
                            <?= Loc::getMessage('STANDARD_INCLUDES') ?>
                            <b><?= $arResult['BASE'][$product->getID()] ?></b>
                        </div>
                    <? } ?>

                    <? // Обработка свойств товара. // ?>
                    <? foreach ($properties as $property) { ?>
                        <? if ($property == 'COLOR') { ?>
                            <? include ($_SERVER['DOCUMENT_ROOT'] . $this->getFolder() . '/props/color.php') ?>
                        <? } ?>
                    <? } ?>
                </div>
            </div>
            <div class="equipmentcontainer__itemleftside">
                <div class="equipmentcontainer__itemphotocontainer">
                    <a class="photoZoom" href="<?= $product->getImageSrc() ?>"></a>
                    <img src="/i?src=<?= $product->getImageSrc() ?>&h=210" class="equipmentcontainer__itemphoto" />
                </div>
                <div class="equipmentcontainer__itemsize">
                    <?= $product->getDescription() ?>
                </div>
            </div>
        </div>

    <? } else { ?>

        <div class="serviceItem__block">
            <div class="serviceItem__row">
                <div class="serviceItem__left">
                    <div class="serviceItem__subtitle">
                        <?= $section->getListTitle() ?>
                    </div>
                    <select class="js-product styler">
                        <option value="">
                            <?= Loc::getMessage('NOT_SELECTED') ?>
                        </option>
                        <? foreach ($products as $product) { ?>
                            <option value="<?= $product->getID() ?>" data-price="<?= FormatCurrency($product->getPrice(), $arResult['CURRENCY']) ?>">
                                <?= $product->getTitle() ?>
                                <?= FormatCurrency($product->getPrice(), $arResult['CURRENCY']) ?>
                            </option>
                        <? } ?>
                    </select>
                </div>
                <div class="serviceItem__right">
                    <div class="js-quantity-wrapper itemCount" data-pid="<?= $product->getID() ?>">
                        <div class="serviceItem__subtitle">
                            <?= Loc::getMessage('QUANTITY') ?>
                        </div>
                        <div class="js-quantity-dec itemCount__button itemCount__down"></div>
                        <div class="js-quantity-inc itemCount__button itemCount__up"></div>

                        <input id="<?= $product->getID() ?>" type="text" class="js-quantity itemCount__input styler" value="<?= (!empty($basketitem)) ? ($basketitem->getQuantity()) : ('0') ?>" />
                    </div>
                </div>
                <div class="js-product-select-price" style="margin-top: 10px; display: none;">
                    <div class="serviceItem__cost">
                        <div class="serviceItem__subtitle">
                            <?= Loc::getMessage('PRICE') ?>
                        </div>
                        <div class="js-product-price serviceItem__cost-value"></div>
                    </div>
                </div>
            </div>
        </div>

    <? } ?>

</div>
