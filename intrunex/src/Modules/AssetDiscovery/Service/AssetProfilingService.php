<?php
namespace App\Modules\AssetDiscovery\Service;

use App\Modules\AssetDiscovery\Entity\Asset;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class AssetProfilingService
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function profile(Asset $asset): void
    {
        $target = $asset->getIpAddress() ?: $asset->getUrl();

        if (!$target) {
            throw new \RuntimeException("No target (IP/URL) specified for asset profiling.");
        }

        // If the target is a URL, extract the host
        if (filter_var($target, FILTER_VALIDATE_URL)) {
            $target = parse_url($target, PHP_URL_HOST);
        }

        $process = new Process(['nmap', '-sV', $target]);
        try {
            $process->mustRun();
        } catch (ProcessFailedException $e) {
            throw new \RuntimeException('Nmap failed: ' . $e->getMessage());
        }

        $output = $process->getOutput();

        // 🔹 Extract IP
        if (preg_match('/Nmap scan report for .* \(([\d\.]+)\)/', $output, $matches)) {
            $asset->setIpAddress($matches[1]);
        }

        // 🔹 Extract Ports & Services
        $services = [];
$detectedType = null;
$detectedStatus = 'Inactive';

if (preg_match_all('/(\d+)\/tcp\s+open\s+([^\s]+)\s*([^\n]*)/', $output, $matches, PREG_SET_ORDER)) {
    foreach ($matches as $m) {
        $port = $m[1];
        $service = $m[2];
        $extra = trim($m[3]);

        // Always store as plain string (prevents Array→String bug)
        $services[] = (string) sprintf("%s/tcp - %s %s", $port, $service, $extra);

        // Detect type
        if (in_array($port, ['80','443'])) {
            $detectedType = 'Web';
        } elseif ($service === 'ssh') {
            $detectedType = 'Server';
        } elseif ($service === 'ftp') {
            $detectedType = 'File Transfer';
        } elseif (in_array($service, ['mysql', 'postgresql'])) {
            $detectedType = 'Database';
        }

        $detectedStatus = 'Active';
    }
}

$asset->setOpenPorts(array_values($services)); // ✅ force reindex & string array

       

        $profiledAt = new \DateTimeImmutable();
        // Update asset
        if ($detectedType !== null) {
            $asset->setType($detectedType);
        }
        $asset->setStatus($detectedStatus);
        $asset->setOpenPorts($services);
        $asset->setLastProfiledAt($profiledAt);

        // Keep full scan output in description
        $asset->setDescription(
            "[Last Scan: ".$profiledAt->format('Y-m-d H:i:s')."]\n".
            "Detected Type: ".($detectedType ?? 'Unknown')."\n".
            "Status: ".$detectedStatus."\n".
            "Open Services:\n".implode("\n", $services)."\n\n".
            "Full Scan Output:\n".$output
        );

        $this->em->persist($asset);
        $this->em->flush();
    }
}
