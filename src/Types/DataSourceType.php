<?php
namespace Idm\PiperLink\Types;

enum DataSourceType: int
{
    case Unknwown = 0;
    case Inline = 1;
    case File = 2;
    case Url = 3;
    case Base64 = 4; 
}