<?php

namespace Detail\Commanding;

use Detail\Commanding\Command\CommandInterface;

interface CommandDispatcherInterface
{
    public function register($commandName, $commandHandler);

    public function handle(CommandInterface $command);
}
