<?php

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$arComponentDescription = array(
	"NAME" 			=> GetMessage("DW_FORM_MAIL_TEMPLATE_NAME"),
	"DESCRIPTION" 	=> GetMessage("DW_FORM_MAIL_TEMPLATE_DESCRIPTION"),
	"ICON" 			=> "/images/form.gif",
	"CACHE_PATH" 	=> "Y",
	"SORT" 			=> 100,
	"PATH" 			=> array(
		"ID" 		=> "content",
		"CHILD" 	=> array(
			"ID" 	=> "mail",
			"NAME" 	=> GetMessage("DW_MAIL_DESC"),
			"SORT" 	=> 100
		),
	),
);
