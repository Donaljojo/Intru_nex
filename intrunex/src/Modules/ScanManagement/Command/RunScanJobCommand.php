<?php

namespace App\Modules\ScanManagement\Command;

use App\Modules\ScanManagement\Message\ScanJobMessage;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;

class RunScanJobCommand extends Command
{
    protected static $defaultName = 'scan:run';

    private MessageBusInterface $messageBus;

    public function __construct(MessageBusInterface $messageBus)
    {
        parent::__construct();

        $this->messageBus = $messageBus;
    }

    protected function configure(): void
    {
        $this
            ->setDescription('Dispatches a scan job for the specified asset ID')
            ->addArgument('assetId', InputArgument::REQUIRED, 'The ID of the asset to scan');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $assetId = (int) $input->getArgument('assetId');

        // Dispatch scan job message
        $this->messageBus->dispatch(new ScanJobMessage($assetId));

        $output->writeln(sprintf('Scan job dispatched for asset ID %d', $assetId));

        return Command::SUCCESS;
    }
}
