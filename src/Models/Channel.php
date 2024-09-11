<?php
namespace Idm\PiperLink\Models;

/**
 * Define the kind of platform (Amazon, Ebay, Shop1, Shop2, etc.) or sales channel.
 */
class Channel
{
    /**
     * The (external) channel id.
     * @var string|null
     */
    public ?string $id = null;

    /**
     * The channel name.
     * @var string|null
     */
    public ?string $name = null;

    public function __construct(string $id = "", ?string $name = null)
    {
        $this->id = $id;
        $this->name = $name;
    }
}