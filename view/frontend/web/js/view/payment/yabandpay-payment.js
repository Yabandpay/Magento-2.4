/**
 * Copyright © 2015 Magento. All rights reserved.
 * See COPYING.txt for license details.
 */
/*browser:true*/
/*global define*/
define(
    [
        'uiComponent',
        'Magento_Checkout/js/model/payment/renderer-list'
    ],
    function (
        Component,
        rendererList
    ) {
        'use strict';
        console.log(window.checkoutConfig);

        if (window.checkoutConfig.payment.yabandpay_wechatpay.isActive) {
            rendererList.push(
                {
                    type: 'yabandpay_wechatpay',
                    component: 'YaBandPay_Payment/js/view/payment/method-renderer/yabandpay_wechatpay-method'
                }
            );
        }

        if (window.checkoutConfig.payment.yabandpay_alipay.isActive) {
            rendererList.push(
                {
                    type: 'yabandpay_alipay',
                    component: 'YaBandPay_Payment/js/view/payment/method-renderer/yabandpay_alipay-method'
                }
            );
        }

        if (window.checkoutConfig.payment.yabandpay_ideal.isActive) {
            rendererList.push(
                {
                    type: 'yabandpay_ideal',
                    component: 'YaBandPay_Payment/js/view/payment/method-renderer/yabandpay_ideal-method'
                }
            );
        }


        if (window.checkoutConfig.payment.yabandpay_bancontact.isActive) {
            rendererList.push(
                {
                    type: 'yabandpay_bancontact',
                    component: 'YaBandPay_Payment/js/view/payment/method-renderer/yabandpay_bancontact-method'
                }
            );
        }

        if (window.checkoutConfig.payment.yabandpay_visa.isActive) {
            rendererList.push(
                {
                    type: 'yabandpay_visa',
                    component: 'YaBandPay_Payment/js/view/payment/method-renderer/yabandpay_visa-method'
                }
            );
        }

        if (window.checkoutConfig.payment.yabandpay_mastercard.isActive) {
            rendererList.push(
                {
                    type: 'yabandpay_mastercard',
                    component: 'YaBandPay_Payment/js/view/payment/method-renderer/yabandpay_mastercard-method'
                }
            );
        }

        if (window.checkoutConfig.payment.yabandpay_paypal.isActive) {
            rendererList.push(
                {
                    type: 'yabandpay_paypal',
                    component: 'YaBandPay_Payment/js/view/payment/method-renderer/yabandpay_paypal-method'
                }
            );
        }

        if (window.checkoutConfig.payment.yabandpay_klarna.isActive) {
            rendererList.push(
                {
                    type: 'yabandpay_klarna',
                    component: 'YaBandPay_Payment/js/view/payment/method-renderer/yabandpay_klarna-method'
                }
            );
        }

        if (window.checkoutConfig.payment.yabandpay_unionpaywap.isActive) {
            rendererList.push(
                {
                    type: 'yabandpay_unionpaywap',
                    component: 'YaBandPay_Payment/js/view/payment/method-renderer/yabandpay_unionpaywap-method'
                }
            );
        }

        if (window.checkoutConfig.payment.yabandpay_unionpayquickpass.isActive) {
            rendererList.push(
                {
                    type: 'yabandpay_unionpayquickpass',
                    component: 'YaBandPay_Payment/js/view/payment/method-renderer/yabandpay_unionpayquickpass-method'
                }
            );
        }
        /** Add view logic here if needed */
        return Component.extend({});
    }
);