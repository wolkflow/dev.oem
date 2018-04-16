<?php

namespace Wolk\OEM;


class Render
{
    const PATH_ROOT    = '/usr/render';
    const PATH_PLUGINS = '/usr/render/yafaray/bin/plugins/';
    
    const PATH_SCENES = '/upload/models/tmp';
    const PATH_MODELS = '/upload/models/xml';
    const PATH_IMAGES = '/upload/models/img';
    
    const DEFAULT_WIDTH    = 800; // 1280;
    const DEFAULT_HEIGHT   = 680; // 1024;
    const DEFAULT_DISTANCE = 2;
	
	
	
	/**
	 * Генерация стенда из заказа.
	 */
	public static function order(Order $order)
	{
		// Данные заказа.
		$data = $order->getData();
		
		// Данные скетча.
		$sketch = $order->getSketch();
		if (!is_object($sketch)) {
			return false;
		}
		
		// Данные сцены.
		$jscene = $sketch->getScene();
		if (empty($jscene)) {
			return false;
		}
		
		$jscene = json_decode($jscene, true);
		
		// Стенд.
		$stand = null;
		
		// Корзины.
		$baskets = [];
		foreach ($order->getBaskets() as $basket) {
			if ($basket['PROPS']['STAND']['VALUE'] == 'Y') {
				$stand = $basket;
				continue;
			}
			if (empty($basket['PROPS']['BID']['VALUE'])) { 
				continue;
			}
			$baskets[$basket['PROPS']['BID']['VALUE']] = $basket;
		}
		
		if (empty($stand)) {
			return false;
		}
		
		// Углы поворота.
        $rotates = array(0, 30, 90, 120);
		
		// Код мероприятия.s
		$code = $data['PROPS']['EVENT_CODE']['VALUE'];
		
		// Объекты на сцене.
		$objects = $jscene['objects'];
		
		foreach ($objects as &$object) {
			$pid = $baskets[$object['id']]['PRODUCT_ID'];
			
			if ($pid <= 0) {
				continue;
			}
			$product = new \Wolk\OEM\Products\Base($pid);
			
			$object['path'] = $product->getModelPath();
		}
		
		// Надпись на фризовой панели.
		$owner = '';
		
		// Корзины с продукцией "надпись на фриз".
		$fascias = (array) $order->getFasciaBaskets();
		if (!empty($fascias)) {
			foreach ($fascias as $fascia) {
				$params = json_decode($fascia['PROPS']['PARAMS']['VALUE'], true);
				
				if (!empty($params['TEXT'])) {
					$owner = $params['TEXT'];
					break;
				}
			}
		}
		
		// Сцена для рендера.
		$scene = [
            'width'      => $data['PROPS']['WIDTH']['VALUE'],
            'length'     => $data['PROPS']['DEPTH']['VALUE'],
            'type'       => $data['PROPS']['SFORM']['VALUE'],
            'owner_name' => $owner,
            'objects'    => $objects,
        ];
        
		// Установка нужной дистанции камеры, в зависимости от размеров стенда.
        $distance = 1;
        if ($params['WIDTH'] > 3 && $params['DEPTH'] > 3) {
            $distance = 2;
        }
		
		$paths = array();
		foreach ($rotates as $rotate) {
			
			// Рендер сцены.
			$paths []= \Wolk\OEM\Render::render(
				$order->getID() . '-' . $rotate, 
				json_encode($scene), 
				'out-'.uniqid(), 
				self::DEFAULT_WIDTH, 
				self::DEFAULT_HEIGHT, 
				$distance, 
				$rotate
			);
		}
		return $paths;
	}
    
    
    /**
     * Рендер изображения.
     *
     * Пример:
     * java -jar oem-render.jar -i scene1.json -o out -r 1280x1024 -l -cr 30 -cd 1
     */
    public static function render($sid, $scene, $outfile, $width = 800, $height = 680, $distance = 1, $rotate = null)
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
		
		file_put_contents($_SERVER['DOCUMENT_ROOT'].'/upload/models/xml/check.log', 'SCENE:' . $scene . PHP_EOL, FILE_APPEND);
		file_put_contents($_SERVER['DOCUMENT_ROOT'].'/upload/models/xml/check.log', 'RENDER:' . $command . PHP_EOL, FILE_APPEND);
        
        if (!empty($rotate)) {
            $command .= ' -cr ' . intval($rotate);
        }
        exec($command, $output, $outcode);
        
        //unlink($infile);
        
        if ($outcode == 0) {
            return ($outfile.'.png');
        }
        return false;
    }
	
	
	
	/**
     * Конвертирование изображения.
     *
     * Пример:
     * java -jar /usr/render/xml-tool.jar -i ./<code>.obj -o <code>
     */
    public static function convert($pid, $data)
    {
		$pid  = (int) $pid;
		$data = (array) $data;
		$code = str_replace('.zip', '', $data['name']);
				
		$piddir = $_SERVER['DOCUMENT_ROOT'] . self::PATH_MODELS . '/' . $pid;
		if (!is_dir($piddir)) {
			if (!mkdir($piddir, 0755, true)) {
				// throw exception;
			}
		}
		
		// application/x-zip-compressed
		
		// Очистка фалов в директоии.
		if (!empty($piddir)) {
			$files = glob($piddir . '/*');
			foreach ($files as $file) {
				if (is_file($file)) {
					unlink($file);
				}
			}
		}
		
		// Пути к файл.
		$zippath = $piddir . '/' . $data['name'];
		$objpath = $piddir . '/' . $code . '.obj';
		
		
		// Перенос файла в директорию.
		rename($data['tmp_name'], $zippath);
		
		// Распаковка архива.
		exec('unzip -qq -o ' . $zippath . ' -d ' . $piddir, $output, $outcode);
		
		if ($outcode === 0) {
			unlink($zippath);
			
			// Конвертация.
			$command = sprintf(
				'java -jar /usr/render/xml-tool.jar -i %s -o %s',
				$objpath,
				$piddir
			);
			
			file_put_contents($_SERVER['DOCUMENT_ROOT'].'/upload/models/xml/check.log', $command . PHP_EOL, FILE_APPEND);
			
			exec($command);
		} else {
			file_put_contents($_SERVER['DOCUMENT_ROOT'].'/upload/models/xml/check.log', 'ZIP: ' . $outcode . ' ' . print_r($output, true) . PHP_EOL, FILE_APPEND);
			file_put_contents($_SERVER['DOCUMENT_ROOT'].'/upload/models/xml/check.log', print_r($data, true) . PHP_EOL, FILE_APPEND);
		}
    }
	
	
	
	/**
     * Удаление изображения.
     *
     * Пример:
     * java -jar /usr/render/xml-tool.jar -i ./<code>.obj -o <code>
     */
    public static function remove($pid)
    {
		$piddir = $_SERVER['DOCUMENT_ROOT'] . self::PATH_MODELS . '/' . intval($pid);
		if (!is_dir($piddir)) {
			return;
		}
		
		// Очистка фалов в директоии.
		if (!empty($piddir)) {
			$files = glob($piddir . '/*');
			foreach ($files as $file) {
				if (is_file($file)) {
					unlink($file);
				}
			}
		}
	}
}
