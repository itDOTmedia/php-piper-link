<?php
namespace Idm\PiperLink\Models;

use Idm\PiperLink\Types\Operation;

/**
 * The customer model.
 * A customer can be a single person or a company.
 */
class Customer extends BaseModel
{
    /**
     * The unique customer id.
     * @var string|null
     */
    public ?string $id = null;

    /**
     * Optional customer, printable number.
     * @var string|null
     */
    public ?string $number = null;

    /**
     * The customer group id this customer belongs to.
     * @var string|null
     */
    public ?string $groupId = null;

    /**
     * List of customer contact information.
     * @var Idm\PiperLink\Models\Contact[]
     */
    public ?array $contacts = [];

    /**
     * The default price list of the customer.
     * @var SalesPriceList|null
     */
    public ?SalesPriceList $defaultPriceList = null;

    /**
     * The customers primary bank details.
     * @var BankDetails|null
     */
    public ?BankDetails $bankDetails = null;

    /**
     * List of internal and external customer notes.
     * @var Idm\PiperLink\Models\Note[]
     */
    public ?array $notes = [];

    /**
     * Date and time the customer was created.
     * @var \DateTime|null
     */
    public ?\DateTime $createdAt = null;

    /**
     * Date and time the customer was updated.
     * @var \DateTime|null
     */
    public ?\DateTime $updatedAt = null;

    public ?Operation $operation = null;

    public ?string $error = null;
    
    function __construct(?string $id = null)
    {
        $this->id = $id;
    }

    #region methods

    /**
     * Returns the primary contact in the contact list.
     * @return Contact|null
     */
    public function getDefaultContact(): ?Contact
    {
        if ($this->contacts === null) return null;

        foreach ($this->contacts as $contact) {
            if ($contact->isPrimaryContact) {
                return $contact;
            }
        }
        return null;
    }

    #endregion    
}