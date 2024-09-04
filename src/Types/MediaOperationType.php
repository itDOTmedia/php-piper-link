<?php
namespace Idm\PiperLink\Types;

enum MediaOperationType : int
{
    case Unknown = 0;

    /** Link an existing media file with a document. */
    case Link = 1;

    /** Replace an existing link within a document. */
    case Replace = 2;

    /** Unlink an exisiting media file from a document. */
    case Unlink = 3;
}