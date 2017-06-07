<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<? $this->setFrameMode(true); ?>

<? use Bitrix\Main\Localization\Loc; ?>
<? use Wolk\Core\Helpers\Text as TextHelper ?>

<pre>
    <? print_r($arResult['ITEMS']) ?>
</pre>

<div class="pagetitle">
    <?= Loc::getMessage('TITLE_EQUIPMENT') ?>
</div>

<div class="main">
    <div id="step3">
        <div class="equipmentcontainer">
            <div class="options_group">
                <? foreach ($arResult['ITEMS'] as $group) { ?>
                    <div class="pagesubtitle moduleinited customizable_border" data-module="pagesubtitle-dropdown">
                        <?= $group->getTitle() ?>
                    </div>
                    <div class="pagesubtitleopencontainer">
                        <additional-equipment v-for="item in section.ITEMS" :item="item" :section="section"></additional-equipment>
                    </div>
                <? } ?>
            </div>
        </div>
    </div>
    
    <aside class="siteAside" data-sticky_column>
        <div class="basketcontainer">
            <div class="basketcontainer__title customizable_border">
                <?= Loc::getMessage('BASKET') ?>
            </div>
            <div class="basketcontainer__itemscontainer customizable_border">
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
            <div class="navButtons">
                <a href="<?= $arResult['LINKS']['PREV'] ?>" class="button styler prev">
                    <?= Loc::getMessage('PREV') ?>
                </a>
                <div class="basketcontainer__nextstepbutton">
                    <?= Loc::getMessage('NEXT') ?>
                </div>
            </div>
        </div>
        </div>
    </aside>
    <div style="clear:both;"></div>
</div>

<? print_r($arResult['EQUIPMENTS']) ?>