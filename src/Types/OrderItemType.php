<?php
namespace Idm\PiperLink\Types;

enum OrderItemType : int
{
    /** The order item is a regular product. */
    case Product = 1;

    /** The order item defines the shipping costs. */
    case ShippingCosts = 2;

    /** The order item is a coupon, promotion, discount, etc. */
    case Coupon = 3;

    /** The order item is something like bundles, shop/project specific */
    case Custom = 255;
}