<?php

namespace Wolk\OEM;

class Location extends \Wolk\Core\System\IBlockEntity
{
    const IBLOCK_ID = IBLOCK_LOCATIONS_ID;
    
    
    public function __construct($id = null, $data = [], $lang = LANG_EN_UP)
    {
		parent::__construct($id, $data);
        
		$this->lang = mb_strtoupper((string) $lang);
    }
	

	public function getLang()
	{
		return $this->lang;
	}
	
	
	/**
	 * Получение документов.
	 */
    public function getDocs()
    {
		$this->load();
		
		return ($this->data['PROPS']['DOCS_' . $this->getLang()]['VALUE']);
    }
    
}