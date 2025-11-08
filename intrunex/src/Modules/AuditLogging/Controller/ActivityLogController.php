<?php

namespace App\Modules\AuditLogging\Controller;

use App\Modules\AuditLogging\Repository\ActivityLogRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('ROLE_USER')]
class ActivityLogController extends AbstractController
{
    #[Route('/audit/log', name: 'audit_log_index')]
    public function index(ActivityLogRepository $activityLogRepository): Response
    {
        $logs = $activityLogRepository->findBy([], ['createdAt' => 'DESC']);

        return $this->render('Modules/AuditLogging/index.html.twig', [
            'logs' => $logs,
        ]);
    }
}