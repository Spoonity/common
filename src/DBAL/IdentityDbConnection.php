<?php

namespace Spoonity\DBAL;


use Doctrine\Common\EventManager;
use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Driver;

/**
 * Class IdentityDbConnection
 * @package App\DBAL
 */
class IdentityDbConnection extends Connection
{
    /**
     * @param array $params
     * @param Driver $driver
     * @param Configuration|null $config
     * @param EventManager|null $eventManager
     * @throws \Doctrine\DBAL\Exception
     */
    public function __construct(array $params, Driver $driver, ?Configuration $config = null, ?EventManager $eventManager = null)
    {
        parent::__construct($params, $driver, $config, $eventManager);
    }

    /**
     * @param string $dbName
     * @param string $user
     * @param string $password
     * @param string $host
     * @param int $port
     * @return void
     * @throws \Doctrine\DBAL\Exception
     */
    public function reconnect(string $dbName, string $user, string $password, string $host, int $port): void
    {
        if($this->isConnected()) {
            $this->close();
        }

        $params = $this->getParams();

        $params['dbname'] = $dbName;
        $params['user'] = $user;
        $params['password'] = $password;
        $params['host'] = $host;
        $params['port'] = $port;

        parent::__construct($params, $this->_driver, $this->_config, $this->_eventManager);
    }
}
