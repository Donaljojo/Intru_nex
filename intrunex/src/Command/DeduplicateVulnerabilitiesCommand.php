<?php

namespace App\Command;

use App\Modules\AssetVulnerability\Entity\Vulnerability;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:deduplicate-vulnerabilities',
    description: 'Deduplicates vulnerabilities in the database.',
)]
class DeduplicateVulnerabilitiesCommand extends Command
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct();
        $this->em = $em;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Deduplicating Vulnerabilities');

        $vulnerabilityRepository = $this->em->getRepository(Vulnerability::class);
        $allVulnerabilities = $vulnerabilityRepository->findAll();

        $groupedVulnerabilities = [];
        foreach ($allVulnerabilities as $vulnerability) {
            $key = $vulnerability->getAsset()->getId() . '-' . $vulnerability->getDescription();
            $groupedVulnerabilities[$key][] = $vulnerability;
        }

        $duplicatesRemoved = 0;
        foreach ($groupedVulnerabilities as $group) {
            if (count($group) > 1) {
                // Sort by discovered date, oldest first
                usort($group, fn($a, $b) => $a->getDiscoveredAt() <=> $b->getDiscoveredAt());

                // Keep the first one, remove the rest
                for ($i = 1; $i < count($group); $i++) {
                    $this->em->remove($group[$i]);
                    $duplicatesRemoved++;
                }
            }
        }

        $this->em->flush();

        $io->success(sprintf('Removed %d duplicate vulnerabilities.', $duplicatesRemoved));

        return Command::SUCCESS;
    }
}
