<?php

namespace Wolk\Core\System\Migrations;

/**
 * Class FileAccess
 * @package Linemedia\Carsale\Migrations
 */
class FileAccess extends UpdateTool
{
    /**
     * @param string $path
     * @param array $permissions
     * @return bool
     */
    public function update($path = '', $permissions = [])
    {
        $original_path = $path;

        \CMain::InitPathVars($site, $path);
        $document_root = \CSite::GetSiteDocRoot($site);

        $path = rtrim($path, "/");

        if (strlen($path) <= 0) {
            $path = "/";
        }

        if (($position = strrpos($path, "/")) !== false) {
            $path_file = substr($path, $position + 1);
            $path_dir = substr($path, 0, $position);
        } else {
            return false;
        }

        if ($path_file == "" && $path_dir == "") {
            $path_file = "/";
        }

        $PERM = [];
        if (file_exists($document_root . $path_dir . "/.access.php")) {
            //include replaced with eval in order to honor of ZendServer
            eval("?>" . file_get_contents($document_root . $path_dir . "/.access.php"));
        }

        if (!isset($PERM[$path_file]) || !is_array($PERM[$path_file])) {
            $new_permissions = $permissions;
        } else {
            $new_permissions = $permissions + $PERM[$path_file];
        }

        if (!empty($new_permissions)) {
            global $APPLICATION;
            return $APPLICATION->SetFileAccessPermission(
                [
                    $site,
                    $path
                ],
                $new_permissions
            );
        }

        return true;
    }
}