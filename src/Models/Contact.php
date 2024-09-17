<?php
namespace Idm\PiperLink\Models;

use Idm\PiperLink\StringX;
use Idm\PiperLink\Types\ContactType;
use Idm\PiperLink\Types\GenderType;
use Pyther\Json\Attributes\JsonEnum;
use Pyther\Json\Types\EnumFormat;

/**
 * The Contact model.
 */
class Contact extends BaseModel
{
    /**
     * the contact id
     * @var string|null
     */
    public ?string $externalId = null;    
    
    /**
     * Is this contact the primary contact?
     * @var boolean
     */
    public bool $isPrimaryContact = false;

    /**
     * The type of contact ("Shipping", "Payment", "Other", ...)
     * @var ContactType|null
     */
    public ?ContactType $contactType = null;

    /**
     * The gender (male, female or diverse).
     * @var GenderType|null
     */
    #[JsonEnum(EnumFormat::Name)]
    public ?GenderType $gender = null;
    
    /**
     * The Salutation like "Mr.", "Mrs.", "Doc.", ...
     * @var string|null
     */    
    public ?string $salutation = null;
    
    /**
     * Optional name prefix.
     * @var string|null
     */    
    public ?string $namePrefix = null;
    
    /**
     * The first name. If name is not splitable, `FirstName` will be null and `LastName` contains both values.
     * @var string|null
     */    
    public ?string $firstName = null;
    
    /**
     * The optional middle name.
     * @var string|null
     */    
    public ?string $middleName = null;
    
    /**
     * The last name. If name is not splitable, `FirstName` will be null and `LastName` contains both values.
     * @var string|null
     */    
    public ?string $lastName = null;
    
    /**
     * Optional name suffix.
     * @var string|null
     */    
    public ?string $nameSuffix = null;
    
    /**
     * The street name.
     * @var string|null
     */    
    public ?string $street = null;

    /**
     * The street number.
     * @var string|null
     */    
    public ?string $houseNumber = null;

    /**
     * The company name.
     * @var string|null
     */    
    public ?string $Ccompany = null;

    /**
     * The additional address line.
     * @var string|null
     */    
    public ?string $additionalAddressLine = null;

    /**
     * The country.
     * @var string|null
     */    
    public ?Country $country = null;

    /**
     * The state.
     * @var string|null
     */    
    public ?string $state = null;

    /**
     * The city.
     * @var string|null
     */    
    public ?string $city = null;

    /**
     * The suburb.
     * @var string|null
     */    
    public ?string $suburb = null;

    /**
     * The postal code.
     * @var string|null
     */    
    public ?string $postalCode = null;

    /**
     * The email address.
     * @var string|null
     */    
    public ?string $email = null;

    /**
     * The primary phone number.
     * @var string|null
     */    
    public ?string $primaryPhone = null;

    /**
     * The secondary phone number.
     * @var string|null
     */    
    public ?string $secondaryPhone = null;

    /**
     * The fax number.
     * @var string|null
     */    
    public ?string $fax = null;

    /**
     * The url address.
     * @var string|null
     */    
    public ?string $url = null;

    /**
     * The VAT number.
     * @var string|null
     */    
    public ?string $vatNumber = null;

    /**
     * Birthday date. 
     * @var DateTime|null
     */
    public ?\DateTime $birthday = null;

    #region methods

    /**
     * Returns the name as a combination of first- and last-name.
     * @return string|null
     */
    public function getName(): ?string {
        return !StringX::isNullOrWhiteSpace($this->firstName) ? ($this->firstName . " " . $this->lastName) : $this->lastName;
    }

    /**
     * Get the display name (combination of all available name parts).
     * @return string|null
     */
    public function getDisplayName(): ?string {
        $parts = [];
        if (!StringX::isNullOrWhiteSpace($this->namePrefix)) $parts[] = $this->namePrefix;
        if (!StringX::isNullOrWhiteSpace($this->firstName)) $parts[] = $this->firstName;
        if (!StringX::isNullOrWhiteSpace($this->middleName)) $parts[] = $this->middleName;
        if (!StringX::isNullOrWhiteSpace($this->lastName)) $parts[] = $this->lastName;
        if (!StringX::isNullOrWhiteSpace($this->nameSuffix)) $parts[] = $this->nameSuffix;

        return count($parts) > 0 ? implode(" ", $parts) : null;
    }

    #endregion
}