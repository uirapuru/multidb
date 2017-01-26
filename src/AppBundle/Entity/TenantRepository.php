<?php
namespace AppBundle\Entity;

use AppBundle\TenantProviderInterface;
use Doctrine\ORM\EntityRepository;

class TenantRepository extends EntityRepository implements TenantProviderInterface
{
    /**
     * @param $tenant
     * @return null|Tenant
     */
    public function getTenant($tenant)
    {
        return $this->find($tenant);
    }
}
