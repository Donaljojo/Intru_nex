<?php
// src/Command/FixOpenPortsCommand.php
namespace App\Command;

use App\Modules\AssetDiscovery\Entity\Asset;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(name: 'app:fix-openports')]
class FixOpenPortsCommand extends Command
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct();
        $this->em = $em;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $repo = $this->em->getRepository(Asset::class);
        $assets = $repo->findAll();

        foreach ($assets as $asset) {
            $ports = $asset->getOpenPorts();

            if ($ports !== null) {
                // Normalize every element into string
                $fixed = array_map(fn($p) => is_array($p) ? implode(' ', $p) : (string) $p, $ports);
                $asset->setOpenPorts($fixed);
                $this->em->persist($asset);

                $output->writeln("Fixed asset #{$asset->getId()}");
            }
        }

        $this->em->flush();
        $output->writeln("✅ All assets fixed.");
        return Command::SUCCESS;
    }
}
