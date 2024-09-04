<?php
namespace Idm\PiperLink\Models;

use Idm\PiperLink\Types\StatusType;

/**
 * The current Channel state of the product.
 */
class ChannelState
{
    /**
     * The id of the channel.
     * @var string|null
     */
    public ?string $channelId = null;

    /**
     * The channel state of StatusType.
     * @var StatusType
     */
    public StatusType $Status = StatusType::Active;
}