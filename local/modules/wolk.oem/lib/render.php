<?php

namespace Wolk\OEM;

class Render
{
    const PATH_ROOT    = '/usr/render';
    const PATH_PLUGINS = '/usr/render/yafaray/bin/plugins/';
    
    const PATH_SCENES = '/upload/models/tmp';
    const PATH_MODELS = '/upload/models/xml';
    const PATH_IMAGES = '/upload/models/img';
    
    const DEFAULT_WIDTH    = 1280;
    const DEFAULT_HEIGHT   = 1024;
    const DEFAULT_DISTANCE = 2;
    
    
    /**
     * Рендер изображения.
     *
     * Пример:
     * java -jar oem-render.jar -i scene1.json -o out -r 1280x1024 -l -cr 30 -cd 1
     */
    public static function render($sid, $scene, $outfile, $width = 1280, $height = 1024, $distance = 1, $rotate = null)
    {
        if (empty($scene)) {
            return false;
        }
        
        $dirpath = self::PATH_IMAGES . '/' . strval($sid);
        $infile  = tempnam($_SERVER['DOCUMENT_ROOT'] . self::PATH_SCENES, 'scene-');
        $outfile = $dirpath . '/' . $outfile;
                 
        if (!is_writable($_SERVER['DOCUMENT_ROOT'] . $dirpath)) {
            mkdir($_SERVER['DOCUMENT_ROOT'] . $dirpath);
        }
        file_put_contents($infile, $scene);
        
        $command = sprintf(
            'cd %s && java -jar /usr/render/oem-render.jar -pp %s -i %s -o %s -r %sx%s -cd %s',
            self::PATH_ROOT,
            self::PATH_PLUGINS,
            $infile,
            $_SERVER['DOCUMENT_ROOT'] . $outfile,
            intval($width),
            intval($height),
            intval($distance)
        );
        
        if (!empty($rotate)) {
            $command .= ' -cr ' . intval($rotate);
        }
        exec($command, $output, $outcode);
        
        unlink($infile);
        
        if ($outcode == 0) {
            return ($outfile.'.png');
        }
        return false;
    }
}