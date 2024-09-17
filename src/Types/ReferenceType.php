<?php
namespace Idm\PiperLink\Types;

enum ReferenceType : int
{
    case Unknown = 0;

    case Order = 1;
    case Customer = 2;
    case Document = 3;
    case Product = 4;

    case Other = 255;
}