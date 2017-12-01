<?php

namespace Wolk\OEM\Products;

use Wolk\OEM\Context;
use Wolk\OEM\Products\Section;
use Wolk\OEM\Prices\Product as ProductPrice;

class Base extends \Wolk\Core\System\IBlockModel implements \Wolk\OEM\Interfaces\ContextPricing
{
	const IBLOCK_ID   = IBLOCK_PRODUCTS_ID;
    const LANG_PREFIX = 'LANG_';
	
	const SPECIAL_TYPE_FASCIA  = 'FASCIA';
	const SPECIAL_TYPE_HANDING = 'HANDING';
    
	protected $price;
    protected $count;
	
	
	
    public function __construct($id = null, $data = [])
    {
        parent::__construct($id, $data);
    }
    
	
	public function getTitle($lang = null)
	{
		$this->load();
        
        if (empty($lang)) {
            $lang = \Bitrix\Main\Context::getCurrent()->getLanguage();
        }
        $lang = mb_strtoupper($lang);
		
		return $this->data['PROPS'][self::LANG_PREFIX . 'TITLE_' . $lang]['VALUE'];
	}
    
    
    public function getDescription($lang = null)
	{
		$this->load();
        
        if (empty($lang)) {
            $lang = \Bitrix\Main\Context::getCurrent()->getLanguage();
        }
        $lang = mb_strtoupper($lang);
		
		return $this->data['PROPS'][self::LANG_PREFIX . 'DESCRIPTION_' . $lang]['~VALUE']['TEXT'];
	}
    
    
    public function getImageSrc()
    {
        $this->load();
        
        return (\CFile::getPath($this->data['PREVIEW_PICTURE']));
    }


    public function isSketchShow()
    {
        $this->load();

        return boolval($this->data['PROPS']['SKETCH_SHOW']['VALUE'] == 'Y');
    }


    public function getSketchType()
    {
        $this->load();

        return strval($this->data['PROPS']['SKETCH_TYPE']['VALUE']);
    }
    
    
    public function getSketchWidth()
    {
        $this->load();
        
        return floatval($this->data['PROPS']['SKETCH_WIDTH']['VALUE']);
    }
    
    
    public function getSketchHeight()
    {
        $this->load();
        
        return floatval($this->data['PROPS']['SKETCH_HEIGHT']['VALUE']);
    }


    public function getSketchImage()
    {
        $this->load();

        return intval($this->data['PROPS']['SKETCH_IMAGE']['VALUE']);
    }


    /**
     * Получение пути к изображению для скетча.
     */
    public function getSketchImageSrc()
    {
        return (\CFile::GetPath($this->getSketchImage()));
    }


    /**
     * Получение подотовленного изображения для скетча.
     */
    public function getSketchImagePrepared()
    {
        $src = \CFile::ResizeImageGet(
            $this->getSketchImage(), [
            'width'  => ($this->getSketchWidth()  / 10 < 5) ? (5) : ($this->getSketchWidth()  / 10),
            'height' => ($this->getSketchHeight() / 10 < 5) ? (5) : ($this->getSketchHeight() / 10),
        ])['src'];

        return $src;
    }
    
    
    /**
     * Получение пути к модели.
     */
    public function getModelPath()
    {
        return ($_SERVER['DOCUMENT_ROOT'] . \Wolk\OEM\Render::PATH_MODELS . '/' . $this->getID());
    }

    
    /**
     * Получение цены из контекста.
     */
	public function getContextPrice(Context $context)
	{
        $price = 0;
        
		if (!empty($context)) {
			$result = ProductPrice::getList(
                [
                    'order'  => [ProductPrice::FIELD_ID => 'DESC'],
                    'filter' => [
                        ProductPrice::FIELD_PRODUCT => $this->getID(),
                        ProductPrice::FIELD_EVENT   => $context->getEventID(),
                        ProductPrice::FIELD_TYPE    => $context->getType(),
                        ProductPrice::FIELD_LANG    => $context->getLang(),
                    ],
                    'select' => [
                        ProductPrice::FIELD_PRICE
                    ],
                    'limit' => 1
                ],
                false
            );
            
            if ($item = $result->fetch()) {
                $price = (float) $item[ProductPrice::FIELD_PRICE];
            }
		}
		return $price;
	}
    
    
    /**
     * Получение цены.
     */
	public function getPrice()
	{
		return $this->price;
	}
	
	
    /**
     * Установка цены.
     */
	public function setPrice($price)
	{
		$this->price = (float) $price;
	}
    
    
    /**
     * Получение количества.
     */
	public function getCount($default = true)
	{
		if (!empty($this->count) && $default) {
			$this->load();
		}
		return $this->count;
	}
	
	
    /**
     * Установка количества.
     */
	public function setCount($count)
	{
		$this->count = (int) $count;
	}
    
    
    /**
     * Получение ID раздела.
     */
    public function getSectionID()
    {
        return (int) $this->get('IBLOCK_SECTION_ID');
    }
	
	
	/**
     * Получение раздела.
     */
    public function getSection()
    {
        $section = new Section((int) $this->get('IBLOCK_SECTION_ID'));
        
        return $section;
    }
    
    
    /**
     * Получение типа раздела.
     */
    public function getSectionType()
    {
        $section = $this->getSection()->getMainSection();
        
        return $section->getCode();
    }
	
	
	/**
	 * Получение специальных типов.
	 */
	public function isSpecialType($type)
	{
		// Специальные свойство.
		$enums = self::getSpecialTypes();
		
		return (in_array($this->getSectionID(), $enums[strval($type)]['SIDS']));
	}
    
	
	/**
	 * Получение ID элементов с уникальными свойствами.
	 */
	public static function getSpecialTypes() 
	{
		$cache = new \CPHPCache();
		
		if (0 && $cache->InitCache(3600 * 36, 'get-special-type-enums-ids', '/products/')) {
			 $enums = $cache->GetVars();
		} else {
			// Пользовательское свойство.
			$result = \CUserTypeEntity::GetList([], ['ENTITY_ID' => 'IBLOCK_'.IBLOCK_PRODUCTS_ID.'_SECTION', 'FIELD_NAME' => 'UF_SPECIAL']);
			$ufield = $result->fetch();
			
			// Варианты пользовательского свойства - список.
			$result = \CUserFieldEnum::GetList([], ['IBLOCK_ID' => IBLOCK_PRODUCTS_ID, 'USER_FIELD_ID' => $ufield['ID']]);
			$enums  = [];
			while ($enum = $result->fetch()) {
				$enums[$enum['XML_ID']] = $enum;
			}
			
			// Разделы с пользовательским свойством.
			foreach ($enums as &$enum) {
				$result = \CIBlockSection::GetList([], ['IBLOCK_ID' => IBLOCK_PRODUCTS_ID, 'UF_SPECIAL' => $enum['ID']], false, ['ID', 'NAME', 'UF_SPECIAL']);
				while ($section = $result->getNext()) {
					$enum['SIDS'] []= (int) $section['ID'];
				}
				$enum['SIDS'] = array_unique($enum['SIDS']);
			}
			unset($result, $enum);
			
			$cache->EndDataCache($enums);
		}
		return $enums;
	}
	
    	
	/**
	 * Получение ID элементов с уникальными свойствами.\
	 */
	public static function getSpecialTypeIDs() 
	{
		$cache = new \CPHPCache();
		
		if ($cache->InitCache(3600 * 4, 'get-special-type-product-ids', '/products/')) {
			 $items = $cache->GetVars();
		} else {
			// Специальные свойство.
			$enums = self::getSpecialTypes();
			
			$items = [];
			foreach ($enums as $enum) {
				if (empty($enum['SIDS'])) {
					continue;
				}
				
				$result = self::getList([
					'filter' => ['SECTION_ID' => $enum['SIDS']],
					'select' => ['ID']
				], false);
				
				while ($item = $result->fetch()) {
					$items[$enum['XML_ID']] []= (int) $item['ID'];
				}
				$items[$enum['XML_ID']] = array_unique($items[$enum['XML_ID']]);
			}
			$cache->EndDataCache($items);
		}
		return $items;
	}
}
