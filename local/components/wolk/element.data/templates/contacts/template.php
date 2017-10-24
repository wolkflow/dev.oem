<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die() ?>

<? $context  = \Bitrix\Main\Application::getInstance()->getContext(); ?>
<? $language = strtoupper($context->getLanguage()); ?>
<? $contacts = $arResult['ELEMENT']['PROPERTIES']['LANG_CONTACTS_'.$language]['VALUE'] ?>

<? if (!empty($contacts)) { ?>
	<? foreach ($contacts as $contact) { ?>
		<ul class="modalContactsBlock">
			<li class="contactTitle">
				<?= $contact['POST'] ?>
			</li>
			<li>
				<?= $contact['NAME'] ?>
			</li>
			<li>
				P: <?= $contact['PHONE'] ?>
			</li>
			<? if (!empty($contact['MOBILE'])) { ?>
				<li>
					M: <?= $contact['MOBILE'] ?>
				</li>
			<? } ?>
			<? if (!empty($contact['WHATSAPP'])) { ?>
				<li class="whatsapp">
					<?= $contact['WHATSAPP'] ?>
				</li>
			<? } ?>
			<li>
				E: <a href="mailto:<?= $contact['EMAIL'] ?>"><?= $contact['EMAIL'] ?></a>
			</li>
		</ul>
	<? } ?>
<? } ?>