<?php

use \Bitrix\Main\Localization\Loc;

require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin_before.php');


Loc::loadMessages(__FILE__);


// jQuery
$APPLICATION->AddHeadScript('https://yastatic.net/jquery/2.1.3/jquery.min.js');

// Bootstrap.
$APPLICATION->SetAdditionalCSS('https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/css/bootstrap.min.css');
$APPLICATION->AddHeadScript('https://maxcdn.bootstrapcdn.com/bootstrap/3.3.1/js/bootstrap.min.js');


$APPLICATION->SetTitle('Список поставщиков');

require ($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin_after.php');

?>
<style>
	* {
		-webkit-box-sizing: content-box !important;
		-moz-box-sizing: content-box !important;
		box-sizing: content-box !important;
	}
</style>
<div class="row">
	<div class="col-md-10 col-xs-offset-1">
		<div class="panel panel-info">
			<div class="panel-heading">
				<b>Проверка системы</b>
			</div>
			<div class="panel-body">
				<table class="table table-bordered">
					<tr>
						<td>Установлен модуль JSON</td>
						<td>
							<?= (extension_loaded('json')) ? ('Да') : ('Нет') ?>
						</td>
					</tr>
					<tr>
						<td>Установлен модуль SOAP</td>
						<td>
							<?= (extension_loaded('soap')) ? ('Да') : ('Нет') ?>
						</td>
					</tr>
				</table>
			</div>
		</div>		
	</div>
</div>


<? require ($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/epilog_admin.php') ?>