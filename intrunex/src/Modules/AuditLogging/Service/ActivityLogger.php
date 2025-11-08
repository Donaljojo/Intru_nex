<?php

namespace App\Modules\AuditLogging\Service;

use App\Modules\AuditLogging\Entity\ActivityLog;
use Doctrine\ORM\EntityManagerInterface;

class ActivityLogger
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function log(string $message, string $status = 'Success'): void
    {
        $activityLog = new ActivityLog();
        $activityLog->setMessage($message);
        $activityLog->setStatus($status);
        $activityLog->setCreatedAt(new \DateTimeImmutable());

        $this->entityManager->persist($activityLog);
        $this->entityManager->flush();
    }
}
