<?php
namespace Idm\PiperLink\Types;

/**
 * Defines the type of an attachment.
 */
enum AttachmentType: int {

    case Unknown = 0;

    /** The document is an invoice. */
    case Invoice = 1;

    case Other = 255;
}