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

    try {
        // Use NiktoScanService which now handles de-duplication and job creation
        $scanJob = $this->niktoScanService->scanAsset($asset);

        // scanAsset already sets job status and completedAt, no need to modify here
    } catch (\Throwable $e) {
        // If scanAsset throws, try to find or create failure job record
        $existingJob = $this->em->getRepository(ScanJob::class)->findOneBy([
            'asset' => $asset,
            'status' => ['pending', 'running']
        ]);

        if (!$existingJob) {
            $existingJob = new ScanJob();
            $existingJob->setAsset($asset);
            $this->em->persist($existingJob);
        }

        $existingJob->setStatus('failed');
        $existingJob->setErrorMessage($e->getMessage());
        $existingJob->setCompletedAt(new \DateTime());
        $this->em->flush();

        $this->logger->error('Scan failed: ' . $e->getMessage());
    }
}

  
}
