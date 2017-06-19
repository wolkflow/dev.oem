<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<? use Bitrix\Main\Localization\Loc; ?>
<? use Wolk\Core\Helpers\Text as TextHelper ?>


<? // Подукция, находящаяся в корзине. // ?>
<? if (!empty($basketgroup)) { ?>
    
    <? // В случае, когда- свойства продукции не установлены в списке отображается лишь один вариант продукции. // ?>
    <? if (empty($properties)) { ?>
        <? $basketitem = reset($basketgroup) ?>
        
        <div class="js-product-section js-pricetype-<?= strtolower($pricetype) ?>" data-bid="<?= $basketitem->getID() ?>" data-sid="<?= $section->getID() ?>" data-pricetype="<?= $pricetype ?>">
            <div class="serviceItem__title">
                <?= $section->getTitle() ?>
            </div>
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
                                
                                <input id="<?= $product->getID() ?>" type="text" class="js-quantity itemCount__input styler" value="<?= $basketitem->getQuantity() ?>" />
                            </div>
                            <? if (array_key_exists($product->getID(), $arResult['BASE'])) { ?>
                                <div class="equipmentcontainer__standartnote">
                                    <?= Loc::getMessage('STANDARD_INCLUDES') ?>
                                    <b><?= $arResult['BASE'][$product->getID()] ?></b>
                                </div>
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

               ...
               
            <? } ?>
        </div>
    <? } ?>
    
    
    <? // В случае, когда- свойства продукции установлены в списке могут отображаться несколкьо вариантов продукции. // ?>
    <? if (!empty($properties)) { ?>
        <? foreach ($basketgroup as $basketitem) { ?>
            <div class="js-product-section js-pricetype-<?= strtolower($pricetype) ?>" data-bid="<?= $basketitem->getID() ?>" data-sid="<?= $section->getID() ?>" data-pricetype="<?= $pricetype ?>">
                <div class="serviceItem__title">
                    <?= $section->getTitle() ?>
                </div>
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
                                    
                                    <input id="<?= $product->getID() ?>" type="text" class="js-quantity itemCount__input styler" value="<?= $basketitem->getQuantity() ?>" />
                                </div>
                                <? if (array_key_exists($product->getID(), $arResult['BASE'])) { ?>
                                    <div class="equipmentcontainer__standartnote">
                                        <?= Loc::getMessage('STANDARD_INCLUDES') ?>
                                        <b><?= $arResult['BASE'][$product->getID()] ?></b>
                                    </div>
                                <? } ?>
                                
                                <? // Обработка свойств товара // ?>
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

                   ...
                   
                <? } ?>
            </div>
        <? } ?>
    <? } ?>
    
<? } else { ?>
    
    <div class="js-product-section js-pricetype-<?= strtolower($pricetype) ?>" data-bid="" data-sid="<?= $section->getID() ?>" data-pricetype="<?= $pricetype ?>">
        <div class="serviceItem__title">
            <?= $section->getTitle() ?>
        </div>
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
                            
                            <input id="<?= $product->getID() ?>" type="text" class="js-quantity itemCount__input styler" value="0" />
                        </div>
                        <? if (array_key_exists($product->getID(), $arResult['BASE'])) { ?>
                            <div class="equipmentcontainer__standartnote">
                                <?= Loc::getMessage('STANDARD_INCLUDES') ?>
                                <b><?= $arResult['BASE'][$product->getID()] ?></b>
                            </div>
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

           ...
           
        <? } ?>
    </div>
<? } ?>

