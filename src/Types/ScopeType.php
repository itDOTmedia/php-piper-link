<?php
namespace Idm\PiperLink\Types;

enum ScopeType : int
{
    case Unknown = 0;

    case Product = 1;
    case Caregory = 2;
    case Order = 3;
    case Contact = 4;
    case File = 5;

    case Other = 255;
}