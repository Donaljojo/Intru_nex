<?php

namespace App\Modules\ScanManagement\MessageHandler;

use App\Modules\ScanManagement\Message\ScanJobMessage;
use App\Modules\ScanManagement\Service\ScanJobService;
use App\Modules\VulnerabilityDetection\Service\NiktoScanService;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
class ScanJobHandler
{
    private ScanJobService $scanJobService;
    private NiktoScanService $niktoScanService;

    public function __construct(ScanJobService $scanJobService, NiktoScanService $niktoScanService)
    {
        $this->scanJobService = $scanJobService;
        $this->niktoScanService = $niktoScanService;
    }

    public function __invoke(ScanJobMessage $message)
    {
        $asset = $this->scanJobService->fetchAsset($message->getAssetId());

        if (!$asset) {
            throw new \RuntimeException('Asset not found for id ' . $message->getAssetId());
        }

        // Run Nikto scan via VulnerabilityDetection module
        $scanJob = $this->niktoScanService->scanAsset($asset);

        // You can add further steps here, like notify user or update status.
    }
}
