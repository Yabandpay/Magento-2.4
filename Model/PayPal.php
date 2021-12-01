<?php

namespace YaBandPay\Payment\Model;

/**
 * Class PayPal
 * @package YaBandPay\Payment\Model
 * @description
 * @version 1.0.0
 */
class PayPal extends AbstractPayment
{
    const CODE = 'yabandpay_paypal';

    protected $_code = self::CODE;
}
