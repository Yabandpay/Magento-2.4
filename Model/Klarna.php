<?php

namespace YaBandPay\Payment\Model;

/**
 * Class Klarna
 * @package YaBandPay\Payment\Model
 * @description
 * @version 1.0.0
 */
class Klarna extends AbstractPayment
{
    const CODE = 'yabandpay_klarna';

    protected $_code = self::CODE;
}
