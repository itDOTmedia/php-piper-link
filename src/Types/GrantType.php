<?php
namespace Idm\PiperLink\Types;

enum GrantType: int
{
    /// An unknown authorization method.
    case Unknown = 0;
    /// No authorization given.
    case None = 1;
    
    /// Make an authorization using a base64 encoded combination of username and password.
    case Basic = 2;
    /// Make an authorization using "client id" and "client secret".
    case ClientCredentials = 3;
    /// Make an authorization using any kind of "token".
    case Bearer = 4;
    /// Make an authorization using an "api key".
    case ApiKey = 5;

    /// Any other authorization method (consider expanding this list).
    case Other = 255;
}