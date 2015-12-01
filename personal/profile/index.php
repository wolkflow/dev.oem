<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Настройки пользователя");
?>
<div data-module="profilepage" class="profilepage">
    <? Helper::includeFile(LANGUAGE_ID . '_profile')?>
    <div rel="history" class="profilecontainer active">
        <?$APPLICATION->IncludeComponent(
            "bitrix:main.profile",
            "oem",
            Array(
                "SET_TITLE" => "Y",
            )
        );?>
    </div>
    <div rel="personal" class="profilecontainer">
        <?$APPLICATION->IncludeComponent("bitrix:sale.personal.order", ".default", Array(
                "SEF_MODE"	=>	"N",
                "ORDERS_PER_PAGE"	=>	"20",
                "PATH_TO_PAYMENT"	=>	"/personal/order/payment/",
                "PATH_TO_BASKET"	=>	"/personal/cart/",
                "SET_TITLE"	=>	"Y"
            )
        );?>
    </div>
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>