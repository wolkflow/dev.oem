<? require ($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php"); ?>
<? $APPLICATION->SetPageProperty("description", "OSEC"); ?>
<? $APPLICATION->SetTitle("Events"); ?>

<?  // Параметры запроса.
    $request = \Bitrix\Main\Context::getCurrent()->getRequest();
    
    $code = (string) $request->get('CODE');
    $step = (int)    $request->get('STEP');
    //$type = mb_strtoupper((string) $request->get('TYPE'));
    $lang = mb_strtoupper(\Bitrix\Main\Context::getCurrent()->getLanguage());
    
    //$width = str_replace(',', '.', $request->get('WIDTH'));
    //$depth = str_replace(',', '.', $request->get('DEPTH'));
    //$sform = $request->get('SFORM');
    
    //$eid  = 
	
	$oid = $request->get('OID');
?>

<?  // Конструктор стенда.
    $APPLICATION->IncludeComponent(
        "wolk:wizard", 
        "wizard", 
        array(
			"OID"  => $oid,
		
            //"EID"  => $eid,
            "CODE" => $code,
            "STEP" => $step,
            //"TYPE" => $type,
            "LANG" => $lang,
            
            //"WIDTH" => $width,
            //"DEPTH" => $depth,
            //"SFORM" => $sform,
        )
    );
?>

<? require ($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php"); ?>