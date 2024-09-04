<?php
namespace Idm\PiperLink\Models;

/**
 * Model of a single shipping profile.
 */
class ShippingProfile
{
     /**
      * The id of the shipping profile.
      * @var string
      */
    public string $id;

    /**
     * The name of the shipping profile.
     * @var string|null
     */
    public ?string $name;

    /**
     * Create a new instance by id and optional name.
     * @param string $id
     * @param string|null $name
     */
    public function __construct(string $id = "", ?string $name = null)
    {
        $this->id = $id;
        $this->name = $name;
    }
}