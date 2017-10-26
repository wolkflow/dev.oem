<?
/**
 * Bitrix Framework
 * @package bitrix
 * @subpackage main
 * @copyright 2001-2014 Bitrix
 */

/**
 * Bitrix vars
 * @global CMain $APPLICATION
 * @global CUser $USER
 * @param array $arParams
 * @param array $arResult
 * @param CBitrixComponentTemplate $this
 */

if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)
	die();

$curlang = strtolower(\Bitrix\Main\Context::getCurrent()->getLanguage());
?>
<div class="profilepage">
<form method="post" action="<?=POST_FORM_ACTION_URI?>" name="regform" enctype="multipart/form-data">
<?
if($arResult["BACKURL"] <> ''):
?>
	<input type="hidden" name="backurl" value="<?=$arResult["BACKURL"]?>" />
<?
endif;
?>
    <div class="pagetitle">
        <?=GetMessage("AUTH_REGISTER")?>
    </div>
    <?if($USER->IsAuthorized()):?>

        <p><?= GetMessage("MAIN_REGISTER_AUTH") ?></p>

    <?else:?>
    <?
    if (count($arResult["ERRORS"]) > 0):
        foreach ($arResult["ERRORS"] as $key => $error)
            if (intval($key) == 0 && $key !== 0)
                $arResult["ERRORS"][$key] = str_replace("#FIELD_NAME#", "&quot;".GetMessage("REGISTER_FIELD_".$key)."&quot;", $error);

        ShowError(implode("<br />", $arResult["ERRORS"]));

    elseif($arResult["USE_EMAIL_CONFIRMATION"] === "Y"):
        ?>
        <p><?= GetMessage("REGISTER_EMAIL_WILL_BE_SENT") ?></p>
    <?endif?>
    <div rel="personal" class="profilecontainer active">
        <div class="pagesubtitle customizable_border"></div>
        <div class="profilecontainer__columnscontainer">
            <div class="profilecontainer__column right">
                <div class="pagesubsubtitle">
                    <?=GetMessage("REGISTER_FIELD_EMAIL")?>
                </div>
                <div class="profilecontainer__itemscontainer">
                    <div class="profilecontainer__item">
                        <div class="profilecontainer__itemname">
                            <input type="text" id="comMail" name="REGISTER[LOGIN]" value="<?=$arResult["VALUES"]['LOGIN']?>"/>
                        </div>
                    </div>
                </div>
                <div class="pagesubsubtitle">
                    <?=GetMessage("REGISTER_FIELD_EMAIL_CONFIRM")?>
                </div>
                <div class="profilecontainer__itemscontainer">
                    <div class="profilecontainer__item">
                        <div class="profilecontainer__itemname">
                            <input type="text" id="email_confirm" name="REGISTER[EMAIL_CONFIRM]" value="<?=$arResult["VALUES"]['EMAIL_CONFIRM']?>"/>
                        </div>
                    </div>
                </div>
                <? if ($curlang != LANG_RU) { ?>
                    <div class="pagesubsubtitle">
                        <?= $arResult["USER_PROPERTIES"]["DATA"]['UF_VAT']['EDIT_FORM_LABEL'] ?>
                    </div>
                    <div class="profilecontainer__itemscontainer">
                        <div class="profilecontainer__item">
                            <div class="profilecontainer__itemname">
                                <input type="text" name="UF_VAT" value="<?=$arResult['VALUES']["UF_VAT"]?>"/>
                            </div>
                        </div>
                    </div>
                <? } ?>
                <div class="pagesubsubtitle">
                    <?=GetMessage("REGISTER_FIELD_PASSWORD")?>
                </div>
                <div class="profilecontainer__itemscontainer">
                    <div class="profilecontainer__item">
                        <div class="profilecontainer__itemname">
                            <input type="password"
                                   name="REGISTER[PASSWORD]" value="<?=$arResult["VALUES"]['PASSWORD']?>" autocomplete="off" />
                        </div>
                    </div>
                </div>
                <div class="pagesubsubtitle">
                    <?=GetMessage("REGISTER_FIELD_CONFIRM_PASSWORD")?>
                </div>
                <div class="profilecontainer__itemscontainer">
                    <div class="profilecontainer__item">
                        <div class="profilecontainer__itemname">
                            <input type="password" id="password_confirm"
                                   name="REGISTER[CONFIRM_PASSWORD]"
                                   value="<?=$arResult["VALUES"]['CONFIRM_PASSWORD']?>" autocomplete="off" />
                        </div>
                    </div>
                </div>
                <div class="profilecontainer__savebutton customizable"><?=GetMessage("AUTH_REGISTER")?>
                    <input type="submit" name="register_submit_button" value="<?=GetMessage("AUTH_REGISTER")?>" />
                </div>
            </div>
            <div class="left profilecontainer__column">
                <div class="pagesubsubtitle">
                    <?=GetMessage("REGISTER_FIELD_WORK_COMPANY")?>
                </div>
                <div class="profilecontainer__itemscontainer">
                    <div class="profilecontainer__item">
                        <div class="profilecontainer__itemname">
                            <input type="text"
                                   name="REGISTER[WORK_COMPANY]" value="<?=$arResult["VALUES"]['WORK_COMPANY']?>"/>
                        </div>
                    </div>
                </div>
                <div class="pagesubsubtitle">
                    <?=GetMessage("REGISTER_FIELD_WORK_STREET")?>
                </div>
                <div class="profilecontainer__itemscontainer">
                    <div class="profilecontainer__item">
                        <div class="profilecontainer__itemname">
                            <input type="text" name="REGISTER[WORK_STREET]" value="<?=$arResult["VALUES"]['WORK_STREET']?>"/>
                        </div>
                    </div>
                </div>
                <div class="pagesubsubtitle">
                    <?=GetMessage("REGISTER_FIELD_NAME")?>
                </div>
                <div class="profilecontainer__itemscontainer">
                    <div class="profilecontainer__item">
                        <div class="profilecontainer__itemname">
                            <input type="text" name="REGISTER[NAME]" value="<?=$arResult["VALUES"]['NAME']?>"/>
                        </div>
                    </div>
                </div>
                <div class="pagesubsubtitle">
                    <?=GetMessage("REGISTER_FIELD_LAST_NAME")?>
                </div>
                <div class="profilecontainer__itemscontainer">
                    <div class="profilecontainer__item">
                        <div class="profilecontainer__itemname">
                            <input type="text" name="REGISTER[LAST_NAME]" value="<?=$arResult["VALUES"]['LAST_NAME']?>"/>
                        </div>
                    </div>
                </div>
                <div class="pagesubsubtitle">
                    <?=GetMessage("REGISTER_FIELD_PERSONAL_PHONE")?>
                </div>
                <div class="profilecontainer__itemscontainer">
                    <div class="profilecontainer__item">
                        <div class="profilecontainer__itemname">
                            <input type="text" name="REGISTER[PERSONAL_PHONE]" value="<?=$arResult["VALUES"]['PERSONAL_PHONE']?>"/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?
/* CAPTCHA */
if ($arResult["USE_CAPTCHA"] == "Y")
{
	?>
		<tr>
			<td colspan="2"><b><?=GetMessage("REGISTER_CAPTCHA_TITLE")?></b></td>
		</tr>
		<tr>
			<td></td>
			<td>
				<input type="hidden" name="captcha_sid" value="<?=$arResult["CAPTCHA_CODE"]?>" />
				<img src="/bitrix/tools/captcha.php?captcha_sid=<?=$arResult["CAPTCHA_CODE"]?>" width="180" height="40" alt="CAPTCHA" />
			</td>
		</tr>
		<tr>
			<td><?=GetMessage("REGISTER_CAPTCHA_PROMT")?>:<span class="starrequired">*</span></td>
			<td><input type="text" name="captcha_word" maxlength="50" value="" /></td>
		</tr>
	<?
}
/* !CAPTCHA */
?>
</form>
<?endif?>
</div>