<?php

namespace Wolk\OEM\Prints;


use \Wolk\OEM\Basket;


class Prerender
{
	const PATH = '/upload/orders/prerenders/';
	const PDIR = '/print/basket/render/';
	
	
	protected $basket = null;
	protected $lang   = null;
	
	
	public function __construct(Basket $basket, $lang = null)
	{
		$this->basket = $basket;
		$this->lang = (string) $lang;
	}
	
	
	/**
	 * Получение корзины.
	 */
	public function getBasket()
	{
		return $this->basket;
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
		return (self::PATH . 'basket_' . session_id() . '-' . strtolower($this->getBasket()->getEventCode()) . '.pdf');
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
		$lang = \Bitrix\Main\Application::getInstance()->getContext()->getLanguage();
		$url  = self::PDIR . session_id() . '/' . $this->getBasket()->getEventCode() . '/' . $lang . '/';
		
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


