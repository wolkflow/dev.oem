<?php

namespace Wolk\Core\System;

\Bitrix\Main\Loader::includeModule('iblock');

class IBlockSectionModel extends Model
{	
	/**
	 * Получение ID инфоблока рзделов.
	 */
	public static function getIBlockID()
	{
		return static::IBLOCK_ID;
	}
	
	
	/**
	 * Загрузка данных элемента.
	 *
	 * @param bool $force
	 * @return array
	 */
	public function load($force = false)
	{
		if (empty($this->data) || $force) {
			$element = \CIBlockSection::getList(
				array(), 
				array('IBLOCK_ID' => static::getIBlockID(), 'ID' => $this->getID()), 
				false, 
				array('UF_*')
			)->getNext();
            
			if ($element) {
				$this->data = $element;
			}
		}
		return $this->data;
	}
	
	
	public function getName()
    {
        return $this->get('NAME');
    }
    
    
    public function getCode()
    {
        return $this->get('CODE');
    }
    
    
    public function add($data)
    {
        $object = new \CIBlockSection();
		
		if ($this->id = $object->add((array) $data)) {
			return true;
		} else {
			throw new \Exception($object->LAST_ERROR);
		}
    }
	
	
	public function update($data)
    {
        $object = new \CIBlockSection();
		
		if ($id = $object->update($this->getID(), (array) $data)) {
			return true;
		} else {
			throw new \Exception($object->LAST_ERROR);
		}
    }
	
		
	public function delete()
    {
        return \CIBlockSection::delete($this->getID());
    }
	
	
	public function existDB()
    {
        $result = \CIBlockSection::getByID($this->getID())->Fetch();
		
		return $result;
    }
	
	
	/**
	 * Получени дочерних разделов.
	 */
	public function getChildren($params, $object = true, $key = 'ID')
	{
		$params['filter']['LEFT_MARGIN']  = $this->get('LEFT_MARGIN');
		$params['filter']['RIGHT_MARGIN'] = $this->get('RIGHT_MARGIN');
		
		$result = self::getList($params, $object, $key);
		
		return $result;
	}
	
	
    /**
     * Получение списка разделов.
     */
	public static function getList($params, $object = true, $key = 'ID', $fetch = false)
    {
        $result = self::queryByParams($params);

        if (!$object) {
            return $result;
        }
        $items = array();
        while ($item = $result->Fetch()) {
			if ($fetch) {
				$items[$item[$key]] = new static($item['ID'], $item);
			} else {
				$items[$item[$key]] = new static($item['ID']);
			}
        }
        return $items;
    }
    
    
    /**
     * Получение количества по фильтру.
     */
    public static function getCount($filter = array())
    {
        $result = self::getList(array('filter' => $filter), false);
        $count  = $result->SelectedRowsCount();
        
        return intval($count);
    }
	
	
    /**
     * @param array $params
     * @return \CIBlockResult|int
     * @throws \Exception
     * @throws \Bitrix\Main\LoaderException
     */
    private static function queryByParams($params = array())
    {
        if (!\Bitrix\Main\Loader::includeModule('iblock')) {
            throw new \Exception('Module IBLOCK is not installed.');
        }
		
        $order  = $params['order'] ?: array();
        $filter = array_merge($params['filter'] ?: array(), array('IBLOCK_ID' => self::getIBlockID()));
        $select = array_merge($params['select'] ?: array(), array('ID'));
		
        return (\CIBlockSection::GetList($order, $filter, $select));
    }
    
    
    /**
     * Получение корневого раздела.
     */
    public function getMainSection($asobject = true)
    {
        $select = ($asobject) ? (array('ID')) : (array());
        
        $result = \CIBlockSection::GetNavChain(self::getIBlockID(), $this->getID(), $select);
        
        if ($item = $result->fetch()) {
            $section = ($asobject) ? (new static($item['ID'])) : ($item);
        }
        return $section;
    }
    
    
    /**
     * Получение родительского раздела.
     */
    public function getSectionID()
    {
        return (int) $this->get('IBLOCK_SECTION_ID');
    }
    
    
    /**
     * Получение родительского раздела.
     */
    public function getSection()
    {
        $section = null;
        if ($this->getSectionID() > 0) {
            $section = new static($this->getSectionID());
        }
        return $section;
    }
    
    
    /**
     * Получение пути до корневого раздела.
     */
    public function getNavChain($select = array())
    {
        $result = \CIBlockSection::GetNavChain(self::getIBlockID(), $this->getID(), $select);
        
        $items = array();
        
        while ($item = $result->fetch()) {
            $items []= $item;
        }
        return $items;
    }
	
	
    /**
     * Получение ID всех подразделов.
     */
    public function getSubsectionIDs()
    {
        $data = $this->load();
        
        $result = \CIBlockSection::GetList(
            array('LEFT_MARGIN' => 'ASC'),
            array(
                'IBLOCK_ID'    => self::getIBlockID(),
                'LEFT_MARGIN'  => $data['LEFT_MARGIN'],
                'RIGHT_MARGIN' => $data['RIGHT_MARGIN'],
            ),
            array('ID')
        );
        
        $items = array();
        while ($item = $result->fetch()) {
            $items []= (int) $item['ID'];
        }
        return $items;
    }
    
    
    
    /**
     * Получение ID всех подразделов.
     */
    public static function getFullSubsectionIDs($sids)
    {
        $ids = array_map('intval', (array) $sids);
        
        $result = \CIBlockSection::GetList(
            array('LEFT_MARGIN' => 'ASC'),
            array(
                'IBLOCK_ID'  => self::getIBlockID(),
                'SECTION_ID' => $ids
            ),
            array('ID')
        );
        
        while ($item = $result->fetch()) {
            $ids []= $item['ID'];
        }
        return $ids;
    }
}