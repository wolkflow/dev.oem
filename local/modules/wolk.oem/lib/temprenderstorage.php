<?php

namespace Wolk\OEM;

class TempRenderStorage extends \Wolk\Core\System\HLBlockModel
{
    const HBLOCK_ID = HLBLOCK_PARAMS_TEMP_RENDER_STORAGE_ID;
    
    // Список полей.
    const FIELD_ID      = 'ID';
    const FIELD_SESSID  = 'UF_SESSID';
    const FIELD_DATA    = 'UF_DATA';
	const FIELD_USER_ID = 'UF_USER_ID';
    const FIELD_TIME    = 'UF_TIME';
    const FIELD_LANG    = 'UF_LANG';
    
    
    public function getSessionID()
    {
        return $this->get(self::FIELD_SESSID);
    }
    
    
    public function getData()
    {
        return json_decode($this->get(self::FIELD_DATA), true);
    }
	
	
	public function getUserID()
    {
        return $this->get(self::FIELD_USER_ID);
    }
    
    
    public function getTime()
    {
        return $this->get(self::FIELD_TIME);
    }
    
	
	public function getLang()
    {
        return $this->get(self::FIELD_LANG);
    }
	
	
	
	/**
     * Удаление сохраненных данных из сессии.
     */
    public static function clear()
    {
		$sessid = session_id();
		
        $class  = self::getEntityClassName();
        $entity = new $class();
        
        $connection = \Bitrix\Main\Application::getConnection();
        $connection->startTransaction();
        
        $query = "
            DELETE FROM `" . $entity->getTableName() . "`
            WHERE `" . self::FIELD_SESSID . "` = '" . strval($sessid) . "'
        ";
        
        $connection->query($query);
        $connection->commitTransaction();
	}
	
    
    
    /**
     * Добавление данных.
     */
    public static function push($data, $lang = null)
    {
		$sessid = session_id();
		
		if (empty($lang)) {
            $lang = \Bitrix\Main\Context::getCurrent()->getLanguage();
        }
        $lang = mb_strtoupper($lang);
		
		self::clear();
		
		$item = new self();
		$item->add([
			self::FIELD_SESSID  => $sessid,
			self::FIELD_DATA    => json_encode((array) $data),
			self::FIELD_USER_ID => \CUser::getID(),
			self::FIELD_TIME    => new \Bitrix\Main\Type\DateTime(),
			self::FIELD_LANG    => $lang
		]);
		
		return $item;
    }
}