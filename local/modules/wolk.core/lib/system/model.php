<?php

namespace Wolk\Core\System;

abstract class Model
{
	/**
     * ID объекта.
     */
    protected $id;
    
    /**
     * Данные объекта.
     */
    protected $data;
    
    /**
     * Сущность объекта.
     */
    protected $entity;
	
		
	/**
     * Создание объекта.
     * 
     * @param int $id
     * @param array $data
     */
    public function __construct($id = null, $data = [])
    {
        $this->id   = (int) $id;
        $this->data = (array) $data;
    }
	
	
	/**
     * Получение ID.
     */
    public function getID()
    {
        return $this->id;
    }

    /**
     * Получение данных в виде массива.
     */
    public function getData()
    {
        $this->load();

        return $this->data;
    }

    /**
     * Существование данных.
     */
    public function exists()
    {
        $this->load();

        return (!empty($this->data));
    }

    /**
     * Получение свойства.
     *
     * @param string $code
     * @return mixed
     */
    public function get($code)
    {
        $this->load();

        return $this->data[strval($code)];
    }
	
	
	/**
     * Проверка на заполненность парметра.
     *
     * @param string $code
     * @return bool
     */
    public function emptyField($code)
    {
        $this->load();

        return (empty($this->data[strval($code)]));
    }

	
    /**
     * Загрузка данных из базы.
     */
    protected function load()
    {
        if ($this->data) {
            return false;
        }
        $this->reset();
		
        return $this->data;
    }

	
    /**
     *  Удаление данных объекта.
     */
    protected function unload()
    {
		unset($this->data);
    }
	
	
	 /**
     *  Обновление данных объекта.
     */
    protected function reload()
    {
		$this->unload();
		$this->load();
    }
	
	
	/**
	 * Сохранение элемнета.
	 *
     * @param bool|array $data
     * @return AddResult|UpdateResult
     */
    public function save($data = false)
    {
        if ($this->existDB()) {
            return $this->update($data);
        }
        return $this->add($data);
    }
	
	
	/**
	 * Получение имени класса.
	 *
     * @return string
     */
    public static function className()
    {
        return get_called_class();
    }
	
	abstract public function add($data);
	
	
	abstract public function update($data);
	
		
	abstract public function delete();
	
	
	abstract public function existDB();
	
	
	abstract public static function getList($params, $object = true, $key = 'ID');
}