<?php
namespace Idm\PiperLink\Models;

use Idm\PiperLink\Types\Operation;

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
    
    // @TODO: complete this model
}