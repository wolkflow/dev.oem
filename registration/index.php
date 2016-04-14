<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Registration");

?>
<?$APPLICATION->IncludeComponent(
	"bitrix:main.register", 
	"oem", 
	array(
		"COMPONENT_TEMPLATE" => "oem",
		"SHOW_FIELDS" => array(
			0 => "NAME",
			1 => "LAST_NAME",
			2 => "PERSONAL_PHONE",
			3 => "WORK_COMPANY",
			4 => "WORK_STREET",
		),
		"REQUIRED_FIELDS" => array(
			0 => "NAME",
			1 => "LAST_NAME",
			2 => "PERSONAL_PHONE",
			3 => "WORK_COMPANY",
			4 => "WORK_STREET",
		),
		"AUTH" => "Y",
		"USE_BACKURL" => "Y",
		"SUCCESS_PAGE" => "/",
		"SET_TITLE" => "Y",
		"USER_PROPERTY" => array(
			0 => "UF_VAT",
		),
		"USER_PROPERTY_NAME" => ""
	),
	false
);?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>