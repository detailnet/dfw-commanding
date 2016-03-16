<?php

namespace Detail\Commanding\Handler;

use Detail\Commanding\Service\CommandDispatcherAwareInterface;
use Detail\Commanding\Service\CommandDispatcherAwareTrait;

abstract class ExtendedCommandHandler extends SimpleCommandHandler implements
    CommandDispatcherAwareInterface
{
    use CommandDispatcherAwareTrait {
        handleCommand as handleSubCommand;
    }
}
