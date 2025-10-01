<?php

namespace App\Modules\Dashboard\Controller;

use App\Modules\AssetDiscovery\Entity\Asset;
use App\Modules\AssetVulnerability\Entity\Vulnerability;
use App\Modules\ScanManagement\Entity\ScanJob;
use App\Modules\ScanManagement\Message\ScanJobMessage; // Use generic ScanJobMessage now
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

        return $this->render('dashboard/index.html.twig', [
            'assetCount' => $assetCount,
            'vulnerabilityCount' => $vulnerabilityCount,
            'assets' => $assets,
            'scanJobs' => $scanJobs,
        ]);
    }
}
