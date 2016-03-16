<?php

namespace Detail\Commanding\Handler;

use Detail\Commanding\Command\CommandInterface;
use Detail\Commanding\Exception;

abstract class SimpleCommandHandler implements
    CommandHandlerInterface
{
    public function handle(CommandInterface $command)
    {
        $commandClass = $this->getCommandClass();

        if (!$command instanceof $commandClass) {
            throw new Exception\RuntimeException(
                sprintf(
                    'Plugin of type %s is invalid; must implement %s\CommandHandlerInterface',
                    (is_object($command) ? get_class($command) : gettype($command)),
                    __NAMESPACE__
                )
            );
        }

        return $this->handleCommand($command);
    }

    abstract protected function handleCommand(CommandInterface $command);

    abstract protected function getCommandClass();
}
