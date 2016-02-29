<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetTitle("rules");
?>With this fixed up, head back to the browser and refresh. Cool! It's the same page, but now it has a full html source. Bonus time! Once you have a full html page, the web debug toolbar makes an appearance. This is a killer feature in Symfony: it includes information about which route was matched, which controller was executed, how fast the page loaded, who is logged in and more.

You can also click any of the icons to get even more detailed information in the profiler, including this amazing timeline that shows you exactly how long each part of your application took to render. This is amazing for debugging and profiling. There's also details in here on Twig, security, routes and other cool stuff. We'll keep exploring this as we go along.<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>