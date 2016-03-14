<?if(\Bitrix\Main\Context::getCurrent()->getServer()->get('HTTP_X_REQUESTED_WITH') == 'XMLHttpRequest') {
    ?></div><?
    die;
}
use Bitrix\Main\Localization\Loc;
Loc::loadLanguageFile(__FILE__);
?>
	<div class="footersection">
		<a href="" data-modal="#contactUs" class="footersection__contact">
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
		<form>
			<div class="contactUs__left">
				<div class="formRow">
					<div class="formCol">
						<label for="uName">Name</label>
						<input type="text" class="styler" id="uName">
					</div>
					<div class="formCol">
						<label for="uPhone">Phone</label>
						<input type="text" class="styler" id="uPhone">
					</div>
				</div>
				<div class="formRow">
					<div class="formCol">
						<label for="ucName">company name</label>
						<input type="text" class="styler" id="ucName">
					</div>
					<div class="formCol">
						<label for="standNum">stand №</label>
						<input type="text" class="styler" id="standNum">
					</div>
				</div>
				<div class="formRow">
					<label for="uMail">email address</label>
					<input type="text" id="uMail" class="styler">
				</div>
				<div class="formRow">
					<label for="uMessage">message</label>
					<textarea id="uMessage"></textarea>
				</div>
				<div class="formRow">
					<p class="formNote">* All fields must be filled out</p>
				</div>
				<div class="formRow">
					<input type="button" class="styler modalSend" value="Send">
				</div>
			</div>
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
		</form>
	</div>
	<!--// .Окно: контакты -->
</div>
</body>
</html>