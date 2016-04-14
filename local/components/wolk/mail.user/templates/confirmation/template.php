<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die(); ?>

<? use Dwai\Kvartirolog\Helpers\Text as TextHelper; ?>

<? $context  = \Bitrix\Main\Application::getInstance()->getContext(); ?>
<? $language = strtoupper($context->getLanguage()); ?>

<? $this->setFrameMode(true); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<meta http-equiv="Content-Type" content="text/html;charset=utf-8" />
	<title>OSEC</title>
	<style type="text/css">
		@import url(http://<?= SITE_SERVER_NAME ?>/mail/fonts/fonts.css);
	</style>
</head>
<body style="margin: 0;padding: 0;">
	<table cellpadding="0" cellspacing="0" border="0" style="margin: 0;padding: 0;width: 100%;background-image: url('http://<?= SITE_SERVER_NAME ?>/upload/mail/images/bg.jpg');background-repeat: no-repeat;background-position: 50% 0;">
		<tr align="center">
			<td style="padding-top: 59px;padding-bottom: 57px;">				
				<? if (!empty($arParams['EVENT'])) { ?>
					<a href="http://<?= SITE_SERVER_NAME ?>/events/<?= $arResult['EVENT']['CODE'] ?>/" style="text-decoration: none;">
						<img src="http://<?= SITE_SERVER_NAME ?><?= $arResult['EVENT']['LOGO'] ?>" style="display: block;" />
					</a>
				<? } else { ?>
					<a href="http://<?= SITE_SERVER_NAME ?>/" style="text-decoration: none;">
						<img src="http://<?= SITE_SERVER_NAME ?>/upload/mail/images/logoTop.png" alt="OSEC" style="display: block;" />
					</a>
				<? } ?>
			</td>
		</tr>
		<tr>
			<td align="center" style="padding-bottom: 20px;">
				<table cellpadding="0" cellspacing="0" border="0" style="margin: 0;padding: 0;width: 100%;max-width: 600px;vertical-align: top;">
					<tr>
						<td height="5" style="height: 5px;background: url('http://<?= SITE_SERVER_NAME ?>/upload/mail/images/top.png');"></td>
					</tr>
					<tr valign="top">
						<td style="padding-top: 52px;padding-bottom: 50px;background-image: url('http://<?= SITE_SERVER_NAME ?>/upload/mail/images/mid.png');background-repeat: repeat-y;padding-right: 61px;padding-left: 61px;">
							<table cellpadding="0" cellspacing="0" border="0" style="margin: 0;padding: 0;width: 100%">
								<tr>
									<td>
										<p style="margin: 0 0 34px 0;padding: 0;font-size: 15px;font-weight: 700;text-transform: uppercase;font-family: 'GothamPro', Arial, Helvetica, sans-serif;color: #333333;">
											Уважаемый <?= $arResult['USER']['NAME'] ?>!
										</p>
										<p style="margin: 0 0 51px 0;padding: 0;font-size: 15px;font-family: 'GothamPro', Arial, Helvetica, sans-serif;line-height: 24px;color: #333333;">
											Благодарим вас за регистрацию в онлайн-конфигураторе<? if (!empty($arParams['EVENT'])) { ?> выставки <?= $arResult['EVENT']['NAME'] ?><? } ?>.
											Пожалуйста, проверьте данные, которые вы указали в системе, потому что они будут использоваться для связи с вами и других операций.
										</p>
										<table cellpadding="0" cellspacing="0" border="0" style="margin: 0;padding: 0;width: 100%;">
											<tr>
												<td>
													<p style="border-bottom: 3px solid #7f7f7f;margin: 0 0 26px 0;padding: 0 0 25px 0;font-size: 21px;font-family: 'GothamPro', Arial, Helvetica, sans-serif;font-weight: 700;color: #333333;text-transform: uppercase">компания и персональные данные</p>
												</td>
											</tr>
											<tr>
												<td style="padding-bottom: 43px;">
													<p style="margin: 0 0 15px 0;padding: 0;font-size: 15px;font-family: 'GothamPro', Arial, Helvetica, sans-serif;font-weight: 700;color: #333333;text-transform: uppercase">	
														Название
													</p>
													<div><img style="display: block;" src="http://<?= SITE_SERVER_NAME ?>/upload/mail/images/line.png" /></div>
													<p style="margin: 15px 0 0 0;padding: 0;font-size: 15px;font-family: 'GothamPro', Arial, Helvetica, sans-serif;color: #333333;">
														<?= $arResult['USER']['WORK_COMPANY'] ?>
													</p>
												</td>
											</tr>
											<tr>
												<td style="padding-bottom: 43px;">
													<p style="margin: 0 0 15px 0;padding: 0;font-size: 15px;font-family: 'GothamPro', Arial, Helvetica, sans-serif;font-weight: 700;color: #333333;text-transform: uppercase">
														Адрес
													</p>
													<div><img style="display: block;" src="http://<?= SITE_SERVER_NAME ?>/upload/mail/images/line.png" /></div>
													<p style="margin: 15px 0 0 0;padding: 0;font-size: 15px;font-family: 'GothamPro', Arial, Helvetica, sans-serif;color: #333333;">
														<?= $arResult['USER']['WORK_STREET'] ?>
													</p>
												</td>
											</tr>
											<tr>
												<td style="padding-bottom: 43px;">
													<p style="margin: 0 0 15px 0;padding: 0;font-size: 15px;font-family: 'GothamPro', Arial, Helvetica, sans-serif;font-weight: 700;color: #333333;text-transform: uppercase">
														Имя
													</p>
													<div><img style="display: block;" src="http://<?= SITE_SERVER_NAME ?>/upload/mail/images/line.png" /></div>
													<p style="margin: 15px 0 0 0;padding: 0;font-size: 15px;font-family: 'GothamPro', Arial, Helvetica, sans-serif;color: #333333;">
														<?= $arResult['USER']['NAME'] ?>
													</p>
												</td>
											</tr>
											<tr>
												<td style="padding-bottom: 43px;">
													<p style="margin: 0 0 15px 0;padding: 0;font-size: 15px;font-family: 'GothamPro', Arial, Helvetica, sans-serif;font-weight: 700;color: #333333;text-transform: uppercase">
														Фамилия
													</p>
													<div><img style="display: block;" src="http://<?= SITE_SERVER_NAME ?>/upload/mail/images/line.png" /></div>
													<p style="margin: 15px 0 0 0;padding: 0;font-size: 15px;font-family: 'GothamPro', Arial, Helvetica, sans-serif;color: #333333;">
														<?= $arResult['USER']['LAST_NAME'] ?>
													</p>
												</td>
											</tr>
											<tr>
												<td style="padding-bottom: 43px;">
													<p style="margin: 0 0 15px 0;padding: 0;font-size: 15px;font-family: 'GothamPro', Arial, Helvetica, sans-serif;font-weight: 700;color: #333333;text-transform: uppercase">
														Телефон
													</p>
													<div><img style="display: block;" src="http://<?= SITE_SERVER_NAME ?>/upload/mail/images/line.png" /></div>
													<p style="margin: 15px 0 0 0;padding: 0;font-size: 15px;font-family: 'GothamPro', Arial, Helvetica, sans-serif;color: #333333;">
														<?= $arResult['USER']['PERSONAL_PHONE'] ?>
													</p>
												</td>
											</tr>
											<tr>
												<td style="padding-bottom: 43px;">
													<p style="margin: 0 0 15px 0;padding: 0;font-size: 15px;font-family: 'GothamPro', Arial, Helvetica, sans-serif;font-weight: 700;color: #333333;text-transform: uppercase">
														Электронная почта
													</p>
													<div><img style="display: block;" src="http://<?= SITE_SERVER_NAME ?>/upload/mail/images/line.png" /></div>
													<p style="margin: 15px 0 0 0;padding: 0;font-size: 15px;font-family: 'GothamPro', Arial, Helvetica, sans-serif;color: #333333;">
														<?= $arResult['USER']['EMAIL'] ?>
													</p>
												</td>
											</tr>
											<tr>
												<td style="padding-bottom: 43px;">
													<p style="margin: 0 0 15px 0;padding: 0;font-size: 15px;font-family: 'GothamPro', Arial, Helvetica, sans-serif;font-weight: 700;color: #333333;text-transform: uppercase">Номер 
														VAT
													</p>
													<div><img style="display: block;" src="http://<?= SITE_SERVER_NAME ?>/upload/mail/images/line.png" /></div>
													<p style="margin: 15px 0 0 0;padding: 0;font-size: 15px;font-family: 'GothamPro', Arial, Helvetica, sans-serif;color: #333333;">
														<?= $arResult['USER']['UF_VAT'] ?>
													</p>
												</td>
											</tr>
											<tr>
												<td style="padding-bottom: 43px;">
													<p style="margin: 0 0 15px 0;padding: 0;font-size: 15px;font-family: 'GothamPro', Arial, Helvetica, sans-serif;font-weight: 700;color: #333333;text-transform: uppercase">
														Пароль
													</p>
													<div><img style="display: block;" src="http://<?= SITE_SERVER_NAME ?>/upload/mail/images/line.png" /></div>
													<p style="margin: 15px 0 0 0;padding: 0;font-size: 15px;font-family: 'GothamPro', Arial, Helvetica, sans-serif;color: #333333;">
														<?= $arResult['FIELDS']['PASSWORD'] ?>
													</p>
												</td>
											</tr>
										</table>
									</td>
								</tr>
								<tr>
									<td>
										<a href="http://<?= SITE_SERVER_NAME ?>/personal/profile/" style="display: block;font-size: 17px;font-family: 'GothamPro', Arial, Helvetica, sans-serif;text-transform: uppercase;color: #ffffff;font-weight: 700;text-align: center;text-decoration: none;width: 100%;background-color: #7f7f7f;margin-top: 14px;margin-bottom: 61px;padding-top: 29px;padding-bottom: 30px;line-height: 1px;">
											Войти в личный кабинет
										</a>
									</td>
								</tr>

								<tr>
									<td style="padding-top: 57px">
										<p style="border-bottom: 3px solid #7f7f7f;margin: 0 0 26px 0;padding: 0 0 25px 0;font-size: 21px;font-family: 'GothamPro', Arial, Helvetica, sans-serif;font-weight: 700;color: #333333;text-transform: uppercase">
											Выставочная команда
										</p>
									</td>
								</tr>
								<? if (!empty($arParams['EVENT'])) { ?>
									<? foreach ($arResult['EVENT']['PROPERTIES']['LANG_CONTACTS_'.$language]['VALUE'] as $contact) { ?>
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
								<? } else { ?>
									<tr>
										<td>
											<p style="margin: 0 0 13px 0;padding: 0;font-size: 15px;font-family: 'GothamPro', Arial, Helvetica, sans-serif;font-weight: 700;color: #333333;text-transform: uppercase">
												Технический директор
											</p>
											<p style="margin: 0 0 7px 0;padding: 0;font-size: 15px;font-family: 'GothamPro', Arial, Helvetica, sans-serif;color: #333333;">
												Дубовицкий Сергей
											</p>
											<p style="margin: 0 0 7px 0;padding: 0;font-size: 15px;font-family: 'GothamPro', Arial, Helvetica, sans-serif;color: #333333;">
												<a href="mailto:sergey.dubovitskiy@businessmediarussia.ru" style="color: #333333;-webkit-text-size-adjust: none;text-decoration: underline;">
													sergey.dubovitskiy@businessmediarussia.ru
												</a>
											</p>
											<p style="margin: 0 0 43px 0;padding: 0;font-size: 15px;font-family: 'GothamPro', Arial, Helvetica, sans-serif;color: #333333;">
												+7 965 887 0909
											</p>
										</td>
									</tr>
								<? } ?>
								<tr>
									<td style="border-top: 1px solid #7f7f7f;">
										<a href="http://<?= SITE_SERVER_NAME ?>" style="text-decoration: none;display: block;padding-top: 60px;padding-bottom: 8px;">
											<img style="display: block;" src="http://<?= SITE_SERVER_NAME ?>/upload/mail/images/logo.png" alt="Universal exhibition configuration" />
										</a>
									</td>
								</tr>
							</table>
						</td>
					</tr>
					<tr>
						<td height="5" style="height: 5px;background: url('http://<?= SITE_SERVER_NAME ?>/upload/mail/images/bot.png');"></td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
</body>