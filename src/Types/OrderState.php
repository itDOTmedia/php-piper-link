<?php
namespace Idm\PiperLink\Types;

enum OrderState : int
{  
    /** The order is in an unknown state. */
    case Unknown = 0;

    /** The order was just created. */
    case Created = 1;

    /** The order is waiting for payment. */
    case WaitingForPayment = 2;

    /** The order es ready to progress. */
    case Ready = 3;

    /** The order is in process. */
    case Progress = 4;

    /** The order is on the way to the customer. */
    case Shipped = 5;

    /** The order is payed by the customer. */
    case Payed = 6;

    /** The order is aborted before going to ready state. */
    case Aborted = 101;

    /** The order was canceled after it was in ready state. */
    case Canceled = 102;

    /** Any other, unhandled state. */
    case Other = 200;
}