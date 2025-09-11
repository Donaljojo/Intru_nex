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
        $user = $this->getUser();

        // Count assets and vulnerabilities owned by current user
        $assetCount = $em->getRepository(Asset::class)->count(['user' => $user]);
        
        // Count vulnerabilities through join on Asset with current user
        $qbVulnCount = $em->createQueryBuilder()
            ->select('COUNT(v.id)')
            ->from(Vulnerability::class, 'v')
            ->join('v.asset', 'a')
            ->where('a.user = :user')
            ->setParameter('user', $user);
        $vulnerabilityCount = (int) $qbVulnCount->getQuery()->getSingleScalarResult();

        // Assets owned by current user
        $assets = $em->getRepository(Asset::class)->findBy(['user' => $user]);

        // ScanJobs related to assets owned by current user
        $qbScanJobs = $em->createQueryBuilder()
            ->select('sj')
            ->from(ScanJob::class, 'sj')
            ->join('sj.asset', 'a')
            ->where('a.user = :user')
            ->setParameter('user', $user)
            ->orderBy('sj.startedAt', 'DESC')
            ->setMaxResults(10);
        $scanJobs = $qbScanJobs->getQuery()->getResult();

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
        // Check asset ownership before dispatching scan
        if ($asset->getUser() !== $this->getUser()) {
            $this->addFlash('error', 'You do not have permission to scan this asset.');
            return $this->redirectToRoute('dashboard');
        }

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




