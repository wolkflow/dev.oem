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
							<table cellpadding="0" cellspacing="0" border="0" style="margin: 0;padding: 0;width: 100%">
								<tr>
									<td>
										<p style="margin: 0 0 34px 0;padding: 0;font-size: 15px;font-weight: 700;text-transform: uppercase;font-family: 'GothamPro', Arial, Helvetica, sans-serif;color: #333333;">
											<?= Loc::getMessage('dear', [], $arParams['LANG']) ?><?= $arResult['USER']['NAME'] ?><?= Loc::getMessage('goodday', [], $arParams['LANG']) ?>!
										</p>
										<p style="margin: 0 0 51px 0;padding: 0;font-size: 15px;font-family: 'GothamPro', Arial, Helvetica, sans-serif;line-height: 24px;color: #333333;">
											<?= Loc::getMessage('your_changes', [], $arParams['LANG']) ?> "<?= $arResult['EVENT']['NAME'] ?>"<?= Loc::getMessage('exhibition', [], $arParams['LANG']) ?>.
											<?= Loc::getMessage('saving', [], $arParams['LANG']) ?>
										</p>

										<? // Данные о компании // ?>
										<table cellpadding="0" cellspacing="0" border="0" style="margin: 0 0 115px 0;padding: 0; width: 100%;">
											<tr>
												<td>
													<p style="border-bottom: 3px solid #7f7f7f;margin: 0 0 26px 0;padding: 0 0 25px 0;font-size: 21px;font-family: 'GothamPro', Arial, Helvetica, sans-serif;font-weight: 700;color: #333333;text-transform: uppercase">
														<?= Loc::getMessage('company', [], $arParams['LANG']) ?>
													</p>
												</td>
											</tr>
											<tr>
												<td style="padding-bottom: 43px;">
													<p style="margin: 0 0 15px 0;padding: 0;font-size: 15px;font-family: 'GothamPro', Arial, Helvetica, sans-serif;font-weight: 700;color: #333333;text-transform: uppercase">
														<?= Loc::getMessage('title', [], $arParams['LANG']) ?>
													</p>
													<div><img style="display: block;" src="http://<?= $arResult['SERVER_NAME'] ?>/upload/mail/images/line.png" alt="___________________________________" /></div>
													<p style="margin: 15px 0 0 0;padding: 0;font-size: 15px;font-family: 'GothamPro', Arial, Helvetica, sans-serif;color: #333333;text-transform: uppercase">
														<?= $arResult['USER']['WORK_COMPANY'] ?>
													</p>
												</td>
											</tr>
											<tr>
												<td style="padding-bottom: 43px;">
													<p style="margin: 0 0 15px 0;padding: 0;font-size: 15px;font-family: 'GothamPro', Arial, Helvetica, sans-serif;font-weight: 700;color: #333333;text-transform: uppercase">
														<?= Loc::getMessage('standnum', [], $arParams['LANG']) ?>
													</p>
													<div><img style="display: block;" src="http://<?= $arResult['SERVER_NAME'] ?>/upload/mail/images/line.png" alt="___________________________________" /></div>
													<p style="margin: 15px 0 0 0;padding: 0;font-size: 15px;font-family: 'GothamPro', Arial, Helvetica, sans-serif;color: #333333;text-transform: uppercase">
														<?= $arResult['PROPS']['standNum']['VALUE'] ?>
													</p>
												</td>
											</tr>
											<tr>
												<td style="padding-bottom: 43px;">
													<p style="margin: 0 0 15px 0;padding: 0;font-size: 15px;font-family: 'GothamPro', Arial, Helvetica, sans-serif;font-weight: 700;color: #333333;text-transform: uppercase">
														<?= Loc::getMessage('pavillion', [], $arParams['LANG']) ?>
													</p>
													<div><img style="display: block;" src="http://<?= $arResult['SERVER_NAME'] ?>/upload/mail/images/line.png" alt="___________________________________" /></div>
													<p style="margin: 15px 0 0 0;padding: 0;font-size: 15px;font-family: 'GothamPro', Arial, Helvetica, sans-serif;color: #333333;text-transform: uppercase">
														<?= $arResult['PROPS']['pavillion']['VALUE'] ?>
													</p>
												</td>
											</tr>
											<tr>
												<td>
													<p style="margin: 0 0 15px 0;padding: 0;font-size: 15px;font-family: 'GothamPro', Arial, Helvetica, sans-serif;font-weight: 700;color: #333333;text-transform: uppercase">
														<?= Loc::getMessage('orderdate', [], $arParams['LANG']) ?>
													</p>
													<div><img style="display: block;" src="http://<?= $arResult['SERVER_NAME'] ?>/upload/mail/images/line.png" alt="___________________________________" /></div>
													<p style="margin: 15px 0 0 0;padding: 0;font-size: 15px;font-family: 'GothamPro', Arial, Helvetica, sans-serif;color: #333333;text-transform: uppercase">
														<?= date('d', strtotime($arResult['ORDER']['DATE_INSERT'])) ?>
														<?= TextHelper::i18nmonth(date('n', strtotime($arResult['ORDER']['DATE_INSERT'])), false) ?>
														<?= date('Y', strtotime($arResult['ORDER']['DATE_INSERT'])) ?>
													</p>
												</td>
											</tr>
										</table>

										<? // Список позиций // ?>
										<table cellpadding="0" cellspacing="0" border="0" style="margin: 0;padding: 0;width: 100%;">
											<tr>
												<td>
													<p style="border-bottom: 3px solid #7f7f7f;margin: 0 0 24px 0;padding: 0 0 25px 0;font-size: 21px;font-family: 'GothamPro', Arial, Helvetica, sans-serif;font-weight: 700;color: #333333;text-transform: uppercase">
														<?= Loc::getMessage('details', [], $arParams['LANG']) ?>
													</p>
												</td>
											</tr>
											<tr>
												<td style="padding-bottom: 55px;">
													<table cellpadding="0" cellspacing="0" border="0" style="margin: 0;width: 100%;">
														<? foreach ($arResult['BASKETS'] as $basket) { ?>
															<? if ($basket['PRICE'] <= 0) continue ?>
															<tr valign="middle">
																<td width="92" style="border-bottom: 1px solid #7f7f7f;padding: 6px 0 6px 0;">
																	<? if (!empty($basket['ITEM']['PREVIEW_PICTURE'])) { ?>
																		<img src="http://<?= $arResult['SERVER_NAME'] ?>/i.php?src=<?= $basket['ITEM']['IMAGE'] ?>&w=92&h=60"  style="display: block;" />
																	<? } else { ?>
																		<div style="width: 92px; height: 60px; border: 1px solid #dcdcdc; background-color: #f5f5f5; text-align: center !important; -webkit-border-radius: 4px; -moz-border-radius: 4px; border-radius: 4px; color: #adadad !important; font-size: 11px !important; line-height: 62px !important; display: inline-block;">Н
																			<?= Loc::getMessage('noimage') ?>
																		</div>
																	<? } ?>
																</td>
																<td style="border-bottom: 1px solid #7f7f7f;padding-left: 28px;padding-right: 20px;font-size: 17px;font-family: 'GothamPro', Arial, Helvetica, sans-serif;color: #333333;">
																	<?= $basket['NAME'] ?>
																</td>
																<td align="right" style="border-bottom: 1px solid #7f7f7f;font-weight: 700;font-size: 17px;font-family: 'GothamPro', Arial, Helvetica, sans-serif;color: #333333;">
																	<?= CurrencyFormat($basket['SUMMARY_PRICE'], $arResult['ORDER']['CURRENCY']) ?>
																</td>
															</tr>
														<? } ?>														
													</table>
												</td>
											</tr>
										</table>

									</td>
								</tr>
								
								<? // Суммы // ?>
								<tr>
									<td valign="middle" style="padding-top: 5px;">
										<p style="margin: 0 0 11px 0;padding: 0;font-size: 17px;font-family: 'GothamPro', Arial, Helvetica, sans-serif;font-weight: 700;color: #333333;text-transform: uppercase">
											<span style="display: inline-block;width: 165px;">
												<?= Loc::getMessage('price_total', [], $arParams['LANG']) ?>:
											</span>
											<?= CurrencyFormat($arResult['PRICES']['BASKET'], $arResult['ORDER']['CURRENCY']) ?>
										</p>
										<? if ($arResult['PRICES']['SURCHARGE'] > 0) { ?>
											<p style="margin: 0 0 11px 0;padding: 0;font-size: 17px;font-family: 'GothamPro', Arial, Helvetica, sans-serif;font-weight: 700;color: #ff3f25;text-transform: uppercase">
												<span style="display: inline-block;width: 165px;">
													<?= Loc::getMessage('price_surcharge', [], $arParams['LANG']) ?>:
												</span>
												<?= $arResult['PRICES']['SURCHARGE'] ?>%
												(<?= CurrencyFormat($arResult['PRICES']['SURCHARGE_PRICE'], $arResult['ORDER']['CURRENCY']) ?>)
											</p>
										<? } ?>
										<p style="margin: 0 0 11px 0;padding: 0;font-size: 17px;font-family: 'GothamPro', Arial, Helvetica, sans-serif;font-weight: 700;color: #333333;text-transform: uppercase">
											<span style="display: inline-block;width: 165px;">
												<?= Loc::getMessage('price_vat', [], $arParams['LANG']) ?>:
											</span>
											<?= CurrencyFormat($arResult['PRICES']['VAT'], $arResult['ORDER']['CURRENCY']) ?>
										</p>
										<? if ($arResult['PRICES']['SURCHARGE'] > 0) { ?>
											<p style="margin: 0 0 5px 0;padding: 0;font-size: 17px;font-family: 'GothamPro', Arial, Helvetica, sans-serif;font-weight: 700;color: #333333;text-transform: uppercase">
												<span style="display: inline-block;width: 165px;">
													<?= Loc::getMessage('price_total_vat', [], $arParams['LANG']) ?>:
												</span>
												<?= CurrencyFormat($arResult['PRICES']['TOTAL_WITH_VAT'], $arResult['ORDER']['CURRENCY']) ?>
											</p>
											<p style="margin: 22px 0 0 0;padding: 0;font-size: 17px;font-family: 'GothamPro', Arial, Helvetica, sans-serif;font-weight: 700;color: #333333;">
												<span style="display: block;float: left;text-transform: uppercase;width: 165px;line-height: 38px;">
													<?= Loc::getMessage('price_total_surcharge', [], $arParams['LANG']) ?>:
												</span>
												<span style="display: block;margin-left: 165px;font-size: 49px;line-height: 38px;font-weight: 400;">
													 <?= CurrencyFormat($arResult['PRICES']['TOTAL_WITH_SURCHARGE'], $arResult['ORDER']['CURRENCY']) ?>
												</span>
											</p>
										<? } else { ?>
											<p style="margin: 22px 0 0 0;padding: 0;font-size: 17px;font-family: 'GothamPro', Arial, Helvetica, sans-serif;font-weight: 700;color: #333333;">
												<span style="display: block;float: left;text-transform: uppercase;width: 165px;line-height: 38px;">
													<?= Loc::getMessage('price_total_vat', [], $arParams['LANG']) ?>:
												</span>
												<span style="display: block;margin-left: 165px;font-size: 49px;line-height: 38px;font-weight: 400;">
													 <?= CurrencyFormat($arResult['PRICES']['TOTAL_WITH_SURCHARGE'], $arResult['ORDER']['CURRENCY']) ?>
												</span>
											</p>
										<? } ?>
									</td>
								</tr>

								<tr>
									<td>
										<p style="border-bottom: 3px solid #7f7f7f;margin: 115px 0 30px 0;padding: 50px 0 25px 0;font-size: 21px;font-family: 'GothamPro', Arial, Helvetica, sans-serif;font-weight: 700;color: #333333;text-transform: uppercase">
											<?= Loc::getMessage('sketch', [], $arParams['LANG']) ?>
										</p>
										<img src="http://<?= $arResult['SERVER_NAME'] ?><?= CFile::GetPath($arResult['PROPS']['SKETCH_FILE']['VALUE_ORIG']) ?>" style="display: block;margin-bottom: 4px;max-width: 100%;" />
									</td>
								</tr>

								<tr>
									<td>
										<a href="http://<?= $arResult['SERVER_NAME'] ?>/personal/orders-history.php" style="display: block;font-size: 17px;font-family: 'GothamPro', Arial, Helvetica, sans-serif;text-transform: uppercase;color: #ffffff;font-weight: 700;text-align: center;text-decoration: none;width: 100%;background-color: #7f7f7f;margin-top: 56px;margin-bottom: 60px;padding-top: 29px;padding-bottom: 30px;line-height: 1px;">
											<?= Loc::getMessage('changeorder', [], $arParams['LANG']) ?>
										</a>
									</td>
								</tr>

								<tr>
									<td style="padding-top: 57px">
										<p style="border-bottom: 3px solid #7f7f7f;margin: 0 0 26px 0;padding: 0 0 25px 0;font-size: 21px;font-family: 'GothamPro', Arial, Helvetica, sans-serif;font-weight: 700;color: #333333;text-transform: uppercase">
											<?= Loc::getMessage('team', [], $arParams['LANG']) ?>
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
										<a href="http://<?= $arResult['SERVER_NAME'] ?>/" style="text-decoration: none;display: block;padding-top: 60px;padding-bottom: 8px;">
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