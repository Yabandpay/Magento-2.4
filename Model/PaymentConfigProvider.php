<?php
/**
 * Copyright © 2018 Magmodules.eu. All rights reserved.
 * See COPYING.txt for license details.
 */

namespace YaBandPay\Payment\Model;

use Magento\Checkout\Model\ConfigProviderInterface;
use Magento\Checkout\Model\Session as CheckoutSession;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Escaper;
use Magento\Framework\View\Asset\Repository as AssetRepository;
use Magento\Payment\Helper\Data as PaymentHelper;
use function var_export;
use YaBandPay\Payment\Helper\General as YaBandWechatPayHelper;
use YaBandPay\Payment\Logger\Logger;
use YaBandPay\Api\Payment;

/**
 * Class PaymentConfigProvider
 *
 * @package YaBandPay\Payment\Model
 * @description
 * @version 1.0.0
 */
class PaymentConfigProvider implements ConfigProviderInterface
{
    /**
     * @var Escaper
     */
    private $escaper;
    /**
     * @var AssetRepository
     */
    private $assetRepository;
    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;
    /**
     * @var PaymentHelper
     */
    private $paymentHelper;
    /**
     * @var CheckoutSession
     */
    private $checkoutSession;
    /**
     * @var YaBandWechatPayHelper
     */
    private $yabandpayPaymentHelper;
    /**
     * @var Logger $logger
     */
    private $logger;

    /**
     * PaymentConfigProvider constructor.
     * @param PaymentHelper $paymentHelper
     * @param CheckoutSession $checkoutSession
     * @param AssetRepository $assetRepository
     * @param ScopeConfigInterface $scopeConfig
     * @param Escaper $escaper
     * @param YaBandWechatPayHelper $yabandpayPaymentHelper
     * @param Logger $logger
     */
    public function __construct(
        PaymentHelper $paymentHelper,
        CheckoutSession $checkoutSession,
        AssetRepository $assetRepository,
        ScopeConfigInterface $scopeConfig,
        Escaper $escaper,
        YaBandWechatPayHelper $yabandpayPaymentHelper,
        Logger $logger
    )
    {
        $this->paymentHelper = $paymentHelper;
        $this->checkoutSession = $checkoutSession;
        $this->escaper = $escaper;
        $this->assetRepository = $assetRepository;
        $this->scopeConfig = $scopeConfig;
        $this->yabandpayPaymentHelper = $yabandpayPaymentHelper;
        $this->logger = $logger;
    }


    /**
     * Config Data for checkout
     *
     * @return array
     */
    public function getConfig()
    {
        $config = [];
        $activeWechatPay = $this->yabandpayPaymentHelper->getIsActiveWechatPay();
        if( $activeWechatPay === true){
            $config['payment'][WechatPay::CODE]['isActive'] = true;
            $config['payment'][WechatPay::CODE]['title'] = Payment::WECHAT . $this->yabandpayPaymentHelper->getWechatPayDesc();
        }else{
            $config['payment'][WechatPay::CODE]['isActive'] = false;
        }
        $activeAliPay = $this->yabandpayPaymentHelper->getIsActiveAlipay();

        if($activeAliPay === true){
            $config['payment'][AliPay::CODE]['isActive'] = true;
            $config['payment'][AliPay::CODE]['title'] = Payment::ALIPAY . $this->yabandpayPaymentHelper->getAlipayDesc();
        }else{
            $config['payment'][AliPay::CODE]['isActive'] = false;
        }

        $activeiDeal = $this->yabandpayPaymentHelper->getIsActiveiDeal();
        if( $activeiDeal === true){
            $config['payment'][IDeal::CODE]['isActive'] = true;
            $config['payment'][IDeal::CODE]['title'] = Payment::IDEAL . $this->yabandpayPaymentHelper->getiDealPayDesc();
        }else{
            $config['payment'][IDeal::CODE]['isActive'] = false;
        }

        $activeBancontact = $this->yabandpayPaymentHelper->getIsActiveBancontact();
        if( $activeBancontact === true){
            $config['payment'][Bancontact::CODE]['isActive'] = true;
            $config['payment'][Bancontact::CODE]['title'] = Payment::BANCONTACT . $this->yabandpayPaymentHelper->getBancontactPayDesc();
        }else{
            $config['payment'][Bancontact::CODE]['isActive'] = false;
        }

        $activeVisa = $this->yabandpayPaymentHelper->getIsActiveVisa();
        if( $activeVisa === true){
            $config['payment'][Visa::CODE]['isActive'] = true;
            $config['payment'][Visa::CODE]['title'] = Payment::VISA . $this->yabandpayPaymentHelper->getVisaPayDesc();
        }else{
            $config['payment'][Visa::CODE]['isActive'] = false;
        }

        $activeMasterCard = $this->yabandpayPaymentHelper->getIsActiveMasterCard();
        if( $activeMasterCard === true){
            $config['payment'][MasterCard::CODE]['isActive'] = true;
            $config['payment'][MasterCard::CODE]['title'] = Payment::MASTERCARD . $this->yabandpayPaymentHelper->getMasterCardPayDesc();
        }else{
            $config['payment'][MasterCard::CODE]['isActive'] = false;
        }

        $activePayPal = $this->yabandpayPaymentHelper->getIsActivePayPal();
        if( $activePayPal === true){
            $config['payment'][PayPal::CODE]['isActive'] = true;
            $config['payment'][PayPal::CODE]['title'] = 'PayPal' . $this->yabandpayPaymentHelper->getPayPalPayDesc();
        }else{
            $config['payment'][PayPal::CODE]['isActive'] = false;
        }

        $activeKlarna = $this->yabandpayPaymentHelper->getIsActiveKlarna();
        if( $activeKlarna === true){
            $config['payment'][Klarna::CODE]['isActive'] = true;
            $config['payment'][Klarna::CODE]['title'] = 'Klarna' . $this->yabandpayPaymentHelper->getKlarnaPayDesc();
        }else{
            $config['payment'][Klarna::CODE]['isActive'] = false;
        }

        $activeUnionPayWap = $this->yabandpayPaymentHelper->getIsActiveUnionPayWap();
        if( $activeUnionPayWap === true){
            $config['payment'][UnionPayWap::CODE]['isActive'] = true;
            $config['payment'][UnionPayWap::CODE]['title'] = Payment::UNIONPAYWAP . $this->yabandpayPaymentHelper->getUnionPayWapPayDesc();
        }else{
            $config['payment'][UnionPayWap::CODE]['isActive'] = false;
        }

        $activeUnionPayQuickPass = $this->yabandpayPaymentHelper->getIsActiveUnionPayQuickPass();
        if( $activeUnionPayQuickPass === true){
            $config['payment'][UnionPayQuickPass::CODE]['isActive'] = true;
            $config['payment'][UnionPayQuickPass::CODE]['title'] = Payment::UNIONPAYQUICKPASS . $this->yabandpayPaymentHelper->getUnionPayQuickPassPayDesc();
        }else{
            $config['payment'][UnionPayQuickPass::CODE]['isActive'] = false;
        }
        
        return $config;
    }
}
