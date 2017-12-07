<?php

namespace Wolk\OEM\Prints;


class Form
{
	const PATH = '/upload/orders/forms-handing/';
	const SDIR = '/print/order/form-handing/';
	
	protected $oid  = null;
	protected $bid  = null;
	protected $lang = null;
	
	
	
	public function __construct($oid, $bid, $lang = null)
	{
		$this->oid  = intval($oid);
		$this->bid  = intval($bid);
		$this->lang = strval($lang);
	}
	
	
	/**
	 * Получение ID заказа.
	 */
	public function getOrderID()
	{
		return $this->oid;
	}
	
	
	/**
	 * Получение ID корзины.
	 */
	public function getBasketID()
	{
		return $this->bid;
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
		return (self::PATH . 'order_' . $this->getOrderID() . '-' . $this->getBasketID() . '.pdf');
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
		$link = self::SDIR . $this->getOrderID() . '/' . $this->getBasketID() . '/' . $this->getLanguage() . '/';
		
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
		$args = array(
			'-q' => '', 
			'--no-stop-slow-scripts' => '',
			'--load-media-error-handling' => 'ignore',
			'--encoding' => 'UTF-8',
		);
		
		if ($delay > 0) {
			$args['--javascript-delay'] = $delay;
		}
		$path = $_SERVER['DOCUMENT_ROOT'] . $this->getPath();
		
		unlink($path);
		
		array_walk($args, function(&$val, $key) { $val = $key.' '.$val; });
		
		// Команда для выполнения.
		$cmd = 'wkhtmltopdf ' . implode(' ', $args) . ' ' . $url . ' ' . $path;
		
		exec($cmd, $output, $result);
		
		// return ($result === 0);
		
		return ($this->isExists());
	}
}


