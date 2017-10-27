<?php

class Helper
{
    static function includeFile($file, $path = false)
    {
        if (!$path) {
            $dir = $_SERVER['DOCUMENT_ROOT'] . '/include/';
            $includeDir = SITE_DIR . "include/";
        } else {
            $dir = $_SERVER['DOCUMENT_ROOT'] . '/' . $path . '/';
            $includeDir = SITE_DIR . $path . "/";
        }

        if (!file_exists($dir . $file . ".php")) {
            $newFile = fopen($dir . $file . ".php", 'w');
            fclose($newFile);
        } else {
            $GLOBALS['APPLICATION']->IncludeFile(
                $includeDir . $file . ".php",
                Array(),
                Array("MODE" => "html")
            );
        }
    }
}