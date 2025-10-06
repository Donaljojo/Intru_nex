<?php

namespace App\Modules\AssetDiscovery\MessageHandler;

use App\Modules\AssetDiscovery\Entity\Asset;
use App\Modules\AssetDiscovery\Message\ProfileAssetMessage;
use App\Modules\AssetDiscovery\Service\AssetProfilingService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
final class ProfileAssetMessageHandler
{
    private EntityManagerInterface $em;
    private AssetProfilingService $profilingService;

    public function __construct(EntityManagerInterface $em, AssetProfilingService $profilingService)
    {
        $this->em = $em;
        $this->profilingService = $profilingService;
    }

    public function __invoke(ProfileAssetMessage $message): void
    {
        $asset = $this->em->getRepository(Asset::class)->find($message->getAssetId());

        if (!$asset) {
            // Handle case where asset is not found, maybe log it
            return;
        }

        $this->profilingService->performProfiling($asset);
    }
}
