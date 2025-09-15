<?php

namespace App\Modules\ScanManagement\Message;

class ScanJobMessage
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
