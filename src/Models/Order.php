<?php

namespace Idm\PiperLink\Models;

use Idm\PiperLink\Types\Operation;
use Idm\PiperLink\Types\OrderState;

/**
 * The order model.
 */
class Order extends BaseModel
{
    /**
     * The unique order id.
     * @var string|null
     */
    public ?string $id = null;

    /**
     * The number visible to the customer per service or marketplace.
     * @var string|null
     */
    public ?string $orderNumber = null;

    /**
     * The order state the order is currently in.
     * @var OrderState|null
     */
    public ?OrderState $state = null;

    /**
     * The date and time of purchase.
     * @var \DateTime|null
     */
    public ?\DateTime $dateOfPurchase = null;

    /**
     * List of all items (products, shipping costs, ...).
     * @var OrderItem[]
     */
    public array $items = [];

    /**
     * List of internal and external order notes.
     * @var Note[]
     */
    public ?array $notes = null;

    /**
     * If true, the invoice is net, gross otherwise.
     * @var bool|null
     */
    public ?bool $isNetInvoice = null;

    /**
     * The order currency.
     * @var Currency|null
     */
    public ?Currency $currency = null;

    /**
     * The total net price.
     * @var float|null
     */
    public ?float $netTotal = null;

    /**
     * The total gross price.
     * @var float|null
     */
    public ?float $vatTotal = null;

    /**
     * The order vat. An order have one vat entry per vat rate.
     * @var Vat[]|null
     */
    public ?array $vats = null;

    /**
     * The invoice total value.
     * @var float|null
     */
    public ?float $invoiceTotal = null;

    /**
     * The (external) customer id.
     * @var string|null
     */
    public ?string $customerId = null;

    /**
     * The customer contact information.
     * @var Contact|null
     */
    public ?Contact $customerContact = null;

    /**
     * The (external) payment id.
     * @var string|null
     */
    public ?string $paymentMethodCode = null;

    /**
     * The payment method name.
     * @var string|null
     */
    public ?string $paymentMethodName = null;

    /**
     * The (external) payment code.
     * @var string|null
     */
    public ?string $paymentStatusCode = null;

    /**
     * The payment status as string.
     * @var string|null
     */
    public ?string $paymentStatusName = null;

    /**
     * Used for external payment providers (PayPal, Unzer, etc.).
     * @var string|null
     */
    public ?string $paymentTransactionId = null;

    /**
     * The billing contact information.
     * @var Contact|null
     */
    public ?Contact $billingContact = null;

    /**
     * The (external) shipping profile id.
     * @var string|null
     */
    public ?string $shippingProfileCode = null;

    /**
     * The shipping profile name.
     * @var string|null
     */
    public ?string $shippingProfileName = null;

    /**
     * The shippping costs (net version).
     * @var float|null
     */
    public ?float $shippingCostsNet = null;

    /**
     * The shipping costs (gross version).
     * @var float|null
     */
    public ?float $shippingCostsGross = null;

    /**
     * The shipping contact information.
     * @var Contact|null
     */
    public ?Contact $shippingContact = null;

    /**
     * Date and time the order was created.
     * @var \DateTime|null
     */
    public ?\DateTime $createdAt = null;

    /**
     * Date and time the order was updated.
     * @var \DateTime|null
     */
    public ?\DateTime $updatedAt = null;

    public ?Operation $operation = null;
}
