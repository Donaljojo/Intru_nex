<?php

namespace App\Modules\AssetDiscovery\Service;

use App\Modules\AssetDiscovery\Entity\Asset;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Process\Process;

class AssetProfilingService
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * Perform Phase 1 – Asset Profiling (Discovery Scan)
     */
    public function profile(Asset $asset): void
    {
        $target = $asset->getIpAddress() ?: $asset->getUrl();
        if (!$target) {
            throw new \RuntimeException('Asset must have IP or URL.');
        }

        // 🔹 Build the nmap command
        $process = new Process(['nmap', '-sV', $target]);
        $process->setTimeout(60); // 1 minute timeout
        $process->run();

        // 🔹 Handle errors
        if (!$process->isSuccessful()) {
            throw new \RuntimeException('Scan failed: ' . $process->getErrorOutput());
        }

        $output = $process->getOutput();

        // 🔹 Parse open ports/services from the output
        $openPorts = $this->parseOpenPorts($output);

        // 🔹 Update the asset entity
        $asset->setOpenPorts($openPorts);                // (JSON column in Asset entity)
        $asset->setLastProfiledAt(new \DateTimeImmutable()); // (datetime column in Asset entity)

        // Keep raw scan output appended to description (optional)
        $existingDesc = $asset->getDescription() ?? '';
        $asset->setDescription($existingDesc . "\n\n[Last Scan Output]\n" . $output);

        // 🔹 Save
        $this->em->persist($asset);
        $this->em->flush();
    }

    /**
     * Extract open ports & services from Nmap output.
     */
    private function parseOpenPorts(string $output): array
    {
        $ports = [];
        foreach (explode("\n", $output) as $line) {
            // Match lines like: "80/tcp open  http"
            if (preg_match('/^(\d+)\/tcp\s+open\s+(\S+)/', trim($line), $matches)) {
                $ports[] = [
                    'port' => (int) $matches[1],
                    'service' => $matches[2]
                ];
            }
        }
        return $ports;
    }
}


