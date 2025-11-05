<?php

namespace App\Modules\Reporting\Controller;

use App\Modules\AssetDiscovery\Entity\Asset;
use App\Modules\Reporting\Service\ReportService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/report')]
class ReportController extends AbstractController
{
    private ReportService $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    #[Route('/asset/{id}', name: 'asset_report', methods: ['GET'])]
    public function assetReport(Asset $asset): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');
        if ($asset->getUser() !== $this->getUser()) {
            throw $this->createAccessDeniedException();
        }

        $reportData = $this->reportService->generateAssetReport($asset);

        return $this->render('reporting/asset_report.html.twig', $reportData);
    }
}
