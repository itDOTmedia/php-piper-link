<?php
namespace Idm\PiperLink\Models;

use Idm\PiperLink\Types\Operation;

/**
 * The products model. 
 */
class PrimaryProduct extends BaseModel
{
    /**
     * The unique product id.
     * @var string|null
     */
    public ?string $id = null;

    /**
     * The optional product model.
     * @var string|null
     */
    public ?string $model = null;    
}