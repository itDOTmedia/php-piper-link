<?php
namespace Idm\PiperLink\Types;

/**
 * Enable/Disable status.
 */
enum StatusType: int 
{
    /**
     * The resouce (like a product) is inactive.
     */
    case Inactive = 0;

    /**
     * The resouce (like a product) is active.
     */
    case Active = 1;        
}