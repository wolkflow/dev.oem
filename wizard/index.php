<? require ($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php"); ?>
<? $APPLICATION->SetPageProperty("description", "OSEC"); ?>
<? $APPLICATION->SetTitle("Events"); ?>

<?  // Параметры запроса.
    $request = \Bitrix\Main\Context::getCurrent()->getRequest();
    
    $eid  = (int)    $request->get('EID');
    $step = (int)    $request->get('STEP');
    $type = (string) $request->get('TYPE');
    $lang = mb_strtoupper(\Bitrix\Main\Context::getCurrent()->getLanguage());
    
    var_dump($eid, $step, $type, $lang);
?>

<?  // Конструкторстенда.
    $APPLICATION->IncludeComponent(
        "wolk:wizard", 
        "wizard", 
        array(
            "EID"  => $eid,
            "STEP" => $step,
            "TYPE" => $type,
            "LANG" => $lang,
        )
    );
?>

<? require ($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php"); ?>