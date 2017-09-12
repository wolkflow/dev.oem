<?php

namespace Wolk\Core\Utils;

class File
{

    /**
     * Получение реструктурированного массива $_FILES.
     */
    public static function getReStructUploadFiles()
    {
        $result = array();
        foreach ($_FILES as $key => $descrs) {
            foreach ($descrs as $param => $value) {
                self::reStructUploadFiles($result, $key, $_FILES[$key][$param], $param);
            }
        }
        return $result;
    }
    
    protected static function reStructUploadFiles(&$data, $key, $value, $param)
    {
        if (is_array($value)) {
            foreach ($value as $code => $val) {
                self::reStructUploadFiles($data[$key], $code, $val, $param);
            }
        } else {
            $data[$key][$param] = $value;
        }
    }
}