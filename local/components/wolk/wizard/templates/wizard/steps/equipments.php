<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<? $this->setFrameMode(true); ?>

<? use Bitrix\Main\Localization\Loc; ?>
<? use Wolk\Core\Helpers\Text as TextHelper ?>

<div class="pagetitle">
    <?= Loc::getMessage('TITLE_EQUIPMENT') ?>
</div>

<? $groups = $arResult['BASKET']->getSectionGroups() ?>

<div class="main">
    <div id="step">
        <div class="equipmentcontainer">
            <div class="options_group">
                <? foreach ($arResult['ITEMS'] as $group) { ?>
                    <div class="pagesubtitle moduleinited customizable_border open" data-module="pagesubtitle-dropdown">
                        <?= $group->getTitle() ?>
                    </div>
                    <div class="js-section-wrapper pagesubtitleopencontainer">
                        <? $sections = $group->getInsides('SORT') ?>
                        <? foreach ($sections as $section) { ?>
                            <? $pricetype  = $section->getPriceType() ?>
                            <? $properties = $section->getProperties() ?>
                            <? $products   = $section->getInsides() ?>
                        
                            
                            <?  // Наличие продукции в корзине.
                                $basketgroup = $groups[$section->getID()];
                            ?>
                            
                            <? // Простое количество // ?>
                            <? if ($pricetype == 'QUANTITY') { ?>
                                 <? include ($_SERVER['DOCUMENT_ROOT'] . $this->getFolder() . '/forms/quantity.php') ?> 
                            <? } ?>
                            
                            <? if (false) { ?>
                                <div class="serviceItem__block" v-for="selectedItem in selectedItems">
                                    <div class="serviceItem__row">
                                        <div class="serviceItem__left">
                                            <div class="serviceItem__subtitle">
                                                <?= $section->getDescription() ?>
                                            </div>
                                            <select class="styler">
                                                <option value="">
                                                    <?= Loc::getMessage('NOT_SELECTED') ?>
                                                </option>
                                                <option value="{{ item.ID }}" v-for="item in items | orderBy SORT">
                                                    {{ item.NAME }} ({{ item.PRICE | format_currency ' ' currency_format }})
                                                </option>
                                            </select>
                                        </div>
                                        <div class="serviceItem__right">
                                            <div class="itemCount">
                                                <div class="serviceItem__subtitle">
                                                    <?= Loc::getMessage('quantity') ?>
                                                </div>
                                                <div class="itemCount__button itemCount__down" @click="decQty(selectedItem, $index)"></div>
                                                <div class="itemCount__button itemCount__up" @click="incQty(selectedItem, $index)"></div>
                                                <input id="{{section.NAME}}_{{$index}}" type="text" v-model="selectedItem.QUANTITY" class="itemCount__input styler" number />
                                            </div>
                                        </div>
                                        <div style="margin-top: 10px;">
                                            <div class="serviceItem__cost">
                                                <div class="serviceItem__subtitle">
                                                    <?= Loc::getMessage('PRICE') ?>
                                                </div>
                                                <div class="serviceItem__cost-value">
                                                    {{ section.ITEMS[selectedItem.ID].PRICE | format_currency ' ' currency_format}}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <? } ?>
                            
                            <? // Добавление типа товара // ?>
                            <? if (!empty($properties)) { ?>
                                <div class="serviceItem__bottom">
                                    <a href="javascript:void(0);" class="js-more-field itemAdd_field">
                                        <i></i>
                                        <span><?= Loc::getMessage('MORE') ?></span>
                                    </a>
                                </div>
                            <? } ?>
                        <? } ?>
                    </div>
                <? } ?>
            </div>
        </div>
    </div>
    <div style="clear:both;"></div>
</div>

<aside class="siteAside" data-sticky_column>
    <div class="basketcontainer">
        <div class="basketcontainer__title customizable_border">
            <?= Loc::getMessage('BASKET') ?>
        </div>
        <div class="basketcontainer__itemscontainer customizable_border">
            <div id="js-basket-wrapper-id">
                <?  // Корзина.
                    $APPLICATION->IncludeComponent(
                        "wolk:basket", 
                        "side", 
                        array(
                            "EID"  => $arResult['EVENT']->getID(),
                            "CODE" => $arResult['EVENT']->getCode(),
                            "TYPE" => $arResult['CONTEXT']->getType(),
                            "LANG" => $arResult['CONTEXT']->getLang(),
                        )
                    );
                ?>
            </div>
            <div class="navButtons">
                <a href="<?= $arResult['LINKS']['PREV'] ?>" class="button styler prev">
                    <?= Loc::getMessage('PREV') ?>
                </a>
                <a href="<?= $arResult['LINKS']['NEXT'] ?>" class="button styler prev">
                    <?= Loc::getMessage('NEXT') ?>
                </a>
            </div>
        </div>
    </div>
</aside>
