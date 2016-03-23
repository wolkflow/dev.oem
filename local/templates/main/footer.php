<?if(\Bitrix\Main\Context::getCurrent()->getServer()->get('HTTP_X_REQUESTED_WITH') == 'XMLHttpRequest') {
    ?></div><?
    die;
}
use Bitrix\Main\Localization\Loc;
Loc::loadLanguageFile(__FILE__);
?>
	<div class="footersection">
		<a href="" data-modal="#contactUs" class="footersection__contact" id="js-contacts-link-id">
			<?= Loc::getMessage('Contact Us') ?>
		</a>
		<a href="" data-modal="#termsConditions" class="footersection__terms">
			<?= Loc::getMessage('Terms & Conditions') ?>
		</a>
		<a href="" data-modal="#generalInfo" class="footersection__information">
			<?= Loc::getMessage('General Information') ?>
		</a>
		<div class="footerLogo">
			<a href="/"><img src="/local/templates/.default/build/images/oem_logo.png"></a>
		</div>
	</div>	
</div>
</div>

<div class="hide">

	<!-- Окно: Условия -->
	<div class="modal modalContact" id="termsConditions">
		<div class="modalClose arcticmodal-close"></div>
		<div class="modalTitle"><?=Loc::getMessage('Terms & Conditions')?></div>

		<div class="modalContent">
			<div class="generalInfoContent">
				<? $APPLICATION->IncludeComponent('bitrix:main.include', '', [
					'AREA_FILE_SHOW' => 'file',
					'PATH' => SITE_TEMPLATE_PATH.'/lang/'.LANGUAGE_ID.'/include/terms_conditions.php',
					'EDIT_TEMPLATE' => 'html'
				]); ?>
			</div>
		</div>
	</div>
	<!--// .Окно: Условия -->

	<!-- Окно: Инфо -->
	<div class="modal modalContact" id="generalInfo">
		<div class="modalClose arcticmodal-close"></div>
		<div class="modalTitle"><?= Loc::getMessage('General Information') ?></div>

		<div class="modalContent">
			<!--
				Класс блока ниже generalInfoContent отвечает за инициализацию слайдера. Каждый входящий див - слайд.
				<div class="generalInfoContent">
					<div>Slide 1</div>
					<div>Slide 2</div>
					<div>Slide 3</div>
				</div>
			 -->

			<div class="generalInfoContent">
				<? $APPLICATION->IncludeComponent('bitrix:main.include', '', [
					'AREA_FILE_SHOW' => 'file',
					'PATH' => SITE_TEMPLATE_PATH.'/lang/'.LANGUAGE_ID.'/include/general_information.php',
					'EDIT_TEMPLATE' => 'html'
				]); ?>
			</div>
		</div>
	</div>
	<!--// .Окно: Инфо -->

	<!-- Окно: контакты -->
	<div class="modal modalContact" id="contactUs">
		<div class="modalClose arcticmodal-close"></div>
		<div class="modalTitle"><?=Loc::getMessage('Contact Us')?></div>
		
		<?	// Контакты.
			$APPLICATION->IncludeComponent(
				"wolk:form.mail",
				"contacts",
				array(
					"FORM" => "CONTACTS",
					"CAPTCHA" => "N",
					"FIELDS" => array("NAME", "PHONE", "COMPANY", "STAND", "EMAIL", "MESSAGE"),
					"REQUIRED" => array("NAME", "PHONE", "COMPANY", "STAND", "EMAIL", "MESSAGE"),
				)		
			);
		?>
		
		<div class="contactUs__right">
			<div class="modalMessage">
				<? $APPLICATION->IncludeComponent('bitrix:main.include', '', [
					'AREA_FILE_SHOW' => 'file',
					'PATH' => SITE_TEMPLATE_PATH.'/lang/'.LANGUAGE_ID.'/include/contacts/message.php',
					'EDIT_TEMPLATE' => 'html'
				]); ?>
			</div>
			<div class="modalContacts">
				<ul class="modalContactsBlock">
					<li class="contactTitle">Technical Director</li>
					<li>
						<? $APPLICATION->IncludeComponent('bitrix:main.include', '', [
							'AREA_FILE_SHOW' => 'file',
							'PATH' => SITE_TEMPLATE_PATH.'/lang/'.LANGUAGE_ID.'/include/contacts/director_name.php',
							'EDIT_TEMPLATE' => 'html'
						]); ?>
					</li>
					<li>P:
						<? $APPLICATION->IncludeComponent('bitrix:main.include', '', [
							'AREA_FILE_SHOW' => 'file',
							'PATH' => SITE_TEMPLATE_PATH.'/lang/'.LANGUAGE_ID.'/include/contacts/director_phone.php',
							'EDIT_TEMPLATE' => 'html'
						]); ?>
					</li>
					<li>E: 
						<? $APPLICATION->IncludeComponent('bitrix:main.include', '', [
							'AREA_FILE_SHOW' => 'file',
							'PATH' => SITE_TEMPLATE_PATH.'/lang/'.LANGUAGE_ID.'/include/contacts/director_email.php',
							'EDIT_TEMPLATE' => 'html'
						]); ?>
					</li>
				</ul>
				<ul class="modalContactsBlock">
					<li class="contactTitle">Show Manager</li>
					<li>
						<? $APPLICATION->IncludeComponent('bitrix:main.include', '', [
							'AREA_FILE_SHOW' => 'file',
							'PATH' => SITE_TEMPLATE_PATH.'/lang/'.LANGUAGE_ID.'/include/contacts/showmanager_name.php',
							'EDIT_TEMPLATE' => 'html'
						]); ?>
					</li>
					<li>P:
						<? $APPLICATION->IncludeComponent('bitrix:main.include', '', [
							'AREA_FILE_SHOW' => 'file',
							'PATH' => SITE_TEMPLATE_PATH.'/lang/'.LANGUAGE_ID.'/include/contacts/showmanager_phone.php',
							'EDIT_TEMPLATE' => 'html'
						]); ?>
					</li>
					<li>E: 
						<? $APPLICATION->IncludeComponent('bitrix:main.include', '', [
							'AREA_FILE_SHOW' => 'file',
							'PATH' => SITE_TEMPLATE_PATH.'/lang/'.LANGUAGE_ID.'/include/contacts/showmanager_email.php',
							'EDIT_TEMPLATE' => 'html'
						]); ?>
					</li>
				</ul>
				<ul class="modalContactsBlock">
					<li class="contactTitle">IT Manager</li>
					<li>
						<? $APPLICATION->IncludeComponent('bitrix:main.include', '', [
							'AREA_FILE_SHOW' => 'file',
							'PATH' => SITE_TEMPLATE_PATH.'/lang/'.LANGUAGE_ID.'/include/contacts/itmanager_name.php',
							'EDIT_TEMPLATE' => 'html'
						]); ?>
					</li>
					<li>P: 
						<? $APPLICATION->IncludeComponent('bitrix:main.include', '', [
							'AREA_FILE_SHOW' => 'file',
							'PATH' => SITE_TEMPLATE_PATH.'/lang/'.LANGUAGE_ID.'/include/contacts/itmanager_phone.php',
							'EDIT_TEMPLATE' => 'html'
						]); ?>
					</li>
					<li>E: 
						<? $APPLICATION->IncludeComponent('bitrix:main.include', '', [
							'AREA_FILE_SHOW' => 'file',
							'PATH' => SITE_TEMPLATE_PATH.'/lang/'.LANGUAGE_ID.'/include/contacts/itmanager_email.php',
							'EDIT_TEMPLATE' => 'html'
						]); ?>
					</li>
				</ul>
			</div>
		</div>
		
	</div>
	<!--// .Окно: контакты -->
</div>
</body>
</html>