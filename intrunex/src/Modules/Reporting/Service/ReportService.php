<?php

namespace App\Modules\Reporting\Service;

use App\Modules\AssetDiscovery\Entity\Asset;
use App\Modules\AssetDiscovery\Service\AssetProfilingService;
use App\Modules\AssetVulnerability\Entity\Vulnerability;
use Doctrine\ORM\EntityManagerInterface;

class ReportService
{
    private EntityManagerInterface $em;
    private AssetProfilingService $assetProfilingService;

    public function __construct(EntityManagerInterface $em, AssetProfilingService $assetProfilingService)
    {
        $this->em = $em;
        $this->assetProfilingService = $assetProfilingService;
    }

    public function generateAssetReport(Asset $asset): array
    {
        // Get profiling data (from asset description, as updated by AssetProfilingService)
        $profilingData = $asset->getDescription();

        // Get scanning data (vulnerabilities)
        $vulnerabilities = $this->em->getRepository(Vulnerability::class)->findBy(['asset' => $asset]);

        return [
            'asset' => $asset,
            'profilingData' => $profilingData,
            'vulnerabilities' => $vulnerabilities,
        ];
    }
}
