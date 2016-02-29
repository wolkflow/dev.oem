<?php

namespace Wolk\Core\System\Wrappers;

/**
 * Class SharedMemory
 */
class SharedMemory
{
    protected $uid = false;
    protected $id = false;
	
	
    public function __construct($code)
    {
        $this->uid = static::genUID($code);
        $this->id  = shm_attach($this->uid);
    }

	
    protected static function genUID($code)
    {
        return crc32($code);
    }
	

    protected function getUID()
    {
        return $this->uid;
    }

	
    protected function getID()
    {
        return $this->id;
    }

	
    /**
     * @param int $var
     * @return bool
     */
    public function has($var = 1)
    {
        return shm_has_var($this->getID(), $var);
    }

	
    /**
     * @param int $var
     * @return mixed
     */
    public function get($var = 1)
    {
        return shm_get_var($this->getID(), $var);
    }

	
    /**
     * @param int $var
     * @param bool $value
     * @return bool
     */
    public function set($var = 1, $value = true)
    {
        return shm_put_var($this->getID(), $var, $value);
    }
}

