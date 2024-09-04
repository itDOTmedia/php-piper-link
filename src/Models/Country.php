<?php
namespace Idm\PiperLink\Models;

/**
 * The country model.
 */
class Country extends BaseModel
{
    /**
     * ISO 3166-1 Numeric Code
     * @var integer|null
     */
    public ?int $code = null;

    /**
     * ISO 3166-1 Alpha 2 Code     
     * @var string|null
     */
    public ?string $alpha2 = null;    

    /**
     * ISO 3166-1 Alpha 3 Code     
     * @var string|null
     */
    public ?string $alpha3 = null;
    
    /**
     * The default country name.     
     * @var string|null
     */
    public ?string $name = null;

    /**
     * Create a new country instance.
     *
     * @param integer|null $code
     * @param string|null $alpha2
     * @param string|null $alpha3
     * @param string|null $name
     */
    public function __construct(?int $code = null, ?string $alpha2 = null, ?string $alpha3 = null, ?string $name = null)
    {
        $this->code = $code;
        $this->alpha2 = $alpha2;
        $this->alpha3 = $alpha3;
        $this->name = $name;
    }

    public function __toString(): string {
        return "$this->name ($this->alpha3)";
    }

}