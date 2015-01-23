<?php

namespace Detail\Commanding\Handler;

use Detail\Commanding\Command\CommandInterface;

interface CommandHandlerInterface
{
    public function handle(CommandInterface $command);
}
