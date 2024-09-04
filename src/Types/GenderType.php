<?php
namespace Idm\PiperLink\Types;

/**
 * Define the gender of a person.
 */
enum GenderType: int
{
    case Unknown = 0;

    /**
     * A male person.
     */
    case Male = 1;

    /**
     * A female person.
     */
    case Female = 2;
    
    /**
     * A diverse person.
     */
    case Diverse = 3;
}