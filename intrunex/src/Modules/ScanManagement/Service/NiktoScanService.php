<?php

namespace App\Modules\ScanManagement\Service;

use App\Modules\ScanManagement\Entity\ScanJob;
use App\Modules\AssetDiscovery\Entity\Asset;
use App\Modules\AssetVulnerability\Entity\Vulnerability;
use Doctrine\ORM\EntityManagerInterface;

class NiktoScanService
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function scanAsset(Asset $asset): ScanJob
    {
        $repo = $this->em->getRepository(ScanJob::class);

        // Find existing active jobs (pending, running) for this asset
        $existingJobs = $repo->createQueryBuilder('j')
            ->where('j.asset = :asset')
            ->andWhere('j.status IN (:activeStatuses)')
            ->setParameter('asset', $asset)
            ->setParameter('activeStatuses', ['pending', 'running'])
            ->getQuery()
            ->getResult();

        // Cancel existing active jobs
        foreach ($existingJobs as $job) {
            $job->setStatus('cancelled');
        }
        $this->em->flush();

        // Create new scan job
        $scanJob = new ScanJob();
        $scanJob->setAsset($asset);
        $scanJob->setStatus('running');
        $scanJob->setStartedAt(new \DateTime());
        $this->em->persist($scanJob);
        $this->em->flush();

        // Select scan target dynamically: url > domain > ipAddress
        if ($asset->getUrl()) {
            $target = $asset->getUrl();
        } elseif ($asset->getDomain()) {
            $target = $asset->getDomain();
        } elseif ($asset->getIpAddress()) {
            $target = $asset->getIpAddress();
        } else {
            throw new \RuntimeException('No valid target (IP, URL, or domain) found for asset ID ' . $asset->getId());
        }

        $outputFile = sys_get_temp_dir() . '/nikto_' . uniqid() . '.txt';

        $command = sprintf(
            'nikto -h %s -o %s',
            escapeshellarg($target),
            escapeshellarg($outputFile)
        );

        $exitCode = null;
        exec($command, $output, $exitCode);

        $result = null;
        if (file_exists($outputFile)) {
            $result = file_get_contents($outputFile);
        }

        $scanJob->setResult($result);
        $scanJob->setStatus($exitCode === 0 ? 'completed' : 'failed');
        $scanJob->setCompletedAt(new \DateTime());
        $this->em->flush();

        if ($result) {
            unlink($outputFile);
        }

        $this->parseScanResult($result, $asset);

        return $scanJob;
    }

    private function parseScanResult(?string $reportText, Asset $asset): void
    {
        if (!$reportText) {
            return;
        }

        preg_match_all('/(OSVDB-\d+|CVE-\d{4}-\d+)/', $reportText, $matches);
        $vulnIds = array_unique($matches[0]);

        if (empty($vulnIds)) {
            return;
        }

        $vulnerabilityRepo = $this->em->getRepository(Vulnerability::class);

        foreach ($vulnIds as $vulnId) {
            $existing = $vulnerabilityRepo->findOneBy([
                'asset' => $asset,
                'cveId' => $vulnId,
            ]);

            if ($existing) {
                continue;
            }

            $vulnerability = new Vulnerability();
            $vulnerability->setAsset($asset);
            $vulnerability->setCveId($vulnId);
            $vulnerability->setDescription('Imported from Nikto scan');
            $vulnerability->setSeverity('unknown');
            $vulnerability->setDiscoveredAt(new \DateTime());
            $vulnerability->setStatus('open');

            $this->em->persist($vulnerability);
        }

        $this->em->flush();
    }
}



