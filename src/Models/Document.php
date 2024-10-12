<?php
namespace Idm\PiperLink\Models;

use Idm\PiperLink\Types\Operation;
use Idm\PiperLink\Types\VatType;

/**
 * The document model.
 */
class Document extends BaseModel
{

        /**
         * The external id for this document.
         * @var string|null
         */
        public ?string $id = null;

        /**
         * The key of the document like "V.RE.123456".
         * @var string|null
         */
        public ?string $key = null;

        /**
         * An optional "Universally Unique Identifier".
         * @var string|null
         */
        public ?string $uuid = null;

        #region payment & shipping
    
        /**
         * The payment method id/code.
         * @var string|null
         */
        public ?string $paymentMethodCode = null;
    
        /**
         * The payment method name.
         * @var string|null
         */
        public ?string $paymentMethodName = null;
    
        /**
         * The shipping profile id/code.
         * @var string|null
         */
        public ?string $shippingProfileCode = null;
    
        /**
         * The shipping profile name.
         * @var string|null
         */
        public ?string $shippingProfileName = null;
    
        #endregion
    
        #region prices & taxes
    
        public ?float $netTotal = null;
        public ?float $vatTotal = null;
        public ?float $grossTotal = null;
        public ?VatType $vatType = null;
        public ?Currency $currency = null;
    
        #endregion
    
        #region contacts
    
        public ?string $customerKey = null;
        public ?string $shippingContactKey = null;
    
        /**
         * list of contacts.
         * @var \Idm\PiperLink\Models\Contact[]
         */
        public ?array $contacts = null;
    
        #endregion
    
        #region items
    
        /**
         * list of document items.
         * @var \Idm\PiperLink\Models\DocumentItem[]
         */
        public ?array $items = null;
    
        #endregion
    
        #region notes
    
        /**
         * list of notes.
         * @var \Idm\PiperLink\Models\Note[]
         */
        public ?array $notes = null;
    
        #endregion
    
        #region timestamps
    
        /**
         * The main document date and time.
         * @var \DateTime|null
         */
        public ?\DateTime $documentDate = null;
    
        /**
         * The shipping date and time.
         * @var \DateTime|null
         */
        public ?\DateTime $shippingDate = null;
    
        /**
         * Date and time the order was created.
         * @var \DateTime|null
         */
        public ?\DateTime $createdAt = null;
    
        /// <summary>Date and time the order was updated.</summary>
        public ?\DateTime $updatedAt = null;
    
        #endregion

        public ?Operation $operation = null;

        public ?string $error = null;
    
        function __construct(?string $id = null)
        {
            $this->id = $id;
        }
}