<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<? use Wolk\Core\Helpers\Text as TextHelper; ?>
<? use Bitrix\Main\Localization\Loc; ?>

<? $this->setFrameMode(true); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
	<title>OSEC</title>
	<style type="text/css">
		@import url(http://<?= $arResult['SERVER_NAME'] ?>/mail/fonts/fonts.css);
	</style>
</head>
<body style="margin: 0;padding: 0;">
	<table cellpadding="0" cellspacing="0" border="0" style="margin: 0;padding: 0;width: 100%;background-image: url('http://<?= $arResult['SERVER_NAME'] ?>/upload/mail/images/bg.jpg');background-repeat: no-repeat;background-position: 50% 0;">
		<tr align="center">
			<td style="padding-top: 59px;padding-bottom: 57px;">
				<a href="http://<?= $arResult['SERVER_NAME'] ?>/events/<?= $arResult['EVENT']['CODE'] ?>/" style="text-decoration: none;">
					<img src="http://<?= $arResult['SERVER_NAME'] ?><?= $arResult['EVENT']['LOGO'] ?>" style="display: block;" />
				</a>
			</td>
		</tr>
		<tr>
			<td align="center" style="padding-bottom: 20px;">
				<table cellpadding="0" cellspacing="0" border="0" style="margin: 0;padding: 0;width: 100%;max-width: 600px;vertical-align: top;">
					<tr>
						<td height="5" style="height: 5px;background: url('http://<?= $arResult['SERVER_NAME'] ?>/upload/mail/images/top.png');"></td>
					</tr>
					<tr valign="top">
						<td style="padding-top: 52px;padding-bottom: 50px;background-image: url('http://<?= $arResult['SERVER_NAME'] ?>/upload/mail/images/mid.png');background-repeat: repeat-y;padding-right: 61px;padding-left: 61px;">
							<p style="margin: 0 0 34px 0;padding: 0;font-size: 15px;font-weight: 700;text-transform: uppercase;font-family: 'GothamPro', Arial, Helvetica, sans-serif;color: #333333;">
								<?= Loc::getMessage('dear') ?><?= $arResult['USER']['NAME'] ?><?= Loc::getMessage('goodday') ?>!
							</p>
							<p style="margin: 0;padding: 0;font-size: 15px;font-family: 'GothamPro', Arial, Helvetica, sans-serif;line-height: 24px;color: #333333;">
								<?= Loc::getMessage('bill') ?> 
									№<?= $arResult['PROPS']['NUMBER']['VALUE'] ?> 
									<?= Loc::getMessage('from') ?>
										<?= date('d', strtotime($arResult['ORDER']['DATE_INSERT'])) ?>
										<?= TextHelper::i18nmonth(date('n', strtotime($arResult['ORDER']['DATE_INSERT'])), false, $arParams['LANG']) ?>
										<?= date('Y', strtotime($arResult['ORDER']['DATE_INSERT'])) ?>
									<?= Loc::getMessage('additional') ?>
									<?= $arResult['EVENT']['NAME'] ?>. 
								<?= Loc::getMessage('conditions') ?>
							</p>
							<table cellpadding="0" cellspacing="0" border="0" style="margin: 0;padding: 0;width: 100%">
								<tr>
									<td style="padding-top: 111px">
										<p style="border-bottom: 3px solid #7f7f7f;margin: 0 0 26px 0;padding: 0 0 25px 0;font-size: 21px;font-family: 'GothamPro', Arial, Helvetica, sans-serif;font-weight: 700;color: #333333;text-transform: uppercase">
											<?= Loc::getMessage('team') ?>
										</p>
									</td>
								</tr>
								<? foreach ($arResult['EVENT']['PROPS']['LANG_CONTACTS_'.$arResult['LANGUAGE']]['VALUE'] as $contact) { ?>
									<tr>
										<td>
											<p style="margin: 0 0 13px 0;padding: 0;font-size: 15px;font-family: 'GothamPro', Arial, Helvetica, sans-serif;font-weight: 700;color: #333333;text-transform: uppercase">
												<?= $contact['POST'] ?>
											</p>
											<p style="margin: 0 0 7px 0;padding: 0;font-size: 15px;font-family: 'GothamPro', Arial, Helvetica, sans-serif;color: #333333;">
												<?= $contact['NAME'] ?>
											</p>
											<p style="margin: 0 0 7px 0;padding: 0;font-size: 15px;font-family: 'GothamPro', Arial, Helvetica, sans-serif;color: #333333;">
												<a href="mailto:<?= $contact['EMAIL'] ?>" style="color: #333333;-webkit-text-size-adjust: none;text-decoration: underline;">
													<?= $contact['EMAIL'] ?>
												</a>
											</p>
											<p style="margin: 0 0 43px 0;padding: 0;font-size: 15px;font-family: 'GothamPro', Arial, Helvetica, sans-serif;color: #333333;">
												<?= $contact['PHONE'] ?>
											</p>
										</td>
									</tr>
								<? } ?>
								<tr>
									<td style="border-top: 1px solid #7f7f7f;">
										<a href="http://<?= $arResult['SERVER_NAME'] ?>/events/<?= $arResult['EVENT']['CODE'] ?>/" style="text-decoration: none;display: block;padding-top: 60px;padding-bottom: 8px;">
											<img style="display: block;" src="http://<?= $arResult['SERVER_NAME'] ?>/upload/mail/images/logo.png" alt="Universal exhibition configuration" />
										</a>
									</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td height="5" style="height: 5px;background: url('http://<?= $arResult['SERVER_NAME'] ?>/upload/mail/images/bot.png');"></td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</body>
</html>