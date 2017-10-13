<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("Forgot password");
?>
<?	// Восстановление пароля.
	$APPLICATION->IncludeComponent(
		"wolk:forgotpass", 
		"", 
		[]
	);
?>
<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>