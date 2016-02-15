<?php

namespace RayRutjes\DddBootstrap\Tests\Messaging\Command;

use RayRutjes\DddBootstrap\Messaging\Command\InMemoryCommandBus;

class InMemoryCommandBusTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @expectedException \InvalidArgumentException
     */
    public function testCanNotSubscribeToANonExistantCommand()
    {
        $bus = new InMemoryCommandBus();
        $bus->subscribe('NotExistant', $this->getCommandHandlerMock());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testCanNotSubscribeToAInvalidCommand()
    {
        $bus = new InMemoryCommandBus();
        $bus->subscribe(CommandHandlerStub::class, $this->getCommandHandlerMock());
    }

    /**
     * @expectedException \InvalidArgumentException
     */
    public function testCanNotSubscribeMultipleHandlersToTheSameCommand()
    {
        $bus = new InMemoryCommandBus();
        $bus->subscribe(CommandStub::class, $this->getCommandHandlerMock());
        $bus->subscribe(CommandStub::class, $this->getCommandHandlerMock());
    }

    public function testCanDelegateHandlingOfACommand()
    {
        $command = new CommandStub();

        $handler = $this->getCommandHandlerMock();
        $handler->expects($this->once())
                ->method('handle')
                ->with($command)
                ->willReturn('result');

        $bus = new InMemoryCommandBus();
        $bus->subscribe(CommandStub::class, $handler);
        $result = $bus->dispatch($command);

        $this->assertEquals('result', $result);
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testShouldFailIfNoHandlerCanBeResolved()
    {
        $command = new CommandStub();
        $bus = new InMemoryCommandBus();
        $bus->dispatch($command);
    }

    /**
     * @return \PHPUnit_Framework_MockObject_MockObject
     */
    private function getCommandHandlerMock()
    {
        return $this->getMock('RayRutjes\DddEssentials\Messaging\Command\CommandHandler');
    }
}
