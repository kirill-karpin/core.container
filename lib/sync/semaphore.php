<?php


namespace Toolbox\Core\Sync;

use Toolbox\Core\Logger\LoggerTrait;

class Semaphore
{
    use LoggerTrait;

    private $key;
    private $maxAcquire;
    private $permissions;
    private $autoRelease;
    private $monopole;
    private $resource;

    /**
     * Semaphore constructor.
     * @param $key
     * @param int $maxAcquire
     * @param int $permissions
     * @param int $autoRelease
     * @param bool $monopole
     * @throws \Exception
     */
    public function __construct($key, $maxAcquire = 1, $permissions = 0666, $autoRelease = 1, $monopole = false)
    {
        if (is_null($key)) {
            throw new \Exception('Semaphore key is null');
        }
        $this->key = $key;
        $this->maxAcquire = $maxAcquire;
        $this->permissions = $permissions;
        $this->autoRelease = $autoRelease;
        $this->monopole = $monopole;
        $this->resource = sem_get($this->key, $this->maxAcquire, $this->permissions, $this->autoRelease);
    }


    /**
     * @return mixed
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param mixed $key
     * @return Semaphore
     */
    public function setKey($key)
    {
        $this->key = $key;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMaxAcquire()
    {
        return $this->maxAcquire;
    }

    /**
     * @param mixed $maxAcquire
     * @return Semaphore
     */
    public function setMaxAcquire($maxAcquire)
    {
        $this->maxAcquire = $maxAcquire;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getPermissions()
    {
        return $this->permissions;
    }

    /**
     * @param mixed $permissions
     * @return Semaphore
     */
    public function setPermissions($permissions)
    {
        $this->permissions = $permissions;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getAutoRelease()
    {
        return $this->autoRelease;
    }

    /**
     * @param mixed $autoRelease
     * @return Semaphore
     */
    public function setAutoRelease($autoRelease)
    {
        $this->autoRelease = $autoRelease;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getMonopole()
    {
        return $this->monopole;
    }

    /**
     * @param mixed $monopole
     * @return Semaphore
     */
    public function setMonopole($monopole)
    {
        $this->monopole = $monopole;
        return $this;
    }

    public function lock()
    {
        return sem_acquire($this->resource, true);
    }

    public function release()
    {
        return sem_release($this->resource);
    }

    public static function process($uid, $callback)
    {
        $semaphore = new self($uid);

        self::log('Process start.');

        if ($semaphore->lock()) {

            $callback();

            $semaphore->release();

        } else {

            self::log('Process ' . $uid. 'is running.');

            return;
        }

        self::log('Process finish.' );
    }


}