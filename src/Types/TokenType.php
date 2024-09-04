<?php
namespace Idm\PiperLink\Types;

enum TokenType: int
{
    /// An unknown authorization method.
    case Unknown = 0;
    
    /// No authorization given.
    case AccessToken = 1;
    
    /// Refresh Token
    case RefreshToken = 2;
}