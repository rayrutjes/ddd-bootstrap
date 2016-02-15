<?php

namespace RayRutjes\DddBootstrap\Messaging\Command;

use RayRutjes\DddEssentials\Messaging\Command\Command;
use RayRutjes\DddEssentials\Messaging\Command\CommandBus;
use RayRutjes\DddEssentials\Messaging\Command\CommandHandler;

final class InMemoryCommandBus implements CommandBus
{
    /**
     * @var array
     */
    private $handlers = [];

    /**
     * @param Command $command
     *
     * @return mixed
     */
    public function dispatch(Command $command)
    {
        return $this->resolveHandlerFor($command)->handle($command);
    }

    /**
     * @param Command $command
     *
     * @return CommandHandler
     */
    private function resolveHandlerFor(Command $command)
    {
        $commandName = get_class($command);
        if (!isset($this->handlers[$commandName])) {
            throw new \RuntimeException(sprintf('No command handler was found for: %s', $commandName));
        }

        return $this->handlers[$commandName];
    }

    /**
     * @param string         $commandName
     * @param CommandHandler $handler
     */
    public function subscribe(string $commandName, CommandHandler $handler)
    {
        if (!class_exists($commandName)) {
            throw new \InvalidArgumentException(sprintf('%s command does not exist.', $commandName));
        }

        if (!is_subclass_of($commandName, Command::class)) {
            throw new \InvalidArgumentException(sprintf('%s does not implement the %s interface.', $commandName, Command::class));
        }

        if (isset($this->handlers[$commandName])) {
            throw new \InvalidArgumentException(sprintf('%s was already bound to a handler.', $commandName));
        }
        $this->handlers[$commandName] = $handler;
    }
}
