<?php
// src/Modules/ScanManagement/Command/RunNiktoScanCommand.php

namespace App\Modules\ScanManagement\Command;

use App\Modules\AssetDiscovery\Entity\Asset;
use App\Modules\ScanManagement\Service\NiktoScanService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RunNiktoScanCommand extends Command
{
    protected static $defaultName = 'scan:nikto';

    private $em;
    private $niktoScanService;

    public function __construct(EntityManagerInterface $em, NiktoScanService $niktoScanService)
    {
        parent::__construct();
        $this->em = $em;
        $this->niktoScanService = $niktoScanService;
    }

    protected function configure()
    {
        $this->addArgument('asset-id', InputArgument::REQUIRED, 'ID of the asset to scan');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $assetId = $input->getArgument('asset-id');
        $asset = $this->em->getRepository(Asset::class)->find($assetId);

        if (!$asset) {
            $output->writeln('Asset not found.');
            return Command::FAILURE;
        }

        $output->writeln('Starting Nikto scan...');
        $scanJob = $this->niktoScanService->scanAsset($asset);

        $output->writeln('Scan finished with status: ' . $scanJob->getStatus());
        if ($scanJob->getResult()) {
            $output->writeln('Scan result:');
            $output->writeln($scanJob->getResult());
        }
        return Command::SUCCESS;
    }
}
