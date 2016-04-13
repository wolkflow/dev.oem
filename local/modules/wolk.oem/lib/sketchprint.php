<?php

namespace Wolk\OEM;


\Bitrix\Main\Loader::includeModule('sale');


class SketchPrint
{
	const PATH = '/upload/sketches/';
	
	protected $order_id	= null;
	
	
	
	public function __construct($order_id)
	{
		$this->order_id = (int) $order_id;
	}
	
	
	/**
	 * Получение пути к файлу.
	 */
	public function getPath()
	{
		return (self::PATH.'sketch_'.$this->getOrderID().'.png');
	}
	
	
	/**
	 * Получение пути к PDF-файлу.
	 */
	public function getPathPDF()
	{
		return (self::PATH.'sketch_'.$this->getOrderID().'.pdf');
	}
	
	
	public function getOrderID()
	{
		return $this->order_id;
	}

	
	/**
	 * Получение счета.
	 */
	public function getSketchPrint()
	{
		if (is_readable($_SERVER['DOCUMENT_ROOT'].$this->getPath())) {
			return $this->getPath();
		}
		return null;
	}
	
	
	public function getURL($absolute = false)
	{
		$url = '/printsketch/'.$this->getOrderID().'/';
		
		if ($absolute) {
			$url = 'http://'.SITE_SERVER_NAME.$url;
		}
		return $url;
	}
	
	
	/**
	 * Печать документа.
	 */
	public function make($delay = 0)
	{		
		$site = \CSite::GetByID(SITE_DEFAULT)->Fetch();
		$url  = $this->getURL(true); 
		
		/*
		include_once($_SERVER['DOCUMENT_ROOT'].'/local/vendors/html2pdf-4.5.1/vendor/autoload.php');
		
		$html2pdf = new \HTML2PDF();
		$html2pdf->writeHTML($url);
	    $html2pdf->output($_SERVER['DOCUMENT_ROOT'].$this->getPathPDF(), 'F');
		*/
		
		// Аргументы.
		/*
		$args = array('-q' => '', '--no-stop-slow-scripts' => ''); // , '-g' => '', '-l' => '');
		
		if ($delay > 0) {
			$args['--javascript-delay'] = $delay;
		}
		array_walk($args, function(&$val, $key) { $val = $key.' '.$val; });
		
		// Команда для выполнения.
		$cmd = 'wkhtmltoimage '.implode(' ', $args).' '.$url.' '.$_SERVER['DOCUMENT_ROOT'].$this->getPath();
		
		*/
		
		$cmd = 'phantomjs '.$_SERVER['DOCUMENT_ROOT'].'/local/vendors/phantomjs/rasterize.js '.$url.' '.$_SERVER['DOCUMENT_ROOT'].$this->getPath();
		
		// echo $cmd;
		
		exec($cmd, $output, $result);
		
		/*
		$im = new \IMagick($_SERVER['DOCUMENT_ROOT'].$this->getPathPDF());
		$im->setImageFormat('png');
		$im->setSize(600, 600);
		$im->writeImage($_SERVER['DOCUMENT_ROOT'].$this->getPath());
		$im->clear();
		$im->destroy();
		*/
		
		return $result;
	}
}


