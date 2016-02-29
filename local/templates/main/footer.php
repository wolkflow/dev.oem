<?if(\Bitrix\Main\Context::getCurrent()->getServer()->get('HTTP_X_REQUESTED_WITH') == 'XMLHttpRequest') {
    ?></div><?
    die;
}
use Bitrix\Main\Localization\Loc;
Loc::loadLanguageFile(__FILE__);?>
<div class="footersection">
    <a href="" data-modal="#contactUs" class="footersection__contact"><?=Loc::getMessage('Contact Us')?></a>
	<a href="" class="footersection__terms"><?=Loc::getMessage('Terms & Conditions')?></a>
	<a href="" data-modal="#generalInfo" class="footersection__information"><?=Loc::getMessage('General Information')?></a>
	<div class="footerLogo">
		<a href="/"><img src="/local/templates/.default/build/images/oem_logo.png" alt=""></a>
	</div>
</div>	
</div>
</div>



<div class="hide">

	<!-- Окно: Инфо -->
	<div class="modal modalContact" id="generalInfo">
		<div class="modalClose arcticmodal-close"></div>
		<div class="modalTitle">Fire Safety Rules</div>

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
				<div>
					<p class="ruleTitle">1. General information Data protection at Messe Frankfurt</p>
					<p>Visitors to the Messe Frankfurt Group internet portal can access many of the information offered there without registering. On subsequent pages, however, it is often necessary for you to enter company-related or personal data, so we can make an offer for the services required or send you information about our events. Naturally we are obligated to ensure that the data you have provided is given sufficient protection within our systems in accordance with the applicable laws. We believe you should be able to rely on your data being handled
						in a responsible and confidential way. Our data-protection concept also incorporates contractors who might need your data to carry out an order to your complete satisfaction.
						It is not our aim to use or pass on personal data without permission and for purposes other than those stated at the time the data was collected.<p>
					<p class="ruleTitle">2. Information about special internet applications Data protection
						in connection with orders</p>
					<p>Whenever content is accessed or requested from our website, we use cookies in order
						to optimise the communication times and to ensure that we are able to perform an anonymous statistical analysis of the utilisation of our website. If you do not wish cookies
						to be used, you can prevent this by adjusting your browser settings. Some of our web sites have links to the internet sites of other suppliers. In view of the fact that we have no responsibility for these sites, we recommend that you read the information about data protection at these sites in detail. Should you have any questions or comments about.<p>
				</div>
				<div>
					<p class="ruleTitle">3. General information Data protection at Messe Frankfurt</p>
					<p>Visitors to the Messe Frankfurt Group internet portal can access many of the information offered there without registering. On subsequent pages, however, it is often necessary for you to enter company-related or personal data, so we can make an offer for the services required or send you information about our events. Naturally we are obligated to ensure that the data you have provided is given sufficient protection within our systems in accordance with the applicable laws. We believe you should be able to rely on your data being handled
						in a responsible and confidential way. Our data-protection concept also incorporates contractors who might need your data to carry out an order to your complete satisfaction.
						It is not our aim to use or pass on personal data without permission and for purposes other than those stated at the time the data was collected.<p>
					<p class="ruleTitle">4. Information about special internet applications Data protection
						in connection with orders</p>
					<p>Whenever content is accessed or requested from our website, we use cookies in order
						to optimise the communication times and to ensure that we are able to perform an anonymous statistical analysis of the utilisation of our website. If you do not wish cookies
						to be used, you can prevent this by adjusting your browser settings. Some of our web sites have links to the internet sites of other suppliers. In view of the fact that we have no responsibility for these sites, we recommend that you read the information about data protection at these sites in detail. Should you have any questions or comments about.<p>
				</div>
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
					If you have troubles with submitting
					the form or if you want to hear our
					voices, do not hesitate to call
					or message us directly.
				</div>
				<div class="modalContacts">
					<ul class="modalContactsBlock">
						<li class="contactTitle">Technical Director</li>
						<li>Sergey Dubovitskiy</li>
						<li>P: +7 926 239-21-72</li>
						<li>E: <a href="mailto:sergey.dubovickiy@businessmediarussia.ru">sergey.dubovickiy@businessmediarussia.ru</a></li>
					</ul>
					<ul class="modalContactsBlock">
						<li class="contactTitle">Show Manager</li>
						<li>Marina Bolotskaya</li>
						<li>P: +7 926 239-28-58</li>
						<li>E: <a href="mailto:marina.bolotskaya@businessmediarussia.ru">marina.bolotskaya@businessmediarussia.ru</a></li>
					</ul>
					<ul class="modalContactsBlock">
						<li class="contactTitle">IT Manager</li>
						<li>Greg Saveliev</li>
						<li>P: +7 926 520-43-44</li>
						<li>E: <a href="mailto:greg.saveliev@businessmediarussia.ru">greg.saveliev@businessmediarussia.ru</a></li>
					</ul>
				</div>
			</div>
		</form>
	</div>
	<!--// .Окно: контакты -->
</div>
</body>
</html>