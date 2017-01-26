<?php
namespace AppBundle\Connection;

use Doctrine\DBAL\Connection;
use Doctrine\Common\EventManager;
use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\Driver;
use Doctrine\DBAL\Event\ConnectionEventArgs;
use Doctrine\DBAL\Events;

class Wrapper extends Connection
{
    /**
     * @var bool
     */
    private $isConnected = false;

    /**
     * @var array
     */
    protected $_params = [];

    /**
     * @param array $params
     * @param Driver $driver
     * @param Configuration|null $config
     * @param EventManager|null $eventManager
     */
    public function __construct(
        array $params, Driver $driver, Configuration $config = null, EventManager $eventManager = null
    ) {
        $this->_params = $params;
        parent::__construct($params, $driver, $config, $eventManager);
    }
    /**
     * @param $host
     * @param $dbname
     * @param $username
     * @param $password
     */
    public function forceSwitch($host, $dbname, $username, $password, $connect = true)
    {
        if ($this->isConnected()) {
            $this->close();
        }
        $this->_params['host'] = $host;
        $this->_params['dbname'] = $dbname;
        $this->_params['user'] = $username;
        $this->_params['password'] = $password;

        if($connect) {
            $this->connect();
        }
    }
    /**
     * {@inheritDoc}
     */
    public function connect()
    {
        if ($this->isConnected()) {
            return true;
        }

        $this->_conn = $this->_driver->connect(
            $this->_params,
            $this->_params['user'],
            $this->_params['password'],
            $this->_params['driverOptions']
        );

        if ($this->_eventManager->hasListeners(Events::postConnect)) {
            $eventArgs = new ConnectionEventArgs($this);
            $this->_eventManager->dispatchEvent(Events::postConnect, $eventArgs);
        }

        $this->isConnected = true;

        return true;
    }

    /**
     * {@inheritDoc}
     */
    public function isConnected()
    {
        return $this->isConnected;
    }

    /**
     * {@inheritDoc}
     */
    public function close()
    {
        if ($this->isConnected()) {
            parent::close();
            $this->isConnected = false;
        }
    }

    /**
     * @return array
     */
    public function getParams()
    {
        return $this->_params;
    }
}
