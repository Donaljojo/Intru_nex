<?php

namespace App\Command;

use App\Modules\AuditLogging\Entity\ActivityLog;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:populate-activity-log',
    description: 'Populates the activity log with some dummy data.',
)]
class PopulateActivityLogCommand extends Command
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        for ($i = 0; $i < 5; $i++) {
            $activityLog = new ActivityLog();
            $activityLog->setMessage('User logged in');
            $activityLog->setStatus('Success');
            $activityLog->setCreatedAt(new \DateTimeImmutable());
            $this->entityManager->persist($activityLog);
        }

        $this->entityManager->flush();

        $io->success('Successfully populated the activity log.');

        return Command::SUCCESS;
    }
}
