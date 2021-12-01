<?php

namespace YaBandPay\Payment\Model;

/**
 * Class MasterCard
 * @package YaBandPay\Payment\Model
 * @description
 * @version 1.0.0
 */
class MasterCard extends AbstractPayment
{
    const CODE = 'yabandpay_mastercard';

    protected $_code = self::CODE;
}
