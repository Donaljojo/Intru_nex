<?php

namespace App\DataFixtures;

use App\Modules\AuditLogging\Entity\ActivityLog;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ActivityLogFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 0; $i < 5; $i++) {
            $activityLog = new ActivityLog();
            $activityLog->setMessage('User logged in');
            $activityLog->setStatus('Success');
            $activityLog->setCreatedAt(new \DateTimeImmutable());
            $manager->persist($activityLog);
        }

        $manager->flush();
    }
}
