<?php

namespace Wolk\OEM;


\Bitrix\Main\Loader::includeModule('sale');


class OrderPrint
{
	const PATH = '/upload/orders/';
	
	const TYPE_ORDER = 'ORDER'; // Заказ.
	const TYPE_HFROM = 'HFORM'; // Форма подвесной конструкции.
	
	
	protected $oid = null;
	
	
	
	public function __construct($oid, $type = '')
	{
		$this->oid = (int) $oid;
	}
	
	
	/**
	 * Получение пути к файлу.
	 */
	public function getPath()
	{
		return (self::PATH.'order_'.$this->getOrderID().'.pdf');
	}
	
	
	public function getOrderID()
	{
		return $this->oid;
	}

	
	/**
	 * Получение счета.
	 */
	public function getOrderPrint()
	{
		if (is_readable($_SERVER['DOCUMENT_ROOT'].$this->getPath())) {
			return $this->getPath();
		}
		return null;
	}
	
	
	public function getURL($absolute = false)
	{
		$site = \CSite::GetByID(SITE_DEFAULT)->Fetch();
		$url  = '/printorder/'.$this->getOrderID().'/';
		
		if ($absolute) {
			$url = 'http://'.$site['SERVER_NAME'].$url;
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
		
		array_walk($args, function(&$val, $key) { $val = $key.' '.$val; });
		
		// Команда для выполнения.
		$cmd = 'wkhtmltopdf '.implode(' ', $args).' '.$url.' '.$_SERVER['DOCUMENT_ROOT'].$this->getPath();
		
		exec($cmd, $output, $result);
		
		return $result;
	}
}


