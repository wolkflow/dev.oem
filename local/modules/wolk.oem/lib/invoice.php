<?php

namespace Wolk\OEM;


\Bitrix\Main\Loader::includeModule('sale');

class Invoice
{
	const PATH = '/upload/invoices/';
	
	protected $order_id	= null;
	protected $template = null;
	protected $company  = null;
	protected $number   = null;
	
	
	public function __construct($order_id, $template, $company, $number)
	{
		/*
		$oid = (int) $oid;
		
		$this->order = CSaleOrder::getByID($oid);
		$this->order['PROPS'] = Wolk\Core\Helpers\SaleOrder::getProperties($oid);
		$this->order['USER']  = CUser::GetByID()->Fetch();
		*/
		$this->order_id = (int)    $order_id;
		$this->template = (string) $template;
		$this->company  = \CUtil::translit(mb_strtolower((string) $company), 'ru', ['replace_space' => '-', 'replace_other' => '-']);
		$this->number   = preg_replace('/[^-0-9]/', '-', (string) $number);
	}
	
	
	/**
	 * Получение имени файла.
	 */
	public static function getClientFileName($company, $number)
	{
		$company  = \CUtil::translit(mb_strtolower((string) $company), 'ru', ['replace_space' => '-', 'replace_other' => '-']);
		$number   = preg_replace('/[^-0-9]/', '-', (string) $number);
		
		return ('invoice_'.$company.'_'.$number.'.pdf');
	}
	
	
	/**
	 * Получение пути к файлу.
	 */
	public function getFileName()
	{
		return ('invoice_'.$this->getCompany().'_'.$this->getNumber().'_'.$this->getOrderID().'_'.$this->getTemplate().'.pdf');
	}
	
	
	/**
	 * Получение пути к файлу.
	 */
	public function getPath()
	{
		return (self::PATH . $this->getFileName());
	}
	
	
	/**
	 * Получение пути к директории.
	 */
	public static function getFolder()
	{
		return ($_SERVER['DOCUMENT_ROOT'] . self::PATH);
	}
	
	
	public function getOrderID()
	{
		return $this->order_id;
	}
	
	
	public function getTemplate()
	{
		return $this->template;
	}
	
	
	public function getCompany()
	{
		return $this->company;
	}
	
	
	public function getNumber()
	{
		return $this->number;
	}
	
	
	/**
	 * Получение счета.
	 */
	public function getInvoice()
	{
		if (is_readable($_SERVER['DOCUMENT_ROOT'].$this->getPath())) {
			return $this->getPath();
		}
		return null;
	}
	
	
	/**
	 * Печать документа.
	 */
	public function make()
	{
		global $APPLICATION;
		
		$site = \CSite::GetByID(SITE_DEFAULT)->Fetch();
		$url  = 'http://'.$site['SERVER_NAME'].'/invoice/'.$this->getOrderID().'/'.$this->getTemplate().'/';
		
		exec('wkhtmltopdf -q '.$url.' '.$_SERVER['DOCUMENT_ROOT'].$this->getPath(), $output, $result);
		
		return $result;
	}
}




