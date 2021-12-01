<?php
/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace YaBandPay\Payment\Model\Config\Source\Order\Status;


use YaBandPay\Api\Payment;

/**
 * Order Status source model
 */
class Order
{
    public function toOptionArray()
    {
        $statuses = [
            'new' => 'New',
            'pending' => 'Pending',
            'pending_payment' => 'Pending Payment',
            Payment::PAY_PROCESSING => 'Processing',
            Payment::PAY_COMPLETED => 'Complete',
            'closed' => 'Closed',
            'canceled' => 'Canceled',
            'holded' => 'On Hold',
            'payment_review' => 'Payment Review',
        ];

        $options = [ [ 'value' => '', 'label' => __('-- Please Select --') ] ];
        foreach($statuses as $code => $label){
            $options[] = [ 'value' => $code, 'label' => $label ];
        }
        return $options;
    }
}
