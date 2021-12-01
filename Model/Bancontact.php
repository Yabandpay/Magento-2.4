<?php

namespace YaBandPay\Payment\Model;

/**
 * Class Bancontact
 * @package YaBandPay\Payment\Model
 * @description
 * @version 1.0.0
 */
class Bancontact extends AbstractPayment
{
    const CODE = 'yabandpay_bancontact';

    protected $_code = self::CODE;
}
