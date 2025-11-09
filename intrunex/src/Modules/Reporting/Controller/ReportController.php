<?php

namespace App\Modules\Reporting\Controller;

use App\Modules\AssetDiscovery\Entity\Asset;
use App\Modules\AssetVulnerability\Entity\Vulnerability;
use App\Modules\ScanManagement\Entity\ScanJob;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;
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

    #[Route('/reports/asset/{id}/export/csv', name: 'report_export_csv')]
    public function exportAssetReportCsv(Asset $asset, EntityManagerInterface $em): StreamedResponse
    {
        $user = $this->getUser();

        if ($asset->getUser() !== $user) {
            $this->addFlash('error', 'You are not authorized to export this report.');
            return $this->redirectToRoute('report_index');
        }

        $vulnerabilities = $em->getRepository(Vulnerability::class)->findBy(['asset' => $asset]);
        $profilingData = $asset->getProfilingData();

        $response = new StreamedResponse();
        $response->setCallback(function () use ($asset, $vulnerabilities, $profilingData) {
            $handle = fopen('php://output', 'w+');

            // Add Asset Details
            fputcsv($handle, ['Asset Details']);
            fputcsv($handle, ['Field', 'Value']);
            fputcsv($handle, ['Name', $asset->getName()]);
            fputcsv($handle, ['IP Address', $asset->getIpAddress() ?? 'N/A']);
            fputcsv($handle, ['URL', $asset->getUrl() ?? 'N/A']);
            fputcsv($handle, ['Domain', $asset->getDomain() ?? 'N/A']);
            fputcsv($handle, ['Type', $asset->getType() ?? 'Unknown']);
            fputcsv($handle, ['Status', $asset->getStatus()]);
            fputcsv($handle, ['Last Profiled At', $asset->getLastProfiledAt() ? $asset->getLastProfiledAt()->format('Y-m-d H:i:s') : 'Never']);
            fputcsv($handle, []); // Empty row for separation

            // Add Profiling Data
            fputcsv($handle, ['Profiling Data']);
            if ($profilingData) {
                fputcsv($handle, ['Field', 'Value']);
                fputcsv($handle, ['Operating System', $profilingData->getOperatingSystem() ?? 'N/A']);
                fputcsv($handle, ['Open Ports', $profilingData->getOpenPorts() ? implode(', ', $profilingData->getOpenPorts()) : 'N/A']);
                fputcsv($handle, ['Last Profiled At', $profilingData->getLastProfiledAt() ? $profilingData->getLastProfiledAt()->format('Y-m-d H:i:s') : 'Never']);
            } else {
                fputcsv($handle, ['No profiling data available.']);
            }
            fputcsv($handle, []); // Empty row for separation

            // Add Vulnerability Scan Results
            fputcsv($handle, ['Vulnerability Scan Results']);
            if (!empty($vulnerabilities)) {
                fputcsv($handle, ['CVE ID', 'Description', 'Severity', 'Discovered At', 'Status']);
                foreach ($vulnerabilities as $vulnerability) {
                    fputcsv($handle, [
                        $vulnerability->getCveId(),
                        $vulnerability->getDescription(),
                        $vulnerability->getSeverity(),
                        $vulnerability->getDiscoveredAt() ? $vulnerability->getDiscoveredAt()->format('Y-m-d') : 'N/A',
                        $vulnerability->getStatus(),
                    ]);
                }
            } else {
                fputcsv($handle, ['No vulnerabilities found for this asset.']);
            }

            fclose($handle);
        });

        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment; filename="asset_report_' . $asset->getId() . '.csv"');

        return $response;
    }
}