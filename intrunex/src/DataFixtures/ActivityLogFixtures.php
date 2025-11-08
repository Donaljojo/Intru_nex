<?php

namespace App\DataFixtures;

use App\Modules\AuditLogging\Entity\ActivityLog;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ActivityLogFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        // Dummy data removed
    }
}
