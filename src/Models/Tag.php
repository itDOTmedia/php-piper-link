<?php
namespace Idm\PiperLink\Models;

use Idm\PiperLink\Types\ScopeType;

class Tag
{
    public ?string $id = null;

    public ?string $name = null;

    /**
     * List of supported scopes for this tag.
     * @var ScopeType[]
     */
    public array $scopes = null;
}