<?php
// src/Command/BackfillUserAssetNumberCommand.php
namespace App\Command;

use App\Modules\AssetDiscovery\Entity\Asset;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class BackfillUserAssetNumberCommand extends Command
{
    protected static $defaultName = 'app:backfill-user-asset-number';
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct();
        $this->em = $em;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $repo = $this->em->getRepository(Asset::class);
        $assetsByUser = [];

        foreach ($repo->findAll() as $asset) {
            $userId = $asset->getUser()->getId();
            $assetsByUser[$userId][] = $asset;
        }

        foreach ($assetsByUser as $userId => $assets) {
            usort($assets, fn($a,$b) => $a->getId() <=> $b->getId());
            $i = 1;
            foreach ($assets as $a) {
                $a->setUserAssetNumber($i++);
                $this->em->persist($a);
            }
        }

        $this->em->flush();
        $output->writeln('<info>Backfilled userAssetNumber successfully.</info>');
        return Command::SUCCESS;
    }
}
