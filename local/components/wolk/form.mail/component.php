<?php 

// Идентификатор формы. 
$arParams['FORM'] = (string) $arParams['FORM']; 

// Поля формы. 
$arParams['FIELDS'] = (array) $arParams['FIELDS']; 

// Обязательные поля формы. 
$arParams['REQUIRED'] = (array) $arParams['REQUIRED']; 

// Использовать CAPTCHA. 
$arParams['CAPTCHA'] = ($arParams['CAPTCHA'] == "Y"); 


$arResult['ERRORS']  = array(); 
$arResult['MESSAGE'] = null; 
$arResult['DATA']    = array(); 

/* 
 * Отправка формы. 
 */ 
if (!empty($_POST) && isset($_REQUEST[$arParams['FORM']])) { 
     
    $arResult['FIELDS'] = array(); 
     
    foreach ($arParams['FIELDS'] as $field) {
    	if (is_array($_POST[$field])) {
    		$value = array_map('strval', $_POST[$field]);
    	} else {
    		$value = (string) $_POST[$field];
    	}
        
        if (in_array($field, $arParams['REQUIRED']) && empty($value)) { 
            $arResult['ERRORS'][$field] = GetMessage('DW_FORM_MAIL_ERROR_EMPTY_REQUIRED');
        } else { 
        	if (is_array($value)) {
        		$fval = implode(PHP_EOL, $value);
        	} else {
        		$fval = $value;
        	}
            $arResult['FIELDS'][$field] = $fval;
        }
        
        $arResult['DATA'][$field] = $value;
    }
     
    // Проверка CAPTCHA. 
    if ($arParams['CAPTCHA']) {
        if (!$APPLICATION->CaptchaCheckCode($_POST['CAPTCHA_WORD'], $_POST['CAPTCHA_CODE'])) { 
            $arResult['ERRORS']['CAPTCHA'] = GetMessage('DW_FORM_MAIL_ERROR_EMPTY_CAPTCHA'); 
        } 
    } 
     
    // Событие. 
    foreach (GetModuleEvents('dwai', 'OnBeforeFormMailSend', true) as $arEvent) { 
        ExecuteModuleEventEx($arEvent, array(&$arParams, &$arResult)); 
    } 
    
    // Отправка сообщения. 
    if (empty($arResult['ERRORS'])) {
        if (CEvent::Send($arParams['FORM'], SITE_ID, $arResult['FIELDS'])) { 
        	$arResult['SUCCESS'] = true;
            $arResult['MESSAGE'] = GetMessage('DW_FORM_MAIL_SUCCESS_MAIL_SEND');
            $arResult['DATA'] = array(); 
        } 
    } else {
    	$arResult['SUCCESS'] = false;
    }
     
    // Событие. 
    foreach (GetModuleEvents('dwai', 'OnAfterFormMailSend', true) as $arEvent) { 
        ExecuteModuleEventEx($arEvent, array($arParams, $arResult)); 
    }
} 

// CAPTCHA. 
if ($arParams['CAPTCHA']) { 
    include_once ($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/classes/general/captcha.php'); 
    $cpt = new CCaptcha();
    $captchaPass = COption::GetOptionString("main", "captcha_password", "");
    if (strlen($captchaPass) <= 0) {
        $captchaPass = randString(10);
        COption::SetOptionString("main", "captcha_password", $captchaPass); 
    }
    $cpt->SetCodeCrypt($captchaPass);
    
    $arResult['CAPTCHA'] = $cpt;
} 

// Событие. 
foreach (GetModuleEvents('dwai', 'OnFormMailShow', true) as $arEvent) {
    ExecuteModuleEventEx($arEvent, array(&$arParams, &$arResult));
}

$this->IncludeComponentTemplate();

return $arResult;
