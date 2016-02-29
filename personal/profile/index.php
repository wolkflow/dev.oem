<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Настройки пользователя");
?>
<div data-module="profilepage" class="profilepage">
    <? Helper::includeFile(\Bitrix\Main\Context::getCurrent()->getLanguage() . '_profile')?>
    <div rel="history" class="profilecontainer active">
        <?$APPLICATION->IncludeComponent(
            "bitrix:main.profile",
            "oem",
            Array(
                "SET_TITLE" => "Y",
            )
        );?>
    </div>
</div>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>