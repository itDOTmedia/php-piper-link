<?php
namespace Idm\PiperLink\Types;

/**
 * List of barcode types.
 */
enum BarcodeType: int
{
    /** 8 digit code, alias EAN-8 */
    case GTIN_8 = 8;
    
    /** 12 digit code, alias UPC */
    case GTIN_12 = 12;

    /** 13 digit code, alias EAN / JAN */
    case GTIN_13 = 13;

    /** 14 digit code */
    case GTIN_14 = 14;
    
    /** 128 digit code */
    case GTIN_128 = 128;

    /** 10 digit code */
    case ISBN_10 = 110;

    /** 12 digit code */
    case ISBN_13 = 113;

    /** 2d code */
    case QR = 201;

    /** 2d code */
    case Aztec = 202;

    /** universal code */
    case CODE_128 = 228;

    /** any other code */
    case Other = 255;
}
