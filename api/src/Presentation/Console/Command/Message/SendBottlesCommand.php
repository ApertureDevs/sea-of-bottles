<?php

namespace App\Presentation\Console\Command\Message;

use App\Core\Component\Message\Application\SendBottles\SendBottlesCommand as SendBottlesCoreCommand;
use App\Core\Component\Message\Application\SendBottles\SendBottlesResponse;
use App\Core\Component\Message\Port\SeaProjectorInterface;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;

class SendBottlesCommand extends Command
{
    protected static $defaultName = 'app:message:send-bottles';
    private MessageBusInterface $commandBus;
    private SeaProjectorInterface $seaProjector;
    private LoggerInterface $logger;

    public function __construct(
        MessageBusInterface $commandBus,
        SeaProjectorInterface $seaProjector,
        LoggerInterface $logger,
        $name = null
    ) {
        $this->commandBus = $commandBus;
        $this->seaProjector = $seaProjector;
        $this->logger = $logger;
        parent::__construct($name);
    }

    protected function configure(): void
    {
        $this->setDescription('Send randomly sent bottles to random sailors.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $sea = $this->seaProjector->getSea();

        $output->writeln("There are <info>{$sea->getBottlesRemaining()}</info> bottle(s) remaining into the sea and <info>{$sea->getSailorsTotal()}</info> sailor(s).");
        $output->writeln('Sending randomly unsent bottles to random sailors...');

        $command = SendBottlesCoreCommand::create();
        $envelope = $this->commandBus->dispatch($command);
        $stamp = $envelope->last(HandledStamp::class);

        if (!$stamp instanceof HandledStamp) {
            throw new \RuntimeException(sprintf('Command bus return any handled stamp. Is "%s" handler missing?', SendBottlesCoreCommand::class));
        }

        $result = $stamp->getResult();

        if (!$result instanceof SendBottlesResponse) {
            throw new \RuntimeException(sprintf('Invalid command response : instance of "%s" expected.', SendBottlesResponse::class));
        }

        $output->writeln("<info>{$result->bottlesSentCount}</info> random bottle(s) were sent to random sailors.");
        $this->logger->info("{$result->bottlesSentCount} random bottle(s) were sent to random sailors.");

        return Command::SUCCESS;
    }
}
