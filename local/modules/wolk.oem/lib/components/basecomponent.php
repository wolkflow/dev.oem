<?php 

namespace Wolk\OEM\Components;


abstract class BaseComponent extends \CBitrixComponent
{
	protected $cacheKeys  = ['ITEMS'];
	protected $cacheAddon = [];
	protected $navParams  = [];

	
	protected function readDataFromCache()
	{
		if ($this->arParams['CACHE_TYPE'] == 'N') {
			return false;
		}
		return !($this->StartResultCache(false, $this->cacheAddon));
	}
	

	protected function putDataToCache()
	{
		if (is_array($this->cacheKeys) && sizeof($this->cacheKeys) > 0) {
			$this->SetResultCacheKeys($this->cacheKeys);
		}
	}

	
	protected function abortDataCache()
	{
		$this->AbortResultCache();
	}
	

	protected function isAjaxRequest()
	{
		return \Bitrix\Main\Context::getCurrent()->getRequest()->isPost()
			&& $this->arParams['AJAX_MODE'] == 'Y'
			&& $this->arParams['AJAX_ID'] == \Bitrix\Main\Context::getCurrent()->getRequest()->getPost('bxajaxid');
	}

	
	protected function executeEpilog()
	{
		if ($this->isAjaxRequest()) {
			die();
		}
	}
	

	public function onPrepareComponentParams($params)
	{
        
	}

	
	public function executeComponent()
	{
		try {
			$this->executeProlog();
			if(!$this->readDataFromCache()) {
				$this->getResult();
				$this->putDataToCache();
				$this->includeComponentTemplate();
			}
			$this->executeEpilog();
		} catch(\Exception $e) {
			$this->abortDataCache();
			ShowError($e->getMessage());
		}
	}

	abstract public function getResult();
}
