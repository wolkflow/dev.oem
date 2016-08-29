<? use Bitrix\Main\Localization\Loc; ?>
<script type="x/template" id="basket">
    <div class="basketcontainer__title customizable_border"><?= Loc::getMessage('basket') ?></div>
    <div class="basketcontainer__itemscontainer customizable_border" v-if="selectedStand">
    
        <div class="basketcontainer__itemcontainer customizable_border" v-show="selectedStand.ID > 0">
            <div class="basketcontainer__itemname">
                {{ selectedStand.NAME }}
            </div>
            <div class="basketcontainer__itemtotalprice">
                {{ selectedStand.PRICE.PRICE | format_currency ' ' currency_format}}
            </div>
            <div class="basketcontainer__itemprice">
                {{ selectedParams.WIDTH }}m &times; {{ selectedParams.DEPTH }}m
            </div>
        </div>
        
        <div class="basketcontainer__itemcontainer customizable_border" v-for="eq in selectedStand.EQUIPMENT | overIncluded">
            <div class="basketcontainer__itemname">{{ allServices[eq.ID].NAME }}</div>
            <div class="basketcontainer__itemtotalprice">
                {{ allServices[eq.ID].PRICE * (eq.QUANTITY - eq.COUNT) | format_currency ' ' currency_format}}
            </div>
            <div class="basketcontainer__itemprice">
                {{ allServices[eq.ID].PRICE | format_currency ' ' currency_format}} &times; {{ eq.QUANTITY - eq.COUNT }}
            </div>
        </div>
        
        <div v-for="(groupId, services) in selectedStand.SERVICES">
            <div class="basketcontainer__itemcontainer customizable_border" v-for="service in services | visibleInCart">
                <div v-if="service.ID == 5" class="basketcontainer__itemname">
                    {{ allServices[service.ID].NAME + ' ('+service.FASCIA_TEXT + ' - ' + service.FASCIA_COLOR + ') ' }}
                </div>
                <div v-else class="basketcontainer__itemname">{{ allServices[service.ID].NAME }}</div>
                <div class="basketcontainer__itemtotalprice">
                    {{ service.MULTIPLIER ? allServices[service.ID].PRICE * service.QUANTITY * service.MULTIPLIER : allServices[service.ID].PRICE * service.QUANTITY | format_currency ' ' currency_format}}
                </div>
                <div class="basketcontainer__itemprice">
                    {{ service.MULTIPLIER ? allServices[service.ID].PRICE : allServices[service.ID].PRICE | format_currency ' ' currency_format}} 
                    &times;
                    {{ service.QUANTITY * (service.MULTIPLIER ? service.MULTIPLIER : 1) }}
                </div>
            </div>
        </div>
        
        <div v-for="(groupId, options) in selectedStand.OPTIONS">
            <div class="basketcontainer__itemcontainer customizable_border" v-for="option in options">
                <div class="basketcontainer__itemname">
                    {{ allServices[option.ID].NAME }}
                </div>
                <div class="basketcontainer__itemtotalprice">
                    {{ allServices[option.ID].PRICE * option.QUANTITY | format_currency ' ' currency_format}}
                </div>
                <div class="basketcontainer__itemprice">
                    {{ allServices[option.ID].PRICE | format_currency ' ' currency_format}} &times; {{ option.QUANTITY }}
                </div>
            </div>
        </div>
        
		<!--<div class="basketcontainer__taxpricecontainer" v-show="taxPrice">
            <div class="basketcontainer__taxpricecontainertitle"><?=Loc::getMessage('tax_price')?>:</div>
            <div class="basketcontainer__taxpricecontainercount">{{ taxPrice | format_currency ' ' currency_format}}</div>
        </div>-->
        
        <div class="basketcontainer__totalpricecontainer" v-show="totalPrice">
            <div class="basketcontainer__totalpricecontainertitle">
				<?= Loc::getMessage('total_price') ?>*:
			</div>
            <div class="basketcontainer__totalpricecontainercount">
				{{ totalPrice | format_currency ' ' currency_format}}
			</div>
            <small>
				<? if ($arResult['EVENT']['PROPS']['INCLUDE_VAT']['VALUE'] != 'Y') { ?>
					<?= Loc::getMessage('tax_is_excluded') ?>
				<? } else { ?>
					<?= Loc::getMessage('tax_is_included') ?>
				<? } ?>
			</small>
        </div>
        <div class="navButtons">
            <a href="#" class="button styler prev" @click.prevent="prevStep">
				<?= Loc::getMessage('back') ?>
			</a>
            <div @click="nextStep" class="basketcontainer__nextstepbutton"><?= Loc::getMessage('next') ?></div>
        </div>
    </div>
</script>