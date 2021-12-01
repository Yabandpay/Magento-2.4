<?php

namespace YaBandPay\Payment\Model;

/**
 * Class IDeal
 * @package YaBandPay\Payment\Model
 * @description
 * @version 1.0.0
 */
class IDeal extends AbstractPayment
{
    const CODE = 'yabandpay_ideal';

    protected $_code = self::CODE;
}
