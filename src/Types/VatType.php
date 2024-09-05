<?php
namespace Idm\PiperLink\Types;

enum VatType: int
{
    /// An unknown vat type.
    case Unknown = 0;
    
    case Net = 1;
    case Gross = 2;
    case None = 3;
}