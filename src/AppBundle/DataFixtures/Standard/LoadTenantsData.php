<?php
namespace AppBundle\DataFixtures\Standard;

use AppBundle\Entity\Tenant;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadTenantsData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $data = [
            ['id' => 'superman', 'server' => 'localhost', 'database' => 'multidb_superman', 'username' => 'root', 'password' => 'root'],
            ['id' => 'spiderman', 'server' => 'localhost', 'database' => 'multidb_spiderman', 'username' => 'root', 'password' => 'root'],
            ['id' => 'batman', 'server' => 'localhost', 'database' => 'multidb_batman', 'username' => 'root', 'password' => 'root']
        ];

        foreach($data as $tenant) {
            $object = Tenant::fromArray($tenant);
            $manager->persist($object);
        }

        $manager->flush();
    }
}
