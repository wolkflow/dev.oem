<?php

namespace Wolk\Core\System;

\Bitrix\Main\Loader::includeModule('highloadblock');

class HLBlockModel extends Model
{
	static protected $hlblockID;
	
	/**
     * Возвращает имя класса.
     *
     * @return string
     */
    public static function getEntityClassName()
    {
    	$hldata    = \Bitrix\Highloadblock\HighloadBlockTable::getById(static::$hlblockID)->Fetch();
    	$hlentity  = \Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hldata);
    	$classname = $hlentity->getDataClass();
    	
        return $classname;
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
			$entity = self::getEntityClassName();
			$element = $entity::GetByID($this->getID());
			
			if ($element) {
				$this->data = $element->fetch();
			}
		}
		return $this->data;
	}
	
	
	/**
	 * Добавление элемента.
	 *
	 * @param array $data
	 * @return mixed
	 */
	public function add($data)
	{
        $class   = self::getEntityClassName();
		//$element = new $class();
		$result = $class::add($data);
		
		if ($result->isSuccess()) {
			$this->id = $result->getID();
			return $this->getID();
		} else {
			throw new \Exception($result->getErrorMessages());
		}
	}
	
	
	/**
	 * Редактирование элемента.
	 *
	 * @param array $data
	 * @return mixed
	 */
	public function update($data)
	{
        $class   = self::getEntityClassName();
		$element = new $class($this->getID());
		$result = $element->update($data);
		
		if ($result->isSuccess()) {
			return $this->getID();
		} else {
			throw new \Exception($result->getErrorMessages());
		}
	}
	
	
	/**
	 * Удаление элемента
	 *
	 * @retrun bool
	 */
	public function delete()
	{
        $class   = self::getEntityClassName();
		$element = new $class();
		$element->elete($this->getID());
	}
	
	
	/**
	 * Проверка существования элемента в БД.
	 *
	 * @return bool
	 */
	public function existDB()
	{
		$class  = self::getEntityClassName();
		$entity = new $class();
		$result = $entity::GetByID($this->getID())->fetch();
		
		return ($result);
	}
	
	
	/**
	 * Получение списка элементов.
	 *
	 * @param array $params
	 * @param bool $object
	 * @param string $key
	 * @return mixed
	 */
	public static function getList($params, $object = true, $key = 'ID')
	{
		$entity = self::getEntityClassName();
		$result = $entity::getList($params);

        if (!$object) {
            return $result;
        }
        $items = array();
        while ($item = $result->fetch()) {
            $items[$item[$key]] = new static($item['ID']);
        }
        return $items;
	}
}