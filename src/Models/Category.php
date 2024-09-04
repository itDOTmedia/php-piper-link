<?php
namespace Idm\PiperLink\Models;

/**
 * Model of a single (shop) category.
 */
class Category
{
    /**
     * The unique category id.
     * @var string
     */
    public string $id;

    /**
     * The parent category id or null for root categories.
     * @var string|null
     */
    public ?string $parentId = null;

    /**
     * The category name.
     * @var string|null
     */
    public ?string $name = null;

    /**
     * Is the category active?
     * @var boolean|null
     */
    public ?bool $isActive = null;

    /**
     * Creates a new category instance.
     * @param string $id The unique category id.
     */
    function __construct(string $id = "")
    {
        $this->id = $id;
    }
}
