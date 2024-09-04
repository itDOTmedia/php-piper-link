<?php
namespace Idm\PiperLink\Models;

use Idm\PiperLink\Types\NoteType;

/**
 * Model of a single customer, employee or system note.
 */
class Note extends BaseModel
{
    /**
     * The external note id.
     * @var string|null
     */
    public ?string $id = null;

    /**
     * The type (origin) of the note.
     * @var NoteType|null
     */
    public ?NoteType $type = null;

    /**
     * The note content.
     * @var string|null
     */
    public ?string $text = null;

    /**
     * Name or id of the note creator.
     * @var string|null
     */
    public ?string $createdBy = null;

    /**
     * Timestamp, when the note was created.
     * @var \DateTime|null
     */
    public ?\DateTime $createdAt = null;
}