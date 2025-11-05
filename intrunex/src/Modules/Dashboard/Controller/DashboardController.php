<?php

namespace App\Modules\Dashboard\Controller;

use App\Modules\AssetDiscovery\Entity\Asset;
use App\Modules\AssetVulnerability\Entity\Vulnerability;
use App\Modules\ScanManagement\Entity\ScanJob;
use App\Modules\ScanManagement\Message\ScanJobMessage; // Use generic ScanJobMessage now
use App\Modules\AuditLogging\Entity\ActivityLog;
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

        // Count assets owned by current user
        $assetCount = $em->getRepository(Asset::class)->count(['user' => $user]);

        // Count active and inactive assets
        $activeAssetsCount = $em->getRepository(Asset::class)->count(['user' => $user, 'status' => 'Active']);
        $inactiveAssetsCount = $assetCount - $activeAssetsCount;

        // Count vulnerable and safe assets
        $qbVulnerableAssets = $em->createQueryBuilder()
            ->select('COUNT(DISTINCT a.id)')
            ->from(Vulnerability::class, 'v')
            ->join('v.asset', 'a')
            ->where('a.user = :user')
            ->setParameter('user', $user);
        $vulnerableAssetsCount = (int) $qbVulnerableAssets->getQuery()->getSingleScalarResult();
        $safeAssetsCount = $assetCount - $vulnerableAssetsCount;

        // Count vulnerabilities related to user's assets
        $qbVulnCount = $em->createQueryBuilder()
            ->select('COUNT(v.id)')
            ->from(Vulnerability::class, 'v')
            ->join('v.asset', 'a')
            ->where('a.user = :user')
            ->setParameter('user', $user);
        $vulnerabilityCount = (int) $qbVulnCount->getQuery()->getSingleScalarResult();

        // Assets owned by current user
        $assets = $em->getRepository(Asset::class)->findBy(['user' => $user]);

        // Recent scan jobs for user's assets
        $qbScanJobs = $em->createQueryBuilder()
            ->select('sj')
            ->from(ScanJob::class, 'sj')
            ->join('sj.asset', 'a')
            ->where('a.user = :user')
            ->setParameter('user', $user)
            ->orderBy('sj.startedAt', 'DESC')
            ->setMaxResults(10);
        $scanJobs = $qbScanJobs->getQuery()->getResult();

        $activityLog = $em->getRepository(ActivityLog::class)->findBy([], ['createdAt' => 'DESC'], 5);

        // Recent vulnerabilities for user's assets
        $qbRecentVulnerabilities = $em->createQueryBuilder()
            ->select('v')
            ->from(Vulnerability::class, 'v')
            ->join('v.asset', 'a')
            ->where('a.user = :user')
            ->setParameter('user', $user)
            ->orderBy('v.discoveredAt', 'DESC')
            ->setMaxResults(5);
        $recentVulnerabilities = $qbRecentVulnerabilities->getQuery()->getResult();

        return $this->render('dashboard/index.html.twig', [
            'assetCount' => $assetCount,
            'vulnerabilityCount' => $vulnerabilityCount,
            'activeAssetsCount' => $activeAssetsCount,
            'inactiveAssetsCount' => $inactiveAssetsCount,
            'vulnerableAssetsCount' => $vulnerableAssetsCount,
            'safeAssetsCount' => $safeAssetsCount,
            'assets' => $assets,
            'scanJobs' => $scanJobs,
            'activity_log' => $activityLog,
            'recentVulnerabilities' => $recentVulnerabilities,
        ]);
    }

    #[Route('/dashboard/asset/{id}/scan', name: 'dashboard_asset_scan', methods: ['POST'])]
    #[IsGranted('ROLE_USER')]
    public function scanAsset(Request $request, Asset $asset, EntityManagerInterface $em, MessageBusInterface $bus): Response
    {
        // Security check to ensure the asset belongs to the current user
        if ($asset->getUser() !== $this->getUser()) {
            $this->addFlash('error', 'You are not authorized to scan this asset.');
            return $this->redirectToRoute('dashboard');
        }

        // Create and dispatch a scan job message
        $scanJob = new ScanJob();
        $scanJob->setAsset($asset);
        $scanJob->setStartedAt(new \DateTimeImmutable());
        $scanJob->setStatus('Pending');
        $em->persist($scanJob);
        $em->flush();

        $bus->dispatch(new ScanJobMessage($scanJob->getId()));

        $this->addFlash('success', 'Scan started for asset: ' . $asset->getName());

        return $this->redirectToRoute('dashboard');
    }
}
