<?php

namespace Wolk\Core\System\Wrappers;

/**
 * Class Semaphore
 */
class Semaphore
{
    protected $id 		  = null;
    protected $sem		  = null;
    protected $autoremove = null;
    protected $maxacquire = null;
    protected $mode 	  = null;

	
	
    /**
     * @param string $name
     * @param int $maxacquire
     * @param int $mode
     * @param int $autoremove
     */
    public function __construct($name = '', $maxacquire = 1, $mode = 0644, $autoremove = 1)
    {
        $this->id 		  = self::createID($name, getmyinode());
        $this->maxacquire = $maxacquire;
        $this->mode 	  = $mode;
        $this->autoremove = $autoremove;
    }
	
		
	public function getID()
	{
		return $this->id;
	}
	
	
    /**
     * @throws Exception
     */
    public function __destruct()
    {
        if ($this->sem && $this->autoremove == 1) {
            $this->remove();
        }
    }
	

    private function __clone()
    {}

	
    /**
     * @param $name
     * @param null $inode
     * @return int
     */
    public static function createID($name, $inode = null)
    {
        $name  = hexdec(substr(md5($name), 24));
		$inode = ($inode ?: getmyinode());
        
        return ($inode + $name);
    }

	
    /**
     * @throws Exception
     */
    protected function create()
    {
        $this->sem = sem_get(
            $this->id,
            $this->maxacquire,
            $this->mode,
            $this->autoremove
        );
		
        if (!$this->sem) {
            throw new Exception(sprintf("Semaphore %d: sem_get() failed", $this->getID()));
        }
    }
	

    /**
     * @throws Exception
     */
    protected function assertSemCreated()
    {
        if (!$this->sem) {
            $this->create();
        }
    }
	

    /**
     * @throws Exception
     */
    public function acquire()
    {
        $this->assertSemCreated();
        if (!sem_acquire($this->sem)) {
            throw new Exception(sprintf("Semaphore %d: sem_acquire() failed", $this->getID()));
        }
    }

	
    /**
     * @throws Exception
     */
    public function release()
    {
        $this->assertSemCreated();
        if (!sem_release($this->sem)) {
            throw new Exception(sprintf("Semaphore %d: sem_release() failed", $this->getID()));
        }
    }

	
    /**
     * @throws Exception
     */
    public function touch()
    {
        $this->acquire();
        $this->release();
    }

	
    /**
     * @throws Exception
     */
    public function remove()
    {
        if (!sem_remove($this->sem)) {
            throw new Exception(sprintf("Semaphore %d: sem_remove() failed", $this->getID()));
        }
        $this->sem = null;
    }
}
