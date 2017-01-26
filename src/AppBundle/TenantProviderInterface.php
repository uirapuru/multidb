<?php
namespace AppBundle;

interface TenantProviderInterface
{
    public function getTenant($tenant);
}
