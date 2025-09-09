<?php

namespace App\Modules\ScanManagement\MessageHandler;

use App\Modules\ScanManagement\Message\NiktoScanMessage;
use App\Modules\AssetDiscovery\Entity\Asset;
use App\Modules\ScanManagement\Entity\ScanJob;
use App\Modules\ScanManagement\Service\NiktoScanService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Psr\Log\LoggerInterface;

class NiktoScanHandler implements MessageHandlerInterface
{
    private EntityManagerInterface $em;
    private NiktoScanService $niktoScanService;
    private LoggerInterface $logger;

    public function __construct(EntityManagerInterface $em, NiktoScanService $niktoScanService, LoggerInterface $logger)
    {
        $this->em = $em;
        $this->niktoScanService = $niktoScanService;
        $this->logger = $logger;
    }
    public function __invoke(NiktoScanMessage $message)
{
    $asset = $this->em->getRepository(Asset::class)->find($message->getAssetId());
    if (!$asset) {
        $this->logger->error('Asset not found for scan: ' . $message->getAssetId());
        return;
    }

    $scanJob = new ScanJob();
    $scanJob->setAsset($asset);
    $scanJob->setStatus('running');
    $scanJob->setStartedAt(new \DateTime());
    $this->em->persist($scanJob);
    $this->em->flush();

    try {
        $this->niktoScanService->scanAsset($asset);
        $scanJob->setStatus('completed');
    } catch (\Throwable $e) {
        $scanJob->setStatus('failed');
        $scanJob->setErrorMessage($e->getMessage());
        $this->logger->error('Scan failed: ' . $e->getMessage());
    }

    $scanJob->setCompletedAt(new \DateTime());
    $this->em->flush();
}


    
}
