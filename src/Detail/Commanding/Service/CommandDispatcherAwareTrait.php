<?php

namespace Detail\Commanding\Service;

use Detail\Commanding\Command\CommandInterface;
use Detail\Commanding\CommandDispatcherInterface;
use Detail\Commanding\Exception;

trait CommandDispatcherAwareTrait
{
    /**
     * @var CommandDispatcherInterface
     */
    protected $commands;

    /**
     * @return CommandDispatcherInterface
     */
    public function getCommands()
    {
        return $this->commands;
    }

    /**
     * @param CommandDispatcherInterface $commands
     */
    public function setCommands(CommandDispatcherInterface $commands)
    {
        $this->commands = $commands;
    }

    protected function handleCommand(CommandInterface $command)
    {
        $commandDispatcher = $this->getCommands();

        if ($commandDispatcher === null) {
            throw new Exception\RuntimeException(
                'A command dispatcher needs to be injected before commands can be handled'
            );
        }

        return $commandDispatcher->handle($command);
    }
}
