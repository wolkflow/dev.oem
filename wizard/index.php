<? require ($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php"); ?>
<? $APPLICATION->SetPageProperty("description", "OSEC"); ?>
<? $APPLICATION->SetTitle("Events"); ?>

<?  // Параметры запроса.
    $request = \Bitrix\Main\Context::getCurrent()->getRequest();
    
    $code = (string) $request->get('CODE');
    $step = (int)    $request->get('STEP');
    $type = mb_strtoupper((string) $request->get('TYPE'));
    $lang = mb_strtoupper(\Bitrix\Main\Context::getCurrent()->getLanguage());
    
    $width = $request->get('WIDTH');
    $depth = $request->get('DEPTH');
    $sform = $request->get('FORM');
    
    $eid  = Wolk\Core\Helpers\IBlockElement::getIDByCode(IBLOCK_EVENTS_ID, $code);
?>

<?  // Конструкторстенда.
    $APPLICATION->IncludeComponent(
        "wolk:wizard", 
        "wizard", 
        array(
            "EID"  => $eid,
            "CODE" => $code,
            "STEP" => $step,
            "TYPE" => $type,
            "LANG" => $lang,
            
            "WIDTH" => $width,
            "DEPTH" => $depth,
            "SFORM" => $sform,
        )
    );
?>

<? require ($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php"); ?>