<?php
namespace Idm\PiperLink\Types;

enum NoteType : int
{
    /** The note type is unknown and not defined. */
    case Unknown = 0;
    
    /** This is a customer note. **/
    case Customer = 1;

    /** This is a note from an employee. */    
    case Employee = 2;

    /** This is a note from the underlying system. */
    case System = 3;

    /** Pool type for any other type of note. */
    case Other = 255;
}