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

    /**
     * @param CommandInterface $command
     * @return mixed
     */
    protected function dispatchCommand(CommandInterface $command)
    {
        $commandDispatcher = $this->getCommands();

        if ($commandDispatcher === null) {
            throw new Exception\RuntimeException(
                'A command dispatcher needs to be injected before commands can be handled'
            );
        }

        return $commandDispatcher->dispatch($command);
    }

    /**
     * @param CommandInterface $command
     * @return mixed
     * @deprecated Use dispatchCommand()
     */
    protected function handleCommand(CommandInterface $command)
    {
        return $this->dispatchCommand($command);
    }
}
