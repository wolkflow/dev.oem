<?php

namespace Wolk\Core\System;

\Bitrix\Main\Loader::includeModule('highloadblock');

class HLBlockModel extends Model
{
	
	/**
     * Возвращает имя класса.
     *
     * @return string
     */
    public static function getEntityClassName()
    {
    	$hldata    = \Bitrix\Highloadblock\HighloadBlockTable::getById(static::HBLOCK_ID)->Fetch();
    	$entity    = \Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hldata);
    	$classname = $entity->getDataClass();
    	
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
			$entity  = self::getEntityClassName();
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
        $class  = self::getEntityClassName();
		$result = $class::add($data);
		
		if ($result->isSuccess()) {
			$this->id = $result->getID();
		} else {
			throw new \Exception(implode(", ", (array) $result->getErrorMessages()));
		}
        return $result;
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
		$result  = $element->update($data);
		
		if ($result->isSuccess()) {
			return $result;
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
	
	
	/**
	 *
	 */
	public static function prepareBatchItem($data)
	{
		$fields = static::getDBFields();
		
		$item = array();
		foreach ($fields as $field) {
			$item[$field] = $data[$field];
		}
		return $item;
	}
	
	
	/**
	 * Добавление списка позиций.
	 */
	public static function runBatchInsert($items)
	{
		$class  = static::getEntityClassName();
        $entity = new $class();
		
		$fields = static::getDBFields();
		$values = array();
		
		foreach ($items as $item) {
			$item = static::prepareBatchItem($item);
			if (count($item) == count($fields)) {
				$values []= "('" . implode("', '", $item) . "')";
			}
		}
		
		if (empty($values)) {
			return false;
		}
		
		// Запрос на добавление записей.
		$query = "
			INSERT INTO `" . $entity->getTableName() . "` (`" . implode("`, `", $fields) . "`)
			VALUES " . implode(", ", $values) . ";
		";
		
		$connection = \Bitrix\Main\Application::getConnection();
        $connection->startTransaction();
		
		$result = $connection->query($query);
        $connection->commitTransaction();
		
		return $result;
	}
	
	
	/**
	 * Получение списка столбцов.
	 */
	public static function getDBFields($noid = true)
	{
		$reflect = new \ReflectionClass(get_called_class());
        $consts  = $reflect->getConstants();
		$fields  = array_filter($consts, function($const) { return (strpos($const, 'FIELD_') !== false); }, ARRAY_FILTER_USE_KEY);
		$fields  = array_combine(array_values($fields), array_values($fields));
		
		if ($noid) {
			unset($fields['ID']);
		}
		return $fields;
	}
}