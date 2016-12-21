<?if(\Bitrix\Main\Context::getCurrent()->getServer()->get('HTTP_X_REQUESTED_WITH') == 'XMLHttpRequest') {
    ?></div><?
    die;
}
use Bitrix\Main\Localization\Loc;
Loc::loadLanguageFile(__FILE__);

$curlang = \Bitrix\Main\Context::getCurrent()->getLanguage();

?>
	<div class="footersection customizable_border">

		<div class="footerLinks">
			<a href="javascript:void(0)" data-modal="#contact-us" class="footersection__contact" id="js-contacts-link-id">
				<?= Loc::getMessage('Contact Us') ?>
			</a>
			
            <a href="javascript:void(0)" data-modal="#general-info" class="footersection__information">
				<?= Loc::getMessage('General Information') ?>
			</a>
            &nbsp;
            <a href="javascript:void(0)" data-modal="#rules" class="footersection__information">
				<?= Loc::getMessage('rules') ?>
			</a>
		</div>
		<? /*
		<a href="" data-modal="#termsConditions" class="footersection__terms">
			<?= Loc::getMessage('Terms & Conditions') ?>
		</a>
		<a href="" data-modal="#generalInfo" class="footersection__information">
			<?= Loc::getMessage('General Information') ?>
		</a>
		*/ ?>
		
		<div class="footerLogo">
			<a href="/"><img src="/local/templates/.default/build/images/logo-sm.png"></a>
		</div>
	</div>	
</div>
</div>

<div class="hide">
	
	<div class="modal modalContact" id="general-info">
		<div class="modalClose arcticmodal-close"></div>
		<div class="windowNavigate"></div>
		<div class="modalContent">
			<? $APPLICATION->IncludeComponent('wolk:element.data', 'documents', ['IBLOCK_ID' => EVENTS_IBLOCK_ID]); ?>
		</div>
	</div>
	
	<? /*
	<!-- Окно: Условия -->
	<div class="modal modalContact" id="termsConditions">
		<div class="modalClose arcticmodal-close"></div>
		<div class="modalTitle"><?= Loc::getMessage('Terms & Conditions') ?></div>

		<div class="modalContent">
			<div class="generalInfoContent">
				<? $APPLICATION->IncludeComponent('bitrix:main.include', '', [
					'AREA_FILE_SHOW' => 'file',
					'PATH' => SITE_TEMPLATE_PATH.'/lang/'.$curlang.'/include/terms_conditions.php',
					'EDIT_TEMPLATE' => 'html'
				]); ?>
			</div>
		</div>
	</div>
	

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
					'PATH' => SITE_TEMPLATE_PATH.'/lang/'.$curlang.'/include/general_information.php',
					'EDIT_TEMPLATE' => 'html'
				]); ?>
			</div>
		</div>
	</div>
	*/ ?>
	
    
    <!-- Окно: правила -->
	<div class="modal modalContact" id="rules">
		<div class="modalClose arcticmodal-close"></div>
		<div class="modalTitle">
			<?= Loc::getMessage('rules') ?>
		</div>
		<div class="modalContent">
            <div class="generalInfoContent">
                <? $APPLICATION->IncludeComponent('bitrix:main.include', '', [
                    'AREA_FILE_SHOW' => 'file',
                    'PATH' => SITE_TEMPLATE_PATH.'/lang/'.$curlang.'/include/rules.php',
                    'EDIT_TEMPLATE' => 'html'
                ]); ?>
            </div>
        </div>
	</div>
    

	<!-- Окно: контакты -->
	<div class="modal modalContact" id="contact-us">
		<div class="modalClose arcticmodal-close"></div>
		<div class="modalTitle">
			<?= Loc::getMessage('Contact Us') ?>
		</div>
		
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
					'PATH' => SITE_TEMPLATE_PATH.'/lang/'.$curlang.'/include/contacts/message.php',
					'EDIT_TEMPLATE' => 'html'
				]); ?>
			</div>
			<div class="modalContacts">
				<? $APPLICATION->IncludeComponent('wolk:element.data', 'contacts', ['IBLOCK_ID' => EVENTS_IBLOCK_ID]); ?>
			</div>
		</div>
	</div>
	
</div>
</body>
</html>