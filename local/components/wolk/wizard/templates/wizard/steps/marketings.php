<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<? $this->setFrameMode(true); ?>

<? use Bitrix\Main\Localization\Loc; ?>
<? use Wolk\Core\Helpers\Text as TextHelper ?>

<div id="js-marketing-id" class="pagetitle">
    <?= Loc::getMessage('TITLE_MARKETING') ?>
</div>

<? $basketgroups = $arResult['BASKET']->getSectionGroups() ?>

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
                            <? $pricetype  = str_replace('.', '-', $section->getPriceType()) ?>
                            <? $properties = $section->getProperties() ?>
                            <? $products   = $section->getInsides() ?>
                            <? $multiple   = (!empty($properties) || count($products) > 1 || $pricetype != 'quantity') ?>
                            <? $priceform  = strtolower($pricetype) ?>
                            
                            <?  // Наличие продукции в корзине.
                                $basketitem  = null;
                                $basketgroup = $basketgroups[$section->getID()];
                            ?>

                            <div id="js-s-<?= $section->getID() ?>-id" class="js-product-wrapper js-block-<?= strtolower($pricetype) ?>">
                                <? if (!empty($basketgroup)) { ?>
                                    <? if ($multiple) { ?>
                                        <div class="js-product-section js-pricetype-<?= strtolower($pricetype) ?>" data-sid="<?= $section->getID() ?>" data-pricetype="<?= $pricetype ?>">
                                            <div class="serviceItem__title">
                                                <?= $section->getTitle() ?>
                                            </div>
                                            <? foreach ($basketgroup as $basketitem) { ?>
                                                <? include ($_SERVER['DOCUMENT_ROOT'] . $this->getFolder() . '/card.php') ?>
                                            <? } ?>
                                        </div>
                                    <? } else { ?>
                                        <? $basketitem = reset($basketgroup) ?>
                                        <div class="js-product-section js-pricetype-<?= strtolower($pricetype) ?>" data-sid="<?= $section->getID() ?>" data-pricetype="<?= $pricetype ?>">
                                            <div class="serviceItem__title">
                                                <?= $section->getTitle() ?>
                                            </div>
                                            <? include ($_SERVER['DOCUMENT_ROOT'] . $this->getFolder() . '/card.php') ?>
                                        </div>
                                    <? } ?>
                                <? } else { ?>
                                    <div class="js-product-section js-pricetype-<?= strtolower($pricetype) ?>" data-sid="<?= $section->getID() ?>" data-pricetype="<?= $pricetype ?>">
                                        <div class="serviceItem__title">
                                            <?= $section->getTitle() ?>
                                        </div>
                                        <? include ($_SERVER['DOCUMENT_ROOT'] . $this->getFolder() . '/card.php') ?>
                                    </div>
                                <? } ?>

                                <? // Добавление типа товара // ?>
                                <? if ($multiple) { ?>
                                    <div class="serviceItem__bottom">
                                        <a href="javascript:void(0);" class="js-more-field itemAdd_field">
                                            <i class="customizable"></i>
                                            <span><?= Loc::getMessage('MORE') ?></span>
                                        </a>
                                    </div>
                                <? } ?>
                            </div>
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
						"STEPLINKS" => $arResult['STEPLINKS'],
                    )
                );
                ?>
            </div>
            <div class="navButtons">
                <a href="<?= $arResult['LINKS']['PREV'] ?>" class="button styler prev customizable">
                    <?= Loc::getMessage('PREV') ?>
                </a>
                <a href="<?= $arResult['LINKS']['NEXT'] ?>" class="button styler next customizable">
                    <?= Loc::getMessage('NEXT') ?>
                </a>
            </div>
        </div>
    </div>
</aside>
