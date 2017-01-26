<?php
namespace AppBundle\Listener;

use AppBundle\Connection\Wrapper;
use AppBundle\TenantProviderInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class TenantListener
{
    /** @var  Wrapper */
    private $connection;

    /** @var  TenantProviderInterface */
    private $tenantProvider;

    /**
     * TenantListener constructor.
     * @param Wrapper $connection
     * @param TenantProviderInterface $tenantProvider
     */
    public function __construct(Wrapper $connection, TenantProviderInterface $tenantProvider)
    {
        $this->connection = $connection;
        $this->tenantProvider = $tenantProvider;
    }

    public function onKernelRequest(GetResponseEvent $event)
    {
        if (!$event->isMasterRequest()) {
            return;
        }

        $query = [];
        parse_str($event->getRequest()->getQueryString(), $query);

        if(array_key_exists('db', $query)) {
            $tenant = $this->tenantProvider->getTenant($query['db']);
            $this->connection->forceSwitch($tenant->getServer(), $tenant->getDatabase(), $tenant->getUsername(), $tenant->getPassword());
        }
    }
}
