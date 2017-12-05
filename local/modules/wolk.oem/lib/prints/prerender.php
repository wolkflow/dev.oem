<?php

namespace Wolk\OEM\Prints;


use \Wolk\OEM\Basket;


class Prerender
{
	const PATH = '/upload/orders/prerenders/';
	const PDIR = '/print/basket/render/';
	
	
	protected $stid = null;
	protected $code = null;
	protected $lang = null;
	
	
	public function __construct($stid, $code, $lang = null)
	{
		$this->stid = intval($stid);
		$this->code = strval($code);
		$this->lang = strval($lang);
		
		if (empty($this->lang)) {
			$this->lang = \Bitrix\Main\Application::getInstance()->getContext()->getLanguage();	
		}
	}
	
	
	/**
	 * Получение ID хранилища.
	 */
	public function getStorageID()
	{
		return $this->stid;
	}
	
	
	/**
	 * Получение кода.
	 */
	public function getCode()
	{
		return $this->code;
	}
	
	
	/**
	 * Получение языка.
	 */
	public function getLanguage()
	{
		return $this->lang;
	}
	
	
	/**
	 * Проверка наличия файла PDF.
	 */
	public function isExists()
	{
		return ($this->getPathPDF() !== null);
	}
	
	
	/**
	 * Получение пути к файлу.
	 */
	public function getPath()
	{
		return (self::PATH . 'basket_' . $this->getStorageID() . '-' . strtolower($this->getCode()) . '.pdf');
	}
	
	
	/**
	 * Получение счета.
	 */
	public function getPathPDF()
	{
		if (is_readable($_SERVER['DOCUMENT_ROOT'] . $this->getPath())) {
			return $this->getPath();
		}
		return null;
	}
	
	
	/**
	 * Получение ссылки на формирование документа.
	 */
	public function getURL($absolute = false)
	{
		$site = \CSite::GetByID(SITE_DEFAULT)->fetch();
		$link  = self::PDIR . $this->getStorageID() . '/' . $this->getCode() . '/' . $this->getLanguage() . '/';
		
		if ($absolute) {
			$link = 'http://' . $site['SERVER_NAME'] . $link;
		}
		return $link;
	}
	
	
	/**
	 * Печать документа.
	 */
	public function make($delay = 0)
	{		
		$url = $this->getURL(true); 
		
		// Аргументы.
		$args = array('-q' => ''); // , '--no-stop-slow-scripts' => '');
		
		if ($delay > 0) {
			$args['--javascript-delay'] = $delay;
		}
		$path = $_SERVER['DOCUMENT_ROOT'] . $this->getPath();
		
		unlink($path);
		
		array_walk($args, function(&$val, $key) { $val = $key.' '.$val; });
		
		// Команда для выполнения.
		$cmd = 'wkhtmltopdf ' . implode(' ', $args) . ' ' . $url . ' ' . $path;
		
		exec($cmd, $output, $result);
		
		return $result;
	}
}


