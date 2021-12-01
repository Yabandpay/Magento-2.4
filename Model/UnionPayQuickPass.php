<?php

namespace YaBandPay\Payment\Model;

/**
 * Class UnionPayQuickPass
 * @package YaBandPay\Payment\Model
 * @description
 * @version 1.0.0
 */
class UnionPayQuickPass extends AbstractPayment
{
    const CODE = 'yabandpay_unionpayquickpass';

    protected $_code = self::CODE;
}
