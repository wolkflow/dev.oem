<?php

namespace Wolk\Core\Utils;

class Ajax
{
	protected $status;
	protected $data;
	protected $html;
	
	
	public function __construct()
	{
		
	}
	
	
	public function setData($data)
	{
		$this->data = $data;
	}
	
	
	public function getData()
	{
		return $this->data;
	}
	
	
	public function setHTML($html)
	{
		$this->html = (strgin) $html;
	}
	
	
	public function getHTML($html)
	{
		return $this->html;
	}
	
	
	public function setStatus($status)
	{
		$this->status = (bool) $status;
	}
	
	
	public function getStatus()
	{
		return $this->status;
	}
	
	
	public function response()
	{
		$result = array(
			'status' => $this->getStatus(),
			'data'   => $this->getData(),
			'html'   => $this->getHTML(),
		);
		
		die(json_encode($result));
	}
}