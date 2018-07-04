<?php

namespace Detail\Commanding\Handler;

use Detail\Commanding\Command\CommandInterface;

interface CommandHandlerInterface
{
    /**
     * @param CommandInterface $command
     * @return mixed
     */
    public function handle(CommandInterface $command);
}
