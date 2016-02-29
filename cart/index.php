<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("cart");
?><?$APPLICATION->IncludeComponent("bitrix:sale.basket.basket", "template1", Array(
	"ACTION_VARIABLE" => "action",	// Name of action variable
		"COLUMNS_LIST" => "QUANTITY",	// Columns
		"COMPONENT_TEMPLATE" => ".default",
		"COUNT_DISCOUNT_4_ALL_QUANTITY" => "N",	// Calculate discount for each order item (whole quantity)
		"HIDE_COUPON" => "N",	// Hide upon entering coupon
		"OFFERS_PROPS" => "",	// SKU properties affecting cart refresh
		"PATH_TO_ORDER" => "/personal/order.php",	// Order page
		"PRICE_VAT_SHOW_VALUE" => "N",	// Show tax rate value
		"QUANTITY_FLOAT" => "N",	// Use fractional quantities
		"SET_TITLE" => "Y",	// Set page title
		"USE_PREPAYMENT" => "N",	// Use PayPal Express Checkout
	),
	false
);?><?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>