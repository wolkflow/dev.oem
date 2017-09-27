<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?
CUtil::InitJSCore(Array('ajax'));
if (strlen($_POST['ajax_key']) && $_POST['ajax_key']==md5('ajax_'.LICENSE_KEY)) {
    $APPLICATION->RestartBuffer();
    if (!defined('PUBLIC_AJAX_MODE')) {
        define('PUBLIC_AJAX_MODE', true);
    }
    header('Content-type: application/json');
    if ($arResult['ERROR']) {
        echo json_encode(array(
            'type' => 'error',
            'message' => strip_tags($arResult['ERROR_MESSAGE']['MESSAGE']),
        ));
    } else {
        echo json_encode(array('type' => 'ok'));
    }
    require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/epilog_after.php');
    die();
}?>
<script>
    BX.message({
        secretKey: '<?= md5('ajax_'.LICENSE_KEY)?>'
    });
</script>
<?
if ($arResult['SHOW_ERRORS'] == 'Y' && $arResult['ERROR'])
	ShowMessage($arResult['ERROR_MESSAGE']);
?>
<?if($arResult["FORM_TYPE"] == "login"):?>
    <?=GetMessage("AUTH_LOGIN_BUTTON")?>
    <form name="system_auth_form<?=$arResult["RND"]?>" class="headersection__loginform"
          method="post" target="_top" action="<?=$arResult["AUTH_URL"]?>" id="login_form">
        <div class="headersection__loginformtitle">
            <?=GetMessage("AUTH_HEAD_TEXT")?>
        </div>
        <div class="errortext" style="display:none;"></div>
    <?if($arResult["BACKURL"] <> ''):?>
        <input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
    <?endif?>
    <?foreach ($arResult["POST"] as $key => $value):?>
        <input type="hidden" name="<?=$key?>" value="<?=$value?>" />
    <?endforeach?>
        <input type="hidden" name="AUTH_FORM" value="Y" />
        <input type="hidden" name="TYPE" value="AUTH" />
        <div class="headersection__loginforminputtitle first">
            <?=GetMessage("AUTH_EMAIL")?>
        </div>
        <input type="text" name="USER_LOGIN" class="headersection__loginforminput" placeholder="yourmail@domain.com"
			   value="<?=$arResult["USER_LOGIN"]?>"/>
        <div class="headersection__loginforminputtitle">
            <?=GetMessage("AUTH_PASSWORD")?>
        </div>
        <input type="password" name="USER_PASSWORD" class="headersection__loginforminput" autocomplete="off" />
    <?if($arResult["SECURE_AUTH"]):?>
                    <span class="bx-auth-secure" id="bx_auth_secure<?=$arResult["RND"]?>" title="<?echo GetMessage("AUTH_SECURE_NOTE")?>" style="display:none">
                        <div class="bx-auth-secure-icon"></div>
                    </span>
                    <noscript>
                    <span class="bx-auth-secure" title="<?echo GetMessage("AUTH_NONSECURE_NOTE")?>">
                        <div class="bx-auth-secure-icon bx-auth-secure-unlock"></div>
                    </span>
                    </noscript>
    <script type="text/javascript">
    document.getElementById('bx_auth_secure<?=$arResult["RND"]?>').style.display = 'inline-block';
    </script>
    <?endif?>
    <?if ($arResult["STORE_PASSWORD"] == "Y"):?>
            <input type="checkbox" id="USER_REMEMBER_frm" name="USER_REMEMBER" value="Y" />
            <label for="USER_REMEMBER_frm" title="<?=GetMessage("AUTH_REMEMBER_ME")?>"><?echo GetMessage("AUTH_REMEMBER_SHORT")?></label>
    <?endif?>
    <?if ($arResult["CAPTCHA_CODE"]):?>
        <?echo GetMessage("AUTH_CAPTCHA_PROMT")?>:<br />
        <input type="hidden" name="captcha_sid" value="<?echo $arResult["CAPTCHA_CODE"]?>" />
        <img src="/bitrix/tools/captcha.php?captcha_sid=<?echo $arResult["CAPTCHA_CODE"]?>" width="180" height="40" alt="CAPTCHA" /><br /><br />
        <input type="text" name="captcha_word" maxlength="50" value="" />
    <?endif?>
        <div class="headersection__loginformbutton">
            <?=GetMessage("AUTH_LOGIN_BUTTON")?>
            <input type="submit" name="Login" value="<?=GetMessage("AUTH_LOGIN_BUTTON")?>" />
        </div>
    <?if($arResult["NEW_USER_REGISTRATION"] == "Y"):?>
        <noindex>
            <p></p>
			<a 
				class="headersection__languagedropdownbutton auth"
				href="<?= $arResult["AUTH_REGISTER_URL"] ?>?backurl=<?= $APPLICATION->GetCurPage(false) ?>" 
				rel="nofollow"
			><?= GetMessage("AUTH_REGISTER") ?></a>
			<a 
				class="headersection__languagedropdownbutton auth"
				href="<?= $arResult["AUTH_FORGOT_PASSWORD_URL"] ?>?backurl=<?= $APPLICATION->GetCurPage(false) ?>" 
				rel="nofollow"
			><?= GetMessage("AUTH_FORGOT_PASS") ?></a>

        </noindex>
    <?endif?>
        <!--<noindex><a href="<?/*=$arResult["AUTH_FORGOT_PASSWORD_URL"]*/?>" rel="nofollow"><?/*=GetMessage("AUTH_FORGOT_PASSWORD_2")*/?></a></noindex>-->
    <?if($arResult["AUTH_SERVICES"]):?>
        <div class="bx-auth-lbl"><?=GetMessage("socserv_as_user_form")?></div>
    <?
    $APPLICATION->IncludeComponent("bitrix:socserv.auth.form", "icons",
        array(
            "AUTH_SERVICES"=>$arResult["AUTH_SERVICES"],
            "SUFFIX"=>"form",
        ),
        $component,
        array("HIDE_ICONS"=>"Y")
    );
    ?>
    <?endif?>
    </form>

<?if($arResult["AUTH_SERVICES"]):?>
<?
$APPLICATION->IncludeComponent("bitrix:socserv.auth.form", "", 
	array(
		"AUTH_SERVICES"=>$arResult["AUTH_SERVICES"],
		"AUTH_URL"=>$arResult["AUTH_URL"],
		"POST"=>$arResult["POST"],
		"POPUP"=>"Y",
		"SUFFIX"=>"form",
	), 
	$component, 
	array("HIDE_ICONS"=>"Y")
);
?>
<?endif?>
<?
elseif($arResult["FORM_TYPE"] == "otp"):
?>
<form name="system_auth_form<?=$arResult["RND"]?>"
      method="post" target="_top" action="<?=$arResult["AUTH_URL"]?>">
<?if($arResult["BACKURL"] <> ''):?>
	<input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
<?endif?>
	<input type="hidden" name="AUTH_FORM" value="Y" />
	<input type="hidden" name="TYPE" value="OTP" />
	<table width="95%">
		<tr>
			<td colspan="2">
			<?echo GetMessage("auth_form_comp_otp")?><br />
			<input type="text" name="USER_OTP" maxlength="50" value="" size="17" autocomplete="off" /></td>
		</tr>
<?if ($arResult["CAPTCHA_CODE"]):?>
		<tr>
			<td colspan="2">
			<?echo GetMessage("AUTH_CAPTCHA_PROMT")?>:<br />
			<input type="hidden" name="captcha_sid" value="<?echo $arResult["CAPTCHA_CODE"]?>" />
			<img src="/bitrix/tools/captcha.php?captcha_sid=<?echo $arResult["CAPTCHA_CODE"]?>" width="180" height="40" alt="CAPTCHA" /><br /><br />
			<input type="text" name="captcha_word" maxlength="50" value="" /></td>
		</tr>
<?endif?>
<?if ($arResult["REMEMBER_OTP"] == "Y"):?>
		<tr>
			<td valign="top"><input type="checkbox" id="OTP_REMEMBER_frm" name="OTP_REMEMBER" value="Y" /></td>
			<td width="100%"><label for="OTP_REMEMBER_frm" title="<?echo GetMessage("auth_form_comp_otp_remember_title")?>"><?echo GetMessage("auth_form_comp_otp_remember")?></label></td>
		</tr>
<?endif?>
		<tr>
			<td colspan="2"><input type="submit" name="Login" value="<?=GetMessage("AUTH_LOGIN_BUTTON")?>" /></td>
		</tr>
		<tr>
			<td colspan="2"><noindex><a href="<?=$arResult["AUTH_LOGIN_URL"]?>" rel="nofollow"><?echo GetMessage("auth_form_comp_auth")?></a></noindex><br /></td>
		</tr>
	</table>
</form>
<?
else:
?>
    <?#=($arResult["USER_NAME"])?: $arResult["USER_LOGIN"]?>
	
    <form action="<?= $arResult['AUTH_URL'] ?>">
        <? foreach ($arResult['GET'] as $key => $value) { ?>
            <input type="hidden" name="<?= $key ?>" value="<?= $value ?>" />
        <? } ?>
		<input type="hidden" name="step" value="<?= strval($_REQUEST['step']) ?>" class="js-step-watcher" />
        <input type="hidden" name="logout" value="yes" />
        <button class="customizable" type="submit" name="logout_butt">
            <?= GetMessage("AUTH_LOGOUT_BUTTON") ?>
        </button>
    </form>
<?endif?>

