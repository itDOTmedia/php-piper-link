<?php
namespace Idm\PiperLink\Types;

enum Operation : int
{
    case Unknown = 0;
    
    //* No operation required.*/
    case Noop = 1;
    
    /** Insert/Add operation.*/
    case Insert = 2;
    
    /** Update operation.*/
    case Update = 3;

    /** Delete operation.*/
    case Delete = 4;

    /** An error occured.*/
    case Error = 5;
}