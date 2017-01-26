<?php
namespace AppBundle\DataFixtures\Tenant;

use AppBundle\Entity\Tenant;
use Client\Invoice;
use DateTime;
use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class LoadInvoicesData implements FixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $data = [
            ["number" => "01/01/2017", 'company' => 'ABC', 'createdAt' => new DateTime()],
            ["number" => "10/01/2017", 'company' => 'CDE', 'createdAt' => new DateTime('yesterday')],
            ["number" => "20/01/2017", 'company' => 'EFG', 'createdAt' => new DateTime('+1 month')],
        ];

        foreach($data as $tenant) {
            $object = Invoice::fromArray($tenant);
            $manager->persist($object);
        }

        $manager->flush();
    }
}
