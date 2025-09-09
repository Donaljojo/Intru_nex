<?php

namespace App\Modules\Dashboard\Controller;

use App\Modules\AssetDiscovery\Entity\Asset;
use App\Modules\AssetVulnerability\Entity\Vulnerability;
use App\Modules\ScanManagement\Entity\ScanJob;
use App\Modules\ScanManagement\Message\NiktoScanMessage;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Messenger\MessageBusInterface;

class DashboardController extends AbstractController
{
    #[Route('/dashboard', name: 'dashboard')]
    #[IsGranted('ROLE_USER')]
    public function index(EntityManagerInterface $em): Response
    {
        // Count total assets and vulnerabilities
        $assetCount = $em->getRepository(Asset::class)->count([]);
        $vulnerabilityCount = $em->getRepository(Vulnerability::class)->count([]);

        // Fetch all assets for scan selection
        $assets = $em->getRepository(Asset::class)->findAll();

        // Fetch last 10 scan jobs ordered by most recent
        $scanJobs = $em->getRepository(ScanJob::class)
            ->findBy([], ['startedAt' => 'DESC'], 10);

        return $this->render('dashboard/index.html.twig', [
            'assetCount' => $assetCount,
            'vulnerabilityCount' => $vulnerabilityCount,
            'assets' => $assets,
            'scanJobs' => $scanJobs,
        ]);
    }

    #[Route('/dashboard/asset/{id}/scan', name: 'dashboard_asset_scan', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function scanAsset(Request $request, Asset $asset, MessageBusInterface $bus): Response
    {
        if (!$this->isCsrfTokenValid('scan-asset' . $asset->getId(), $request->request->get('_token'))) {
            $this->addFlash('error', 'Invalid CSRF token.');
            return $this->redirectToRoute('dashboard');
        }

        try {
            $bus->dispatch(new NiktoScanMessage($asset->getId()));
            $this->addFlash('success', 'Scan job dispatched successfully for asset: ' . $asset->getName());
        } catch (\Exception $e) {
            $this->addFlash('error', 'Failed to dispatch scan: ' . $e->getMessage());
        }

        return $this->redirectToRoute('vulnerability_list');
    }
}



