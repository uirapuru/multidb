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

class FixturesListener
{
    /** @var  array|string[] */
    private $allowedCommands = [
        'doctrine:fixtures:load'
    ];

    private $options = [];

    /**
     * ClubConnectionCommandListener constructor.
     * @param array $config
     */
    public function __construct($options = [])
    {
        $this->options = $options;
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

        if($input->hasOption('tenant') && $input->getOption('tenant') !== null) {
            $dir = $this->options['tenant'];
        } else {
            $dir = $this->options['default'];
        }

        $command->getDefinition()->getOption('fixtures')->setDefault([$dir]);
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
