<?php

namespace Detail\Commanding;

use Detail\Commanding\Command\CommandInterface;
use Detail\Commanding\Handler\CommandHandlerInterface;

interface CommandDispatcherInterface
{
    /**
     * @param string $commandName
     * @param CommandHandlerInterface|string $commandHandler
     * @return void
     */
    public function register($commandName, $commandHandler);

    /**
     * @param CommandInterface $command
     * @return mixed
     */
    public function dispatch(CommandInterface $command);

    /**
     * @param CommandInterface $command
     * @return mixed
     * @deprecated Use dispatch()
     */
    public function handle(CommandInterface $command);
}
