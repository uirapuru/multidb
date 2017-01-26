<?php
namespace AppBundle\Listener;

use AppBundle\Connection\Wrapper;
use AppBundle\Entity\Tenant;
use AppBundle\Entity\TenantRepository;
use AppBundle\TenantProviderInterface;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Schema\AbstractSchemaManager;
use Exception;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Event\ConsoleCommandEvent;
use Symfony\Component\Console\Input\InputOption;

class CommandListener
{
    /** @var  Wrapper */
    private $connectionWrapper;

    /** @var  TenantRepository */
    private $tenantProvider;

    /** @var  array|string[] */
    private $allowedCommands;

    /**
     * ClubConnectionCommandListener constructor.
     * @param array $config
     */
    public function __construct(TenantProviderInterface $tenantProvider, Connection $connectionWrapper, AbstractSchemaManager $schemaManager, $allowedCommands = [])
    {
        $this->tenantProvider = $tenantProvider;
        $this->connectionWrapper = $connectionWrapper;
        $this->schemaManager = $schemaManager;
        $this->allowedCommands = $allowedCommands;
    }

    /**
     * @param ConsoleCommandEvent $event
     * @throws \Exception
     */
    public function onConsoleCommand(ConsoleCommandEvent $event)
    {
        $command = $event->getCommand();
        $input = $event->getInput();

        if (!$this->isProperCommand($command)) {
            return;
        }

        $command->getDefinition()->addOption(
            new InputOption('tenant', null, InputOption::VALUE_OPTIONAL, 'tenant name', null)
        );

        if(!$command->getDefinition()->hasOption('em')) {
            $command->getDefinition()->addOption(
                new InputOption('em', null, InputOption::VALUE_OPTIONAL, 'The entity manager to use for this command')
            );
        }

        $input->bind($command->getDefinition());

        if(is_null($input->getOption('tenant'))) {
            $event->getOutput()->write('<error>default:</error> ');
            return;
        }

        $tenantName = $input->getOption('tenant');

        $input->setOption('em', 'tenant');
        $command->getDefinition()->getOption('em')->setDefault('tenant');

        /** @var Tenant $tenant */
        $tenant = $this->tenantProvider->getTenant($tenantName);

        if($tenant === null) {
            throw new Exception(sprintf('Tenant identified as %s does not exists', $tenantName));
        }

        $this->connectionWrapper->forceSwitch(
            $tenant->getServer(), $tenant->getDatabase(), $tenant->getUsername(), $tenant->getPassword(), false
        );

        $event->getOutput()->writeln(
            sprintf('<error>%s@%s:</error> ', $tenant->getUsername(), $tenant->getDatabase())
        );
    }

    /**
     * @param Command $command
     * @return bool
     */
    private function isProperCommand(Command $command)
    {
        return in_array($command->getName(), $this->allowedCommands);
    }
}
