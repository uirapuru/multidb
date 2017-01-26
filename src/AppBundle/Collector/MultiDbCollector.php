<?php
namespace AppBundle\Collector;

use Doctrine\DBAL\Connection;
use Exception;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\DataCollector\DataCollector;

class MultiDbCollector extends DataCollector
{
    /** @var Connection */
    private $defaultConnection;

    /** @var Connection */
    private $tenantConnection;

    /**
     * MultiDbCollector constructor.
     * @param $default
     * @param $tenant
     */
    public function __construct(Connection $defaultConnection, Connection $tenantConnection)
    {
        $this->defaultConnection = $defaultConnection;
        $this->tenantConnection = $tenantConnection;
    }

    /**
     * @return string
     */
    public function getDefault()
    {
        return $this->data['default'];
    }

    /**
     * @return string
     */
    public function getTenant()
    {
        return $this->data['tenant'];
    }

    /**
     * @param Request $request
     * @param Response $response
     * @param Exception|null $exception
     */
    public function collect(Request $request, Response $response, Exception $exception = null)
    {
        $this->data['default'] = $this->defaultConnection->getDatabase();
        $this->data['tenant'] = $this->tenantConnection->isConnected() ? $this->tenantConnection->getDatabase() : null;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'database_connections';
    }
}
