<?php
namespace Idm\PiperLink\Models;

/**
 * The bank details model.
 */
class BankDetails extends BaseModel
{
    /**
     * The name of the bank.
     * @var string|null
     */
    public ?string $bankName = null;

    /**
     * The "International Bank Account Number".
     * @var string|null
     */
    public ?string $IBAN = null;

    /**
     * The "Bank Identifier Code".
     * @var string|null
     */
    public ?string $BIC = null;

    /**
     * The name of the bank account owner.
     * @var string|null
     */
    public ?string $accountOwner = null;
}