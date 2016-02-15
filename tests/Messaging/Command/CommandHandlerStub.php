<?php

namespace RayRutjes\DddBootstrap\Tests\Messaging\Command;

use RayRutjes\DddEssentials\Messaging\Command\Command;
use RayRutjes\DddEssentials\Messaging\Command\CommandHandler;

final class CommandHandlerStub implements CommandHandler
{
    /**
     * @param Command $command
     *
     * @return mixed
     */
    public function handle(Command $command)
    {
    }
}
