<?php

namespace Detail\Commanding\Handler;

use Detail\Commanding\Command\CommandInterface;
use Detail\Commanding\Exception;

abstract class SimpleCommandHandler implements
    CommandHandlerInterface
{
    /**
     * @param CommandInterface $command
     * @return mixed
     */
    public function handle(CommandInterface $command)
    {
        $commandClass = $this->getCommandClass();

        if (!$command instanceof $commandClass) {
            throw new Exception\RuntimeException(
                sprintf('Handler only accepts commands of type %s', $commandClass)
            );
        }

        return $this->handleCommand($command);
    }

    /**
     * @param CommandInterface $command
     * @return mixed
     */
    abstract protected function handleCommand(CommandInterface $command);

    /**
     * @return string
     */
    abstract protected function getCommandClass();
}
