<?php

namespace Detail\Commanding\Service;

use Detail\Commanding\CommandDispatcherInterface;

interface CommandDispatcherAwareInterface
{
    /**
     * @param CommandDispatcherInterface $commands
     */
    public function setCommands(CommandDispatcherInterface $commands);
}
