<?php

namespace YaBandPay\Payment\Model;

/**
 * Class Visa
 * @package YaBandPay\Payment\Model
 * @description
 * @version 1.0.0
 */
class Visa extends AbstractPayment
{
    const CODE = 'yabandpay_visa';

    protected $_code = self::CODE;
}
