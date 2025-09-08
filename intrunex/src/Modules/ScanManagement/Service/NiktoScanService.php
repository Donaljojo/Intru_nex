<?php
// src/Modules/ScanManagement/Service/NiktoScanService.php

namespace App\Modules\ScanManagement\Service;

use App\Modules\ScanManagement\Entity\ScanJob;
use Doctrine\ORM\EntityManagerInterface;
use App\Modules\AssetDiscovery\Entity\Asset;

class NiktoScanService
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function scanAsset(Asset $asset)
    {
        $scanJob = new ScanJob();
        $scanJob->setAssetId($asset->getId());
        $scanJob->setStatus('running');
        $scanJob->setStartedAt(new \DateTime());
        $this->em->persist($scanJob);
        $this->em->flush();

        // Build the Nikto command
        $target = $asset->getIp(); // Or get URL, hostname
        $outputFile = sys_get_temp_dir() . '/nikto_' . uniqid() . '.json';
        $command = sprintf(
            'nikto -h %s -Format json -o %s',
            escapeshellarg($target),
            escapeshellarg($outputFile)
        );

        // Execute Nikto
        $exitCode = null;
        exec($command, $output, $exitCode);

        // Read and save the result
        $result = file_exists($outputFile) ? file_get_contents($outputFile) : null;
        $scanJob->setResult($result);
        $scanJob->setStatus($exitCode === 0 ? 'completed' : 'failed');
        $scanJob->setFinishedAt(new \DateTime());
        $this->em->flush();

        // Clean up temp file
        if ($result) unlink($outputFile);

        return $scanJob;
    }
}
