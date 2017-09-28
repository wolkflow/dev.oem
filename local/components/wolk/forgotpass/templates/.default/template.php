<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<? $this->setFrameMode(true); ?>

<? use Bitrix\Main\Localization\Loc; ?>

<div class="profilepage">
	<form method="post" name="regform" enctype="multipart/form-data">
		<input type="hidden" name="action" value="recover" />
		
		<div class="pagetitle">
			<?= Loc::getMessage("RECOVER_PASSWORD") ?>
		</div>
		<? if ($USER->IsAuthorized()) { ?>
			<p><?= Loc::getMessage("MAIN_REGISTER_AUTH") ?></p>
		<? } else { ?>
		
			<div rel="personal" class="profilecontainer active">
				<div class="pagesubtitle customizable_border"></div>
				<div class="profilecontainer__columnscontainer">
					<div class="profilecontainer__column left">
						<div class="pagesubsubtitle">
							<?= GetMessage("RECOVER_FIELD_EMAIL") ?>
						</div>
						
						<div class="hide js-restore-success">
							<?= GetMessage("RECOVER_SUCCESS") ?>
						</div>
						<div class="hide js-restore-error-is-empty">
							<?= GetMessage("RECOVER_ERROR_EMAIL_IS_EMPTY") ?>
						</div>
						<div class="hide js-restore-error-not-found">
							<?= GetMessage("RECOVER_ERROR_EMAIL_NOT_FOUND") ?>
						</div>
						
						<div class="profilecontainer__itemscontainer">
							<div class="profilecontainer__item">
								<div class="profilecontainer__itemname">
									<input type="text" class="js-email-mask" name="EMAIL" value="" />
								</div>
							</div>
						</div>
						<div class="profilecontainer__savebutton customizable">
							<?= GetMessage("RECOVER_SEND") ?>
							<input type="submit" id="js-recover-pass-submit-id" value="<?= GetMessage("RECOVER_SEND") ?>" />
						</div>
					</div>
					<div class="profilecontainer__column right"></div>
				</div>
			</div>
			<? /*
			if (count($arResult["ERRORS"]) > 0):
				foreach ($arResult["ERRORS"] as $key => $error)
					if (intval($key) == 0 && $key !== 0)
						$arResult["ERRORS"][$key] = str_replace("#FIELD_NAME#", "&quot;".GetMessage("REGISTER_FIELD_".$key)."&quot;", $error);

				ShowError(implode("<br />", $arResult["ERRORS"]));

			elseif($arResult["USE_EMAIL_CONFIRMATION"] === "Y"):
				?>
				<p><?= Loc::getMessage("REGISTER_EMAIL_WILL_BE_SENT") ?></p>
			<?endif  */ ?>
		
		<? } ?>
	</form>
</div>