<?
/**
 * @global CMain $APPLICATION
 * @param array $arParams
 * @param array $arResult
 */
use Bitrix\Main\Localization\Loc;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true)
    die();
?>
<div class="pagesubtitle">
    <?= GetMessage("USER_PERSONAL_INFO") ?>
</div>
<div class="profilecontainer__columnscontainer">
    <? ShowError($arResult["strProfileError"]); ?>
    <?
    if ($arResult['DATA_SAVED'] == 'Y')
        ShowNote(GetMessage('PROFILE_DATA_SAVED'));
    ?>
    <script type="text/javascript">
        <!--
        var opened_sections = [<?
$arResult["opened"] = $_COOKIE[$arResult["COOKIE_PREFIX"]."_user_profile_open"];
$arResult["opened"] = preg_replace("/[^a-z0-9_,]/i", "", $arResult["opened"]);
if (strlen($arResult["opened"]) > 0)
{
	echo "'".implode("', '", explode(",", $arResult["opened"]))."'";
}
else
{
	$arResult["opened"] = "reg";
	echo "'reg'";
}
?>];
        //-->

        var cookie_prefix = '<?=$arResult["COOKIE_PREFIX"]?>';
    </script>
    <form method="post" name="form1" action="<?= $arResult["FORM_TARGET"] ?>" enctype="multipart/form-data">
        <?= $arResult["BX_SESSION_CHECK"] ?>
        <input type="hidden" name="lang" value="<?= LANG ?>"/>
        <input type="hidden" name="ID" value=<?= $arResult["ID"] ?>/>
        <input type="hidden" name="LOGIN" value="<?=$arResult["arUser"]["LOGIN"] ?>"/>
        <div class="profilecontainer__column right">
            <div class="pagesubsubtitle"><?= GetMessage('EMAIL') ?></div>
            <div class="profilecontainer__itemscontainer">
                <div class="profilecontainer__item">
                    <div class="profilecontainer__itemname">
                        <span>
                            <? echo $arResult["arUser"]["EMAIL"] ?>&nbsp;
                        </span>
                        <input style="display:none;" type="text" name="EMAIL" value="<? echo $arResult["arUser"]["EMAIL"] ?>"/>
                    </div>
                    <div class="profilecontainer__changebutton"><?= Loc::getMessage('change')?></div>
                </div>
            </div>
            <div class="pagesubsubtitle"><?= GetMessage('VAT_ID') ?></div>
            <div class="profilecontainer__itemscontainer">
                <div class="profilecontainer__item">
                    <div class="profilecontainer__itemname">
                        <span>
                            <? echo $arResult["arUser"]["UF_VAT"] ?>&nbsp;
                        </span>
                        <input style="display:none;" type="text" name="UF_VAT" value="<? echo $arResult["arUser"]["UF_VAT"] ?>"/>
                    </div>
                    <div class="profilecontainer__changebutton"><?= Loc::getMessage('change')?></div>
                </div>
            </div>
            <? if ($arResult["arUser"]["EXTERNAL_AUTH_ID"] == ''): ?>
                <div class="pagesubsubtitle"><?= GetMessage('NEW_PASSWORD_REQ') ?></div>
                <div class="profilecontainer__itemscontainer">
                    <div class="profilecontainer__item">
                        <div class="profilecontainer__itemname">
                            <span>
                                ******
                            </span>
                            <input style="display:none;" type="password" name="NEW_PASSWORD" value="" autocomplete="off"/>
                        </div>
                        <div class="profilecontainer__changebutton"><?= Loc::getMessage('change')?></div>
                    </div>
                </div>
                <div class="pagesubsubtitle password_confirm"><?= GetMessage('NEW_PASSWORD_CONFIRM') ?></div>
                <div class="profilecontainer__itemscontainer password_confirm" id="password_confirm_input">
                    <div class="profilecontainer__item">
                        <div class="profilecontainer__itemname">
                            <input type="password" name="NEW_PASSWORD_CONFIRM" maxlength="50" value="" autocomplete="off"/>
                        </div>
                    </div>
                </div>
                <? if ($arResult["SECURE_AUTH"]): ?>
                    <span class="bx-auth-secure" id="bx_auth_secure"
                          title="<? echo GetMessage("AUTH_SECURE_NOTE") ?>" style="display:none">
                        <div class="bx-auth-secure-icon"></div>
                    </span>
                    <noscript>
                        <span class="bx-auth-secure" title="<? echo GetMessage("AUTH_NONSECURE_NOTE") ?>">
                            <div class="bx-auth-secure-icon bx-auth-secure-unlock"></div>
                        </span>
                    </noscript>
                    <script type="text/javascript">
                        document.getElementById('bx_auth_secure').style.display = 'inline-block';
                    </script>
                <? endif ?>
            <? endif ?>
            <div class="profilecontainer__savebutton customizable" id="profilecontainer__savebutton">
                <?= GetMessage("MAIN_SAVE") ?>
                <input type="submit" name="save" value="<?= (($arResult["ID"] > 0) ? GetMessage("MAIN_SAVE") : GetMessage("MAIN_ADD")) ?>">
            </div>
        </div>
        <div class="left profilecontainer__column">
            <div class="pagesubsubtitle">
                <?= GetMessage('USER_COMPANY') ?>
            </div>
            <div class="profilecontainer__itemscontainer">
                <div class="profilecontainer__item">
                    <div class="profilecontainer__itemname">
                        <span>
                            <? echo $arResult["arUser"]["WORK_COMPANY"] ?>&nbsp;
                        </span>
                        <input style="display:none;" type="text" name="WORK_COMPANY" value="<?= $arResult["arUser"]["WORK_COMPANY"] ?>"/>
                    </div>
                    <div class="profilecontainer__changebutton"><?= Loc::getMessage('change')?></div>
                </div>
            </div>
            <div class="pagesubsubtitle"><?= GetMessage("WORK_STREET") ?></div>
            <div class="profilecontainer__itemscontainer">
                <div class="profilecontainer__item">
                    <div class="profilecontainer__itemname">
                        <span>
                            <? echo $arResult["arUser"]["WORK_STREET"] ?>&nbsp;
                        </span>
                        <textarea style="display:none;" cols="30" rows="5"
                              name="WORK_STREET"><?= $arResult["arUser"]["WORK_STREET"] ?></textarea>
                    </div>
                    <div class="profilecontainer__changebutton"><?= Loc::getMessage('change')?></div>
                </div>
            </div>
            <div class="pagesubsubtitle"><?= GetMessage('NAME') ?></div>
            <div class="profilecontainer__itemscontainer">
                <div class="profilecontainer__item">
                    <div class="profilecontainer__itemname">
                        <span>
                            <? echo $arResult["arUser"]["NAME"] ?>&nbsp;
                        </span>
                        <input style="display:none;" type="text" name="NAME" value="<?= $arResult["arUser"]["NAME"] ?>"/>
                    </div>
                    <div class="profilecontainer__changebutton"><?= Loc::getMessage('change')?></div>
                </div>
            </div>
            <div class="pagesubsubtitle"><?= GetMessage('LAST_NAME') ?></div>
            <div class="profilecontainer__itemscontainer">
                <div class="profilecontainer__item">
                    <div class="profilecontainer__itemname">
                        <span>
                            <? echo $arResult["arUser"]["LAST_NAME"] ?>&nbsp;
                        </span>
                        <input style="display:none;" type="text" name="LAST_NAME" value="<?= $arResult["arUser"]["LAST_NAME"] ?>"/>
                    </div>
                    <div class="profilecontainer__changebutton"><?= Loc::getMessage('change')?></div>
                </div>
            </div>
            <div class="pagesubsubtitle"><?= GetMessage('USER_PHONE') ?></div>
            <div class="profilecontainer__itemscontainer">
                <div class="profilecontainer__item">
                    <div class="profilecontainer__itemname">
                        <span>
                            <? echo $arResult["arUser"]["PERSONAL_PHONE"] ?>&nbsp;
                        </span>
                        <input style="display:none;" type="text" name="PERSONAL_PHONE" value="<?= $arResult["arUser"]["PERSONAL_PHONE"] ?>"/>
                    </div>
                    <div class="profilecontainer__changebutton"><?= Loc::getMessage('change')?></div>
                </div>
            </div>
        </div>
    </form>
</div>