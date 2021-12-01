<?php
/**
 * @project    : YabanPay-Magento2
 * @description:
 * @user       : persi
 * @email persi@sixsir.com
 * @date       : 2018/9/1
 * @time       : 11:42
 */

namespace YaBandPay\Payment\Controller\Checkout;

use function var_export;
use YaBandPay\Payment\Controller\Api;
use YaBandPay\Payment\Helper\General as YaBandWechatPayHelper;

class Success extends Api
{
    /**
     * Execute Redirect to Mollie after placing order
     */
    public function execute()
    {
        try{
            $orderInfo = $this->getOrderInfo();
            $this->yaBandWechatPayHelper->addTolog('info', 'Success:' . var_export($orderInfo, true));
            if(isset($orderInfo['state']) && $orderInfo['state'] === YaBandWechatPayHelper::PAY_PAID){
                $this->paymentInstance->processTransaction($orderInfo);
                $this->_redirect(
                    'checkout/onepage/success?utm_nooverride=1'
                );
            }else{
                $orderInfo['demo'] = json_decode($orderInfo['demo'],true);
                $this->_redirect('sales/order/view/order_id/'.$orderInfo['demo']['magento_order_id']);
            }
        }catch(\Exception $e){
            $this->yaBandWechatPayHelper->addTolog('error', 'Success Exception:' . $e->getMessage());
            $this->messageManager->addExceptionMessage(
                $e, __($e->getMessage())
            );
            $this->_redirect('checkout/onepage/error?utm_nooverride=1&error=2');
        }
    }
}
