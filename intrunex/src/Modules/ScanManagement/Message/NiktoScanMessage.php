<?php

namespace App\Modules\ScanManagement\Message;

class NiktoScanMessage
{
    private int $assetId;

    public function __construct(int $assetId)
    {
        $this->assetId = $assetId;
    }

    public function getAssetId(): int
    {
        return $this->assetId;
    }
}
