<?php
namespace Idm\PiperLink\Models;

use Idm\PiperLink\Types\DataSourceType;
use Pyther\Json\Attributes\JsonIgnore;

/**
 * Defines a specific property value.
 */
class PropertyValue
{
    /**
     * Optional external property value id.
     * @var string|null
     */
    public ?string $id = null;

    /**
     * The property this value belongs to.
     * @var string|null
     */
    public ?string $propertyId = null;

    /**
     * The name of the property this value belongs to.
     * @var string|null
     */
    public ?string $name = null;

    /**
     * Optional language code for multi-language values.
     * @var string|null
     */
    public ?string $language = null;

    /**
     * The string version of the property values.
     * @var string|null
     */
    public ?string $value = null;

    /**
     * The list of string versions of the property values for multi selects.
     * @var string[]|null
     */
    public ?array $values = null;

    /**
     * Optional data source to reference to an external resource (like file or url).
     * @var DataSource
     */
    public DataSourceType $source = DataSourceType::Inline;

    /**
     * Are we getting binary data?
     * @var boolean
     */
    public bool $isBinary = false;

    /**
     * Optional binary value representation for "File" type.
     * @var string|null
     */
    #[JsonIgnore()]
    public ?string $binary = null;

    /**
     * Optional file name for Base64 data.
     * You have to set this value too, if the file name can not be extracted from the "Value".
     * @var string|null
     */
    public ?string $fileName = null;
}