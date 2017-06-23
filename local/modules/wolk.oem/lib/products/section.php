<?php

namespace Wolk\OEM\Products;

class Section extends \Wolk\Core\System\IBlockSectionModel
{
	const IBLOCK_ID   = IBLOCK_PRODUCTS_ID;
    const LANG_PREFIX = 'LANG_';
    
    const DEPTH_SECTION = 1; // Раздел
    const DEPTH_GROUP   = 2; // Группа
    const DEPTH_VIEW    = 3; // Вид
    
    const TYPE_EQUIPMENTS = 'EQUIPMENTS';
    const TYPE_SERVICES   = 'SERVICES';
    const TYPE_MARKETINGS = 'MARKETINGS';
    
    protected static $pricetypes = array();
    protected static $properties = array();
    
    // Временные внутренние данные (в текущем рабочем простаранстве).
    protected $insides = [];
    
    
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
		
		return $this->data['UF_' . self::LANG_PREFIX . 'TITLE_' . $lang];
	}


    public function getListTitle($lang = null)
    {
        $this->load();

        if (empty($lang)) {
            $lang = \Bitrix\Main\Context::getCurrent()->getLanguage();
        }
        $lang = mb_strtoupper($lang);

        return $this->data['UF_' . self::LANG_PREFIX . 'LIST_NAME_' . $lang];
    }

    
    public function getDescription($lang = null)
	{
		$this->load();
        
        if (empty($lang)) {
            $lang = \Bitrix\Main\Context::getCurrent()->getLanguage();
        }
        $lang = mb_strtoupper($lang);
		
		return $this->data['UF_' . self::LANG_PREFIX . 'NOTE_' . $lang];
	}
    
    
    /**
     * Получение типа товара.
     */
    public function getType()
    {
        $data = $this->getMainSection()->getData();
        
        return $data['CODE'];
    }
    
    
    /**
     * Получение типа товара.
     */
    public function getPriceType()
    {
        $pricetypes = self::getPriceTypesSource();
        
        $value  = $this->get('UF_PRICE_TYPE');
        $result = $pricetypes[$value]['XML_ID'];
        
        return $result;
    }
    
    /**
     * Получение свойств товара.
     */
    public function getProperties()
    {
        $properties = self::getPropertiesSource();
        
        $values = $this->get('UF_PROPERTIES');
        $result = array();
        foreach ($values as $value) {
            $result []= $properties[$value]['XML_ID'];
        }
        return $result;
    }
    
    
    /**
     * Получение уровня вложенности.
     */
	public function getDepth()
	{
		return intval($this->data['DEPTH_LEVEL']);
	}
    
    
    /**
     * Получение продукции.
     */
    public function getProducts()
    {
        $products = Base::getList([
            'filter' => ['SECTION_ID' => $this->getID()]
        ]);
    
        return $products;
    }
    
    
    /**
     * Список типов расчета цены продукции.
     
    public static function getPriceTypes()
    {
        $values = self::getPriceTypesSource();
        $result = array();
        foreach ($values as $value) {
            $result[$value['XML_ID']] = $value['VALUE'];
        }
        return $result;
    }
     */
    
    
    /**
     * Установка внутренних данных.
     */
    public function setInsides($items)
    {
        $this->insides = (array) $items;
    }
    
    
    /**
     * Добавление внутренних данных.
     */
    public function addInside($item, $key = null)
    {
        if (is_null($key)) {
            $this->insides []= $item;
        } else {
            $this->insides[strval($key)] = $item;
        }
    }
    
    
    /**
     * Удаление внутренних данных.
     */
    public function delInside($key)
    {
        unset($this->insides[strval($key)]);
    }
    
    
    /**
     * Получение внутренних данных.
     */
    public function getInsides($sort = null)
    {
        if (!is_null($sort)) {
            $sort = (string) $sort;
            
            uasort($this->insides, function($x1, $x2) use ($sort) {
                return ($x1->get($sort) - $x2->get($sort));
            });
        }
        return $this->insides;
    }
    
    
    /**
     * Получение типов расчета цены продукции.
     */
    protected static function getPriceTypesSource()
    {
        if (empty(self::$pricetypes)) {
            $result = \CUserTypeEntity::GetList(array($b => $o), array('IBLOCK_ID' => self::IBLOCK_ID, 'FIELD_NAME' => 'UF_PRICE_TYPE'));
            if ($item = $result->fetch()) {
                $enum   = new \CUserFieldEnum();
                $result = $enum->GetList(array(), array('USER_FIELD_ID' => $item['ID']));
                $values = array();
                while ($value = $result->GetNext()){
                    $values[$value['ID']] = $value;
                }
            }
            self::$pricetypes = $values;
        }
        return self::$pricetypes;
    }
    
    
    /**
     * Получение свойств продукции.
     */
    protected static function getPropertiesSource()
    {
        if (empty(self::$properties)) {
            $result = \CUserTypeEntity::GetList(array($b => $o), array('IBLOCK_ID' => self::IBLOCK_ID, 'FIELD_NAME' => 'UF_PROPERTIES'));
            if ($item = $result->fetch()) {
                $enum   = new \CUserFieldEnum();
                $result = $enum->GetList(array(), array('USER_FIELD_ID' => $item['ID']));
                $values = array();
                while ($value = $result->GetNext()){
                    $values[$value['ID']] = $value;
                }
            }
            self::$properties = $values;
        }
        return self::$properties;
    }
}