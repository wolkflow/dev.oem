<?php

namespace Wolk\OEM\Prints;


class Form
{
	const PATH = '/upload/orders/forms-handing/';
	
	
	protected $oid  = null;
	protected $lang = null;
	
	
	public function __construct($oid, $lang = null)
	{
		$this->oid  = (int) $oid;
		$this->lang = (string) $lang;
	}
	
	
	/**
	 * Получение ID заказа.
	 */
	public function getOrderID()
	{
		return $this->oid;
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
		return (self::PATH . 'order_'.$this->getOrderID() . '.pdf');
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
		$url  = '/print/order/form-handing/' . $this->getOrderID() . '/' . $this->getLanguage() . '/';
		
		if ($absolute) {
			$url = 'http://' . $site['SERVER_NAME'] . $url;
		}
		return $url;
	}
	
	
	/**
	 * Печать документа.
	 */
	public function make($delay = 0)
	{		
		$url = $this->getURL(true); 
		
		// Аргументы.
		$args = array('-q' => '', '--no-stop-slow-scripts' => '');
		
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


