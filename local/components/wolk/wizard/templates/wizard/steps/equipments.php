<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<? $this->setFrameMode(true); ?>






<div class="main">
    <div id="step3" v-show="curStep == 2">
        <div class="equipmentcontainer">
            <div class="options_group" v-for="section in options.SECTIONS | orderBy 'SORT'">
                <div
					v-if="section.ITEMS" 
					@click="toggleSectionVisible(section)" 
					data-module="pagesubtitle-dropdown"
                    class="pagesubtitle moduleinited customizable_border"
                    :class="{'open': section.visible == true || !section.hasOwnProperty('visible')}"
				>
					{{ section.NAME }}
                </div>
                <div class="pagesubtitleopencontainer">
                    <additional-equipment v-for="item in section.ITEMS" :item="item" :section="section"></additional-equipment>
                </div>
            </div>
        </div>
    </div>
    
    <aside class="siteAside" data-sticky_column>
        <div class="basketcontainer">
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
    </aside>
    <div style="clear:both;"></div>
</div>

<? print_r($arResult['EQUIPMENTS']) ?>