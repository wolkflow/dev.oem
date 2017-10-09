<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<? use Bitrix\Main\Localization\Loc; ?>
<? use Wolk\Core\Helpers\Text as TextHelper ?>
<? use Wolk\Oem\Basket; ?>

<div class="js-product-block js-product-block-<?= $section->getID() ?>" data-bid="<?= (!empty($basketitem)) ? ($basketitem->getID()) : ('') ?>" data-price-type="<?= $priceform ?>">
    
    <? // Вывод продукции карточкой или списком. // ?>
    <? if (!$section->asListShow() && count($products) == 1) { ?>
        <? // Единственный продукт. // ?>
        <? $product = reset($products) ?>
        
        <div class="equipmentcontainer__itemcontainer">
            <div class="equipmentcontainer__itemrightside">
                <div class="equipmentcontainer__itemprice">
                    <?= FormatCurrency($product->getPrice(), $arResult['CURRENCY']) ?>
                </div>
                <div class="itemquantitycontainer">
                    <? // Продукция, включенная в стенд. // ?>
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
                    <img src="/i.php?src=<?= $product->getImageSrc() ?>&h=210" class="equipmentcontainer__itemphoto" />
                </div>
                <div class="equipmentcontainer__itemsize">
                    <?= $product->getDescription() ?>
                </div>
            </div>
			
            <? // Подключение формы добавления продукции в корзину. // ?>
            <? include ($_SERVER['DOCUMENT_ROOT'] . $this->getFolder() . '/forms/'.$priceform.'.php') ?>

            <? // Обработка свойств товара. // ?>
            <? include ($_SERVER['DOCUMENT_ROOT'] . $this->getFolder() . '/props.php') ?>
        </div>
        
    <? } else { ?>
    
        <div class="serviceItem__block">
            <div class="serviceItem__row">
                <div class="serviceItem__left">
                    <div class="serviceItem__subtitle">
                        <?= $section->getListTitle() ?>
                    </div>
                    <select class="js-product-select styler">
                        <? if (count($products) > 1) { ?>
                            <option class="js-option-noselect" value="" <?= (empty($basketitem)) ? ('selected') : ('') ?>>
                                <?= Loc::getMessage('NOT_SELECTED') ?>
                            </option>
                        <? } ?>
                        <? foreach ($products as $product) { ?>
                            <option value="<?= $product->getID() ?>" <?= (!empty($basketitem) && $basketitem->getProductID() == $product->getID()) ? ('selected') : ('') ?> data-price="<?= FormatCurrency($product->getPrice(), $arResult['CURRENCY']) ?>" data-descr="<?= $product->getDescription() ?>">
                                <?= $product->getTitle() ?>
                                <?= FormatCurrency($product->getPrice(), $arResult['CURRENCY']) ?>
                            </option>
                        <? } ?>
                    </select>
                </div>
            </div>
            
            <? // Вывод цены при выбооре конкретной продукции. // ?>
            <div class="js-product-select-price" style="margin-top: 10px; display: none;">
                <div class="serviceItem__cost">
                    <div class="serviceItem__subtitle">
                        <?= Loc::getMessage('PRICE') ?>
                    </div>
                    <div class="js-product-price serviceItem__cost-value"></div>
                </div>
            </div>
            
            <? // Вывод комметария при выбооре конкретной продукции. // ?>
            <div class="js-product-select-descr" style="margin-top: 10px; display: none;">
                <div class="serviceItem__cost">
                    <div class="js-product-descr serviceItem__cost-value"></div>
                </div>
            </div>
            
            <? // Комментарий раздела. // ?>
            <div class="equipmentcontainer__itemsize">
                <?= $section->getDescription() ?>
            </div>
            
            <? // Подключение формы добавления продукции в корзину. // ?>
            <? include ($_SERVER['DOCUMENT_ROOT'] . $this->getFolder() . '/forms/'.$priceform.'.php') ?>
            
            <? // Подключение свойств товара. // ?>
            <? include ($_SERVER['DOCUMENT_ROOT'] . $this->getFolder() . '/props.php') ?>
        </div>
    
    <? } ?>
    
</div>