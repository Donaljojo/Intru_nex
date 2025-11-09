<?php

namespace App\Modules\Analytics\Controller;

use App\Modules\AssetDiscovery\Repository\AssetRepository;
use App\Modules\AssetVulnerability\Repository\VulnerabilityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class AnalyticsController extends AbstractController
{
    #[Route('/analytics', name: 'analytics')]
    #[IsGranted('ROLE_USER')]
    public function index(AssetRepository $assetRepository, VulnerabilityRepository $vulnerabilityRepository): Response
    {
        $assetCount = $assetRepository->count([]);
        $activeAssetsCount = $assetRepository->count(['status' => 'Active']);
        $inactiveAssetsCount = $assetRepository->count(['status' => 'Inactive']);
        $monitoredAssetsCount = $assetRepository->count(['isMonitored' => true]);
        $unmonitoredAssetsCount = $assetRepository->count(['isMonitored' => false]);
        $vulnerableAssetsCount = $vulnerabilityRepository->countVulnerableAssets();
        $safeAssetsCount = $assetCount - $vulnerableAssetsCount;

        return $this->render('analytics/index.html.twig', [
            'assetCount' => $assetCount,
            'activeAssetsCount' => $activeAssetsCount,
            'inactiveAssetsCount' => $inactiveAssetsCount,
            'monitoredAssetsCount' => $monitoredAssetsCount,
            'unmonitoredAssetsCount' => $unmonitoredAssetsCount,
            'vulnerableAssetsCount' => $vulnerableAssetsCount,
            'safeAssetsCount' => $safeAssetsCount,
        ]);
    }
}
