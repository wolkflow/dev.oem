<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<? use Bitrix\Main\Localization\Loc; ?>
<? use Wolk\Core\Helpers\Text as TextHelper ?>
<? use Wolk\Oem\Basket; ?>


<div class="js-product-block js-product-block-<?= $section->getID() ?>" data-bid="<?= (!empty($basketitem)) ? ($basketitem->getID()) : ('') ?>">
    <input type="hidden" name="dates" class="js-product-days-hours-dates" value="" />
    <input type="hidden" name="times" class="js-product-days-hours-times" value="" />
    
    <? if (!$section->asListShow() && count($products) == 1) { ?>
        
        <? $product = reset($products) ?>

        <div class="equipmentcontainer__itemcontainer">
            <div class="equipmentcontainer__itemrightside">
                <div class="equipmentcontainer__itemprice">
                    <?= FormatCurrency($product->getPrice(), $arResult['CURRENCY']) ?>
                </div>
                <div class="itemquantitycontainer">
                    
                    <div class="js-days-hours-wrapper js-product-select itemCount" data-pid="<?= $product->getID() ?>">
                        <div class="setDateBlock">
                            <div class="serviceItem__subtitle">
                                <?= Loc::getMessage('DATES') ?>
                            </div>
                            
                            <div class="setDate hasDatepicker">
                                <div class="loolee">
                                    <div class="looleeHead">
                                        <label class="styler">
                                            <input id="<?= $product->getID() ?>" type="checkbox" class="changeMode" value="<?= (!empty($basketitem)) ? ($basketitem->getQuantity()) : ('') ?>" />
                                            <span></span><?= Loc::getMessage('DATERANGE') ?>
                                        </label>
                                        <a href="#" class="cButton buttonClear dateClear">
                                            <?= Loc::getMessage('CLEAR') ?>
                                        </a>
                                        <a href="#" class="cButton buttonOk looleeClose">
                                            ОК
                                        </a>
                                    </div>
                                    <div class="dpBlock" data-mode="multiple"></div>
                                </div>
                            </div>
                        </div>
                    </div>

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
                    <img src="/i?src=<?= $product->getImageSrc() ?>&h=210" class="equipmentcontainer__itemphoto" />
                </div>
                <div class="equipmentcontainer__itemsize">
                    <?= $product->getDescription() ?>
                </div>
            </div>

            <? // Обработка свойств товара. // ?>
            <? include ($_SERVER['DOCUMENT_ROOT'] . $this->getFolder() . '/props.php') ?>

            <div class="equipmentcontainer__itemsize">
                <?= $section->getDescription() ?>
            </div>
        </div>

    <? } else { ?>
    
        <? // Рабочее здесь // ?>
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
                
                <div class="js-product-select-price" style="margin-top: 10px; display: none;">
                    <div class="serviceItem__cost">
                        <div class="serviceItem__subtitle">
                            <?= Loc::getMessage('PRICE') ?>
                        </div>
                        <div class="js-product-price serviceItem__cost-value"></div>
                    </div>
                </div>
                
                <div class="js-product-select-descr" style="margin-top: 10px; display: none;">
                    <div class="serviceItem__cost">
                        <div class="js-product-descr serviceItem__cost-value"></div>
                    </div>
                </div>
                
                <div class="equipmentcontainer__itemsize">
                    <?= $section->getDescription() ?>
                </div>
            </div>
            
            <div class="serviceItem__row">
                <div class="js-days-hours-wrapper itemCount" data-pid="<?= $product->getID() ?>">
                    
                    <? // Установка даты // ?>
                    <div class="serviceItem__left">
                        <div class="setDateBlock">
                            <div class="serviceItem__subtitle">
                                <?= Loc::getMessage('DATES') ?>
                            </div>
                            <input name="dates" class="setDate js-field-value js-days-hours-datepicker" value="<?= (!empty($basketitem)) ? ($basketitem->getField('dates')) : ('') ?>" />
                        </div>
                    </div>
                    
                    <? // Установка времени // ?>
                    <div class="serviceItem__right">
                        <div class="itemCount">
                            <div class="serviceItem__subtitle">
                                <?= Loc::getMessage('TIMES') ?>
                            </div>
                            <div class="setTime">
                                <select name="timemin" class="styler js-field-value js-days-hours-times js-days-hours-time-min">
                                    <? for ($time = 8; $time <= 20; $time++) { ?>
                                        <? $hour = str_pad($time, 2, '0', STR_PAD_LEFT).':00' ?>
                                        <option value="<?= $hour ?>" <?= (!empty($basketitem) && $hour == $basketitem->getField('timemin')) ? ('selected') : ('') ?>>
                                            <?= $hour ?>
                                        </option>
                                    <? } ?>
                                </select>
                                <span class="setTime__divider"></span>
                                <select name="timemax" class="styler js-field-value js-days-hours-times js-days-hours-time-max">
                                    <? for ($time = 8; $time <= 20; $time++) { ?>
                                        <? $hour = str_pad($time, 2, '0', STR_PAD_LEFT).':00' ?>
                                        <option value="<?= $hour ?>" <?= (!empty($basketitem) && $hour == $basketitem->getField('timemax')) ? ('selected') : ('') ?>>
                                            <?= $hour ?>
                                        </option>
                                    <? } ?>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <? // Обработка свойств товара. // ?>
            <? include ($_SERVER['DOCUMENT_ROOT'] . $this->getFolder() . '/props.php') ?>
        </div>

    <? } ?>

</div>