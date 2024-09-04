<?php
namespace Idm\PiperLink\Types;

/**
 * Defines the type of contact.
 */
enum ContactType: int 
{
    case Unknown = 0;
    case Shipping = 1;
    case Payment = 2;
    case Other = 255;
}