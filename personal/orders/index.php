<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("История заказов");
?>
    <div class="profilepage">
        <? Helper::includeFile(\Bitrix\Main\Context::getCurrent()->getLanguage(). '_profile') ?>
        <div class="">
            <?	// История зказов.
				$APPLICATION->IncludeComponent(
					"bitrix:sale.personal.order",
					"orders",
					array(
						"SEF_MODE" => "N",
						"ORDERS_PER_PAGE" => "100",
						"PATH_TO_PAYMENT" => "",
						"PATH_TO_BASKET" => "",
						"SET_TITLE" => "Y",
						"COMPONENT_TEMPLATE" => "orders",
						"PROP_1" => array(),
						"PROP_2" => array(),
						"ACTIVE_DATE_FORMAT" => "m/d/Y",
						"CACHE_TYPE" => "N",
						"CACHE_TIME" => "3600",
						"CACHE_GROUPS" => "Y",
						"SAVE_IN_SESSION" => "Y",
						"NAV_TEMPLATE" => "",
						"CUSTOM_SELECT_PROPS" => array(
						),
						"HISTORIC_STATUSES" => array(
							0 => "F",
						),
						"STATUS_COLOR_F" => "gray",
						"STATUS_COLOR_N" => "green",
						"STATUS_COLOR_PSEUDO_CANCELLED" => "red"
					),
					false
				);
			?>
        </div>
    </div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>