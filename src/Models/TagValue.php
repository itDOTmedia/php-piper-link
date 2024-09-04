<?php
namespace Idm\PiperLink\Models;

use Pyther\Json\Attributes\JsonIgnore;

/**
 * Model of a single tag value.
 */
class TagValue
{
    /**
     * The tag defined by tag id.
     * @var string|null
     */
    public ?string $id = null;

    /**
     * The tag defined by tag name.
     * @var string|null
     */
    public ?string $name = null;

    /**
     * Reference to a real tag object.
     * @var Tag|null
     */
    #[JsonIgnore()]
    public ?Tag $tag = null;
}