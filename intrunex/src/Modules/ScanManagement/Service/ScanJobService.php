<?php

namespace App\Modules\ScanManagement\Service;

use App\Modules\AssetDiscovery\Entity\Asset;
use Doctrine\ORM\EntityManagerInterface;

class ScanJobService
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function fetchAsset(int $assetId): ?Asset
    {
        return $this->em->getRepository(Asset::class)->find($assetId);
    }
}
