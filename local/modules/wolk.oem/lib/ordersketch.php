<?php

namespace Wolk\OEM;

class OrderSketch extends \Wolk\Core\System\HLBlockModel
{
    const HBLOCK_ID = HLBLOCK_ORDER_SKETCHES_ID;
    
    // Список полей.
    const FIELD_ID       = 'ID';
    const FIELD_ORDER_ID = 'UF_ORDER_ID';
    const FIELD_SCENE    = 'UF_SCENE';
    const FIELD_IMAGE    = 'UF_IMAGE';
    const FIELD_FILE     = 'UF_FILE';
    
    
    
    public function getOrderID()
    {
        return $this->get(self::FIELD_ORDER_ID);
    }
    
    
    public function getScene()
    {
        return $this->get(self::FIELD_SCENE);
    }
	
	
	public function getImage()
    {
        return $this->get(self::FIELD_IMAGE);
    }
	
	
	public function getFileID()
    {
        return $this->get(self::FIELD_FILE);
    }
    
    
    public function getFilePath()
    {
        return (\CFile::getPath($this->getFileID()));
    }
	
	
	public function saveFile()
	{
		$file = $this->makeFile();
		
		if (!empty($file)) {
			$this->update([self::FIELD_FILE => $file]);
		}
	}
    
	
	public function makeFile()
	{
		$file  = null;
		$image = base64_decode($this->getImage());
		
		if (!empty($image)) {
			$file = \CFile::SaveFile([
				'name'    	  => 'sketch-'.$this->getOrderID().'.jpg',
				'description' => 'Изображение скетча для заказа №'.$this->getOrderID(),
				'content'     => $image,
			], 'sketches');
		}
		
		return $file;
	}
	
	
	public static function clear($id)
	{
		$id = (int) $id;
		
		$class  = self::getEntityClassName();
        $entity = new $class();
        $table  = $entity->getTableName();
		
		$connection = \Bitrix\Main\Application::getConnection();
        
        $query = "
            DELETE FROM `" . $table . "`
            WHERE `" . self::FIELD_ORDER_ID . "` = '" . intval($id) . "'
        ";
        $result = $connection->query($query);
		
		return $result;
	}
}
