<?php

namespace App\Modules\AssetDiscovery\Message;

final class ProfileAssetMessage
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
