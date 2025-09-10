<?php

namespace App\Modules\ScanManagement\Controller;

use App\Modules\ScanManagement\Entity\ScanJob;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
class ScanJobController extends AbstractController
{
    #[Route('/scan-jobs', name: 'scan_job_list')]
    public function list(EntityManagerInterface $em): Response
    {
        $scanJobs = $em->getRepository(ScanJob::class)
            ->findBy([], ['startedAt' => 'DESC'], 50);

        return $this->render('scan_management/scan_jobs.html.twig', [
            'scanJobs' => $scanJobs,
        ]);
    }

    #[Route('/scan-job/{id}/progress', name: 'scan_progress')]
    public function progress(ScanJob $scanJob): Response
    {
        return $this->render('scan_management/scan_progress.html.twig', [
            'scanJob' => $scanJob,
        ]);
    }

    #[Route('/scan-job/{id}/status', name: 'scan_progress_api')]
    public function scanStatus(ScanJob $scanJob): JsonResponse
    {
        return new JsonResponse([
            'status' => $scanJob->getStatus(),
        ]);
    }

    #[Route('/scan-job/{id}/cancel', name: 'scan_cancel', methods: ['POST'])]
    public function cancelScan(Request $request, ScanJob $scanJob, EntityManagerInterface $em): Response
    {
        if (!$this->isCsrfTokenValid('cancel_scan' . $scanJob->getId(), $request->headers->get('X-CSRF-TOKEN'))) {
            return new Response('Invalid CSRF token', 400);
        }

        if ($scanJob->getStatus() === 'running') {
            $scanJob->setStatus('cancelled');
            $em->flush();

            return new Response('Cancelled', 200);
        }

        return new Response('Scan is not running', 400);
    }
    #[Route('/scan-job/{id}/delete', name: 'scan_delete', methods: ['POST'])]
    public function deleteScan(Request $request, ScanJob $scanJob, EntityManagerInterface $em): Response
    {
    if (!$this->isCsrfTokenValid('delete_scan' . $scanJob->getId(), $request->request->get('_token'))) {
        $this->addFlash('error', 'Invalid CSRF token.');
        return $this->redirectToRoute('scan_progress', ['id' => $scanJob->getId()]);
    }

    $em->remove($scanJob);
    $em->flush();

    $this->addFlash('success', 'Scan job deleted.');

    //return $this->redirectToRoute('dashboard');
    // Redirect to scan job list page instead of dashboard
    return $this->redirectToRoute('scan_job_list');
    }
    
}
