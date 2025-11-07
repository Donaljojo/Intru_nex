<?php

namespace App\Modules\AuditLogging\Controller;

use App\Modules\AuditLogging\Entity\ActivityLog;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LogController extends AbstractController
{
    #[Route('/audit-log', name: 'audit_log')]
    public function index(EntityManagerInterface $em): Response
    {
        $logs = $em->getRepository(ActivityLog::class)->findBy([], ['createdAt' => 'DESC']);

        return $this->render('@AuditLogging/log/index.html.twig', [
            'logs' => $logs,
        ]);
    }
}
