<?php

namespace Wolk\OEM\Dicts;

class Color extends \Wolk\Core\System\HLBlockModel
{
    const HBLOCK_ID = COLORS_ENTITY_ID;

    const LANG_PREFIX = 'UF_LANG_';


    /**
     * Получение названия цвета.
     */
    public function getName($lang = null)
    {
        $this->load();

        if (empty($lang)) {
            $lang = \Bitrix\Main\Context::getCurrent()->getLanguage();
        }
        $lang = mb_strtoupper($lang);

        $name = $this->data[self::LANG_PREFIX . 'NAME_' . $lang];
        if (empty($name)) {
            $name = $this->data['UF_XML_ID'];
        }
        return $name;
    }
	
	
	/**
     * Получение номера цвета.
     */
	public function getNumber()
	{
		return $this->get('UF_NUM');
	}
	
	
	public function getCode()
	{
		return $this->get('UF_CODE');
	}
	
	
	public function getBackGround()
	{
		return $this->get('UF_BACKGROUND');
	}
	
	
	/**
     * Получение цвета.
     */
	public function getBG()
	{
		$back = $this->getBackGround();
		$code = $this->getCode();
		
		$color = (!empty($back)) 
			   ? ('url('.\CFile::getPath($back).')')
			   : ('rgb('.$code.')');
	   
	    return $color;
	}
}