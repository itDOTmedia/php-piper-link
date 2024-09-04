<?php
namespace Idm\PiperLink\Models;

use Idm\PiperLink\Types\MediaOperationType;
use Idm\PiperLink\Types\StatusType;

class ProductImageLink
{
    /**
     * The id of the image linked with a product.
     * @var string|null
     */
    public ?string $id = null;

    /**
     * The image operation of "Link", "Replace" or "Unlink".
     * @var MediaOperationType
     */
    public MediaOperationType $operation = MediaOperationType::Link;

    /**
     * The reference to the image. This is an external resource id.
     * @var string|null
     */
    public ?string $reference = null;

    /**
     * Mark the image as primary image. This image is often used as preview image in listings.
     * @var boolean
     */
    public bool $isPrimary = false;

    /**
     * Enable/disable this image. Inactive images are not the same as "unlinked" images.
     * @var StatusType
     */
    public StatusType $status = StatusType::Active;

    /**
     * Images titles per language.
     * @var LanguageValue[]|null
     */
    public ?array $titles = null;
}