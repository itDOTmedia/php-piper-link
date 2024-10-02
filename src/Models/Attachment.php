<?php
namespace Idm\PiperLink\Models;

use Idm\PiperLink\Types\AttachmentType;
use Idm\PiperLink\Types\Operation;
use Idm\PiperLink\Types\ReferenceType;

/**
 * The model for a (order) document.
 */ 
class Attachment extends BaseModel
{
    /**
     * The document id (leave empty for new uploads).
     * @var string|null
     */
    public ?string $id = null;

    /**
     * The document number.
     * @var integer|null
     */
    public ?int $number = null;

    /**
     * The document display number.
     * @var string|null
     */
    public ?string $displayNumber = null;

    /**
     * The reference type this file belongs to (like Order, Customer, Product, ...).
     *
     * @var ReferenceType|null
     */
    public ?ReferenceType $referenceType = null;

    /**
     * The reference id (lile Order Id, Customer Id, Product Id, ...).
     * @var string|null
     */
    public ?string $referenceId = null;
    
    /**
     * The document type.
     * @var DocumentFileType|null
     */
    public ?AttachmentType $type = null;

    /**
     * The document title.
     * @var string|null
     */
    public ?string $title = null;

    /**
     * The document encoded as base64 string. This value should be set to null in the result.
     * @var string|null
     */
    public ?string $base64 = null;

    /**
     * The file type/extension (must be set for Base64).
     * @var string|null
     */
    public ?string $fileType = null;

    /**
     * Optional timestamp of the document.
     * @var \DateTime|null
     */
    public ?\DateTime $timestamp = null;

    /**
     * Date and time the attachment was created.
     * @var \DateTime|null
     */
    public ?\DateTime $createdAt = null;
    
    /**
     * Date and time the attachment was updated.
     * @var \DateTime|null
     */
    public ?\DateTime $updatedAt = null;

    
    public ?Operation $operation = null;
    public ?string $error = null;

    public function __construct(?string $id = null)
    {
        $this->id = $id;
    }
}
