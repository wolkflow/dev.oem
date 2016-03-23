<?php

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

$arComponentParameters = array(
	"PARAMETERS" => array(
		"FORM" => array(
			"PARENT" 	=> "BASE",
			"NAME" 		=> GetMessage("FORM_MAIL_PARAM_FORM"),
			"TYPE" 		=> "STRING",
			"DEFAULT" 	=> "FEEDBACK",
		),
		"FIELDS" => array(
			"PARENT" 			=> "BASE",
			"NAME" 				=> GetMessage("FORM_MAIL_PARAM_FIELDS"),
			"TYPE" 				=> "LIST",
			"ADDITIONAL_VALUES" => "Y",
		),
		"REQUIRED" => array(
			"PARENT" 			=> "BASE",
			"NAME" 				=> GetMessage("FORM_MAIL_PARAM_REQUIRED"),
			"TYPE" 				=> "LIST",
			"ADDITIONAL_VALUES" => "Y",
		),
		"CAPTCHA" => array(
			"PARENT" 	=> "BASE",
			"NAME" 		=> GetMessage("FORM_MAIL_PARAM_CAPTCHA"),
			"TYPE" 		=> "CHECKBOX",
			"DEFAULT" 	=> "N",
		),
	),
);