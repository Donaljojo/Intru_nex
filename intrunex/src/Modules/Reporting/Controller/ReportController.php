<?php

namespace App\Modules\Reporting\Controller;

use App\Modules\AssetDiscovery\Entity\Asset;
use App\Modules\AssetVulnerability\Entity\Vulnerability;
use App\Modules\ScanManagement\Entity\ScanJob;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
class ReportController extends AbstractController
{
    #[Route('/reports', name: 'report_index')]
    public function index(EntityManagerInterface $em): Response
    {
        $user = $this->getUser();

        $qbScanJobs = $em->createQueryBuilder()
            ->select('sj')
            ->from(ScanJob::class, 'sj')
            ->join('sj.asset', 'a')
            ->where('a.user = :user')
            ->setParameter('user', $user)
            ->orderBy('sj.startedAt', 'DESC');
        $scanJobs = $qbScanJobs->getQuery()->getResult();

        return $this->render('reporting/index.html.twig', [
            'scanJobs' => $scanJobs,
        ]);
    }
    #[Route('/reports/asset/{id}', name: 'asset_report')]
    public function assetReport(Asset $asset, EntityManagerInterface $em): Response
    {
        $user = $this->getUser();

        if ($asset->getUser() !== $user) {
            $this->addFlash('error', 'You are not authorized to view this report.');
            return $this->redirectToRoute('report_index');
        }

        $vulnerabilities = $em->getRepository(Vulnerability::class)->findBy(['asset' => $asset]);
        $profilingData = $asset->getProfilingData();

        return $this->render('reporting/asset_report.html.twig', [
            'asset' => $asset,
            'vulnerabilities' => $vulnerabilities,
            'profilingData' => $profilingData,
        ]);
    }
}