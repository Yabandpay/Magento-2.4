<?php
/**
 * @project    : YabanPay-Magento2
 * @description:
 * @user       : persi
 * @email      : persi@sixsir.com
 * @date       : 2018/8/31
 * @time       : 20:55
 */

namespace YaBandPay\Payment\Helper;

use Magento\Config\Model\ResourceModel\Config;
use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\App\ProductMetadataInterface;
use Magento\Framework\Locale\Resolver;
use Magento\Framework\Module\ModuleListInterface;
use Magento\Sales\Model\Order;
use Magento\Store\Model\StoreManagerInterface;
use YaBandPay\Payment\Logger\Logger;
use YaBandPay\Payment\Model\AliPay;
use YaBandPay\Api\Account;
use YaBandPay\Api\Api;
use YaBandPay\Api\Cryptography;
use YaBandPay\Api\Payment;
use YaBandPay\Api\Request;
use function implode;
use function round;
use function var_export;
use YaBandPay\Payment\Model\Bancontact;
use YaBandPay\Payment\Model\IDeal;
use YaBandPay\Payment\Model\WechatPay;
use YaBandPay\Payment\Model\Visa;
use YaBandPay\Payment\Model\MasterCard;
use YaBandPay\Payment\Model\PayPal;
use YaBandPay\Payment\Model\Klarna;
use YaBandPay\Payment\Model\UnionPayWap;
use YaBandPay\Payment\Model\UnionPayQuickPass;

/**
 * Class General
 *
 * @package YaBandPay\Payment\Helper
 * @description
 * @version 1.0.0
 */
class General extends AbstractHelper
{
    const ROUTER_NAME = 'yabandpay';

    const MODULE_CODE = 'yabandpay';
    const YABANDPAY_USERNAME = 'payment/' . self::MODULE_CODE . '/username';
    const YABANDPAY_TOKEN = 'payment/' . self::MODULE_CODE . '/token';
    const YABANDPAY_PAY_TIMEOUT = 'payment/' . self::MODULE_CODE . '/pay_timeout';

    const YABANDPAY_WECHATPAY_ACTIVE = 'payment/' . self::MODULE_CODE . '/wechatpay_active';
    const YABANDPAY_WECHATPAY_DESC = 'payment/' . self::MODULE_CODE . '/wechatpay_desc';
    const YABANDPAY_ALIPAY_ACTIVE = 'payment/' . self::MODULE_CODE . '/alipay_active';
    const YABANDPAY_ALIPAY_DESC = 'payment/' . self::MODULE_CODE . '/alipay_desc';
    const YABANDPAY_IDEAL_ACTIVE = 'payment/' . self::MODULE_CODE . '/ideal_active';
    const YABANDPAY_IDEAL_DESC = 'payment/' . self::MODULE_CODE . '/ideal_desc';
    const YABANDPAY_BANCONTACT_ACTIVE = 'payment/' . self::MODULE_CODE . '/bancontact_active';
    const YABANDPAY_BANCONTACT_DESC = 'payment/' . self::MODULE_CODE . '/bancontact_desc';
    const YABANDPAY_VISA_ACTIVE = 'payment/' . self::MODULE_CODE . '/visa_active';
    const YABANDPAY_VISA_DESC = 'payment/' . self::MODULE_CODE . '/visa_desc';
    const YABANDPAY_MASTERCARD_ACTIVE = 'payment/' . self::MODULE_CODE . '/mastercard_active';
    const YABANDPAY_MASTERCARD_DESC = 'payment/' . self::MODULE_CODE . '/mastercard_desc';
    const YABANDPAY_PAYPAL_ACTIVE = 'payment/' . self::MODULE_CODE . '/paypal_active';
    const YABANDPAY_PAYPAL_DESC = 'payment/' . self::MODULE_CODE . '/paypal_desc';
    const YABANDPAY_KLARNA_ACTIVE = 'payment/' . self::MODULE_CODE . '/klarna_active';
    const YABANDPAY_KLARNA_DESC = 'payment/' . self::MODULE_CODE . '/klarna_desc';
    const YABANDPAY_UNIONPAYWAP_ACTIVE = 'payment/' . self::MODULE_CODE . '/unionpaywap_active';
    const YABANDPAY_UNIONPAYWAP_DESC = 'payment/' . self::MODULE_CODE . '/unionpaywap_desc';
    const YABANDPAY_UNIONPAYQUICKPASS_ACTIVE = 'payment/' . self::MODULE_CODE . '/unionpayquickpass_active';
    const YABANDPAY_UNIONPAYQUICKPASS_DESC = 'payment/' . self::MODULE_CODE . '/unionpayquickpass_desc';
    const YABANDPAY_CURRENCY = 'payment/' . self::MODULE_CODE . '/currency';
    const YABANDPAY_FEE = 'payment/' . self::MODULE_CODE . '/fee';
    const YABANDPAY_CANCELURL = 'payment/' . self::MODULE_CODE . '/cancel_url';
    const YABANDPAY_AUTO_EMAIL = 'payment/' . self::MODULE_CODE . '/auto_send_email';
    const YABANDPAY_AUTO_INVOICE = 'payment/' . self::MODULE_CODE . '/auto_invoice';
    const YABANDPAY_DEBUG = 'payment/' . self::MODULE_CODE . '/debug';

    const YABANDPAY_STATUS_PENDING = 'payment/' . self::MODULE_CODE . '/pending_status';
    const YABANDPAY_ORDER_PAID_STATUS = 'payment/' . self::MODULE_CODE . '/order_paid_status';

    const PAY_NEW = 'new';

    const PAY_PENDING = 'pending';

    const PAY_PROCESSING = 'processing';

    const PAY_PAID = 'paid';

    const PAY_CANCELLED = 'canceled';

    const PAY_FAILED = 'failed';

    const PAY_REFUNDED = 'refunded';

    const PAY_EXPIRED = 'expired';

    const PAY_COMPLETED = 'completed';
    /**
     * @var ProductMetadataInterface
     */
    private $metadata;
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;
    /**
     * @var Config
     */
    private $resourceConfig;
    /**
     * @var \Magento\Framework\UrlInterface
     */
    private $urlBuilder;
    /**
     * @var ModuleListInterface
     */
    private $moduleList;
    /**
     * @var Logger
     */
    private $logger;
    /**
     * @var
     */
    private $apiAccount;
    /**
     * @var
     */
    private $apiToken;
    /**
     * @var Resolver
     */
    private $resolver;

    /**
     * @var Api
     */
    private static $apiInstance;

    /**
     * General constructor.
     *
     * @param Context $context
     * @param StoreManagerInterface $storeManager
     * @param Config $resourceConfig
     * @param ModuleListInterface $moduleList
     * @param ProductMetadataInterface $metadata
     * @param Resolver $resolver
     * @param Logger $logger
     */
    public function __construct(
        Context $context,
        StoreManagerInterface $storeManager,
        Config $resourceConfig,
        ModuleListInterface $moduleList,
        ProductMetadataInterface $metadata,
        Resolver $resolver,
        Logger $logger
    )
    {
        $this->storeManager = $storeManager;
        $this->resourceConfig = $resourceConfig;
        $this->urlBuilder = $context->getUrlBuilder();
        $this->moduleList = $moduleList;
        $this->metadata = $metadata;
        $this->resolver = $resolver;
        $this->logger = $logger;
        parent::__construct($context);
    }

    /**
     * Get admin value by path and storeId
     *
     * @param     $path
     * @param int $scopeCode
     *
     * @return mixed
     */
    public function getStoreConfig($path, $scopeCode = null)
    {
        return $this->scopeConfig->getValue(
            $path, \Magento\Store\Model\ScopeInterface::SCOPE_STORE, $scopeCode
        );
    }

    public function getApiAccount()
    {
        if($this->apiAccount){
            return $this->apiAccount;
        }
        $apiAccount = trim(
            $this->getStoreConfig(self::YABANDPAY_USERNAME)
        );
        if(empty($apiAccount)){
            $this->addTolog('error', 'YaBandPay API Account not set');
            return null;
        }
        $this->apiAccount = $apiAccount;

        return $this->apiAccount;
    }

    public function getApiToken()
    {
        if($this->apiToken){
            return $this->apiToken;
        }
        $apiToken = trim(
            $this->getStoreConfig(self::YABANDPAY_TOKEN)
        );
        if(empty($apiToken)){
            $this->addTolog('error', 'YaBandPay API Token not set');
            return null;
        }
        $this->apiToken = $apiToken;
        return $this->apiToken;
    }

    public function getIsActiveWechatPay()
    {
        return (bool)$this->getStoreConfig(self::YABANDPAY_WECHATPAY_ACTIVE);
    }

    public function getWechatPayDesc()
    {
        return ' ' . $this->getStoreConfig(self::YABANDPAY_WECHATPAY_DESC);
    }

    public function getIsActiveAlipay()
    {
        return (bool)$this->getStoreConfig(self::YABANDPAY_ALIPAY_ACTIVE);
    }

    public function getAlipayDesc()
    {
        return ' ' . $this->getStoreConfig(self::YABANDPAY_ALIPAY_DESC);
    }

    public function getIsActiveiDeal()
    {
        return (bool)$this->getStoreConfig(self::YABANDPAY_IDEAL_ACTIVE);
    }

    public function getiDealPayDesc()
    {
        return ' ' . $this->getStoreConfig(self::YABANDPAY_IDEAL_DESC);
    }


    public function getIsActiveBancontact()
    {
        return (bool)$this->getStoreConfig(self::YABANDPAY_BANCONTACT_ACTIVE);
    }

    public function getBancontactPayDesc()
    {
        return ' ' . $this->getStoreConfig(self::YABANDPAY_BANCONTACT_DESC);
    }

    public function getIsActiveVisa()
    {
        return (bool)$this->getStoreConfig(self::YABANDPAY_VISA_ACTIVE);
    }

    public function getVisaPayDesc()
    {
        return ' ' . $this->getStoreConfig(self::YABANDPAY_VISA_DESC);
    }

    public function getIsActiveMasterCard()
    {
        return (bool)$this->getStoreConfig(self::YABANDPAY_MASTERCARD_ACTIVE);
    }

    public function getMasterCardPayDesc()
    {
        return ' ' . $this->getStoreConfig(self::YABANDPAY_MASTERCARD_DESC);
    }

    public function getIsActivePayPal()
    {
        return (bool)$this->getStoreConfig(self::YABANDPAY_PAYPAL_ACTIVE);
    }

    public function getPayPalPayDesc()
    {
        return ' ' . $this->getStoreConfig(self::YABANDPAY_PAYPAL_DESC);
    }

    
    public function getIsActiveKlarna()
    {
        return (bool)$this->getStoreConfig(self::YABANDPAY_KLARNA_ACTIVE);
    }

    public function getKlarnaPayDesc()
    {
        return ' ' . $this->getStoreConfig(self::YABANDPAY_KLARNA_DESC);
    }

    public function getIsActiveUnionPayWap()
    {
        return (bool)$this->getStoreConfig(self::YABANDPAY_UNIONPAYWAP_ACTIVE);
    }

    public function getUnionPayWapPayDesc()
    {
        return ' ' . $this->getStoreConfig(self::YABANDPAY_UNIONPAYWAP_DESC);
    }

    public function getIsActiveUnionPayQuickPass()
    {
        return (bool)$this->getStoreConfig(self::YABANDPAY_UNIONPAYQUICKPASS_ACTIVE);
    }

    public function getUnionPayQuickPassPayDesc()
    {
        return ' ' . $this->getStoreConfig(self::YABANDPAY_UNIONPAYQUICKPASS_DESC);
    }

    public function getPayCurrency()
    {
        return $this->getStoreConfig(self::YABANDPAY_CURRENCY);
    }

    /**
     * Selected processing status
     *
     * @param int $storeId
     *
     * @return mixed
     */
    public function getStatusProcessing()
    {
        return $this->getStoreConfig(self::YABANDPAY_ORDER_PAID_STATUS) ?: Payment::PAY_PROCESSING;
    }

    /**
     * Write to log
     *
     * @param $type
     * @param $data
     */
    public function addTolog($type, $data)
    {
        if($this->getDebug()){
            if($type == 'error'){
                $this->logger->addErrorLog($data);
            }else{
                $this->logger->addInfoLog($data);
            }
        }
    }

    public function getApiInstance()
    {
        if(self::$apiInstance === null){
            $account = $this->getApiAccount();
            $token = $this->getApiToken();
            self::$apiInstance = new Api(new Account($account, $token), new Request(new Cryptography($token)));
        }
        return self::$apiInstance;
    }

    public function getOrderPayUrl($paymentMethodCode, Order $order)
    {
        // $customer = $customerFactory->create();
        switch ($paymentMethodCode) {
            case AliPay::CODE:
                $paymentMethod = Payment::ALIPAY;
                break;
            case WechatPay::CODE:
                $paymentMethod = Payment::WECHAT;
                break;
            case IDeal::CODE:
                $paymentMethod = Payment::IDEAL;
                break;
            case Bancontact::CODE:
                $paymentMethod = Payment::BANCONTACT;
                break;
            case Visa::CODE:
                $paymentMethod = Payment::VISA;
                break;
            case MasterCard::CODE:
                $paymentMethod = Payment::MASTERCARD;
                break;
            case PayPal::CODE:
                    $paymentMethod = Payment::PAYPAL;
                    break;
            case Klarna::CODE:
                $paymentMethod = Payment::KLARNA;
                break;
            case UnionPayWap::CODE:
                $paymentMethod = Payment::UNIONPAYWAP;
                break;
            case UnionPayQuickPass::CODE:
                $paymentMethod = Payment::UNIONPAYQUICKPASS;
                break;
            default:
                echo '不存在的支付方式CODE $paymentMethodCode = '.$paymentMethodCode;
                exit;
                break;
        }

        $orderTotalAmount = $this->getOrderTotalAmount($order);
        $description = $this->getOrderProduct($order);
        $notifyUrl = $this->getNotifyUrl();
        $redirectUrl = $this->getRedirectUrl();
        $timeout=$this->getPayTimeout();
        $cancelUrl=$this->getCancelUrl();
        if(empty($cancelUrl)){
            $cancelUrl='';
        }

        try {
            $pay_url = $this->getApiInstance()->payment(
                $paymentMethod,
                $order->getIncrementId(),
                $orderTotalAmount,
                $this->getPayCurrency(),
                $description,
                $redirectUrl,
                $notifyUrl,
                $timeout,
                json_encode([
                    'magento_order_id' => $order->getId(),
                    'plugin_version' => 'magento2.4.2-yabandpay1.0.6',
                    'cancel_url'=>$cancelUrl
                ]),
                $order->getCustomerEmail(),
                $cancelUrl
            );
        } catch (\Exception $e){
            $error_hint_url = 'https://partner.yabandpay.com/payments/error?message='.\urlencode($e->getMessage());
            $this->addTolog('error', 'getOrderPayUrl Exception:' . $e->getMessage());
            return $error_hint_url;
        }

        return $pay_url;
    }

    protected function getOrderProduct(Order $order)
    {
        $productList = [];
        foreach($order->getAllItems() as $item){
            $product = $item->getData();
            if(isset($product['name']) && !empty($product['name'])){
                $productList[] = $product['name']. '*' .intval($product['qty_ordered']);
            }
        }
        return implode(',', $productList);
    }

    /**
     * Redirect Url Builder /w OrderId & UTM No Override
     *
     * @param $orderId
     *
     * @return string
     */
    public function getRedirectUrl()
    {
        return $this->urlBuilder->getUrl(self::ROUTER_NAME . '/checkout/success');
    }

    /**
     * Webhook Url Builder
     *
     * @return string
     */
    public function getNotifyUrl()
    {
        return $this->urlBuilder->getUrl(self::ROUTER_NAME . '/checkout/notify');
    }

    /**
     * Checkout Url Builder
     *
     * @return string
     */
    public function getCheckoutUrl()
    {
        return $this->urlBuilder->getUrl('checkout/cart');
    }

    /**
     * Selected pending (payment) status
     *
     * @param int $storeId
     *
     * @return mixed
     */
    public function getStatusPending()
    {
        return $this->getStoreConfig(self::YABANDPAY_STATUS_PENDING) ?: Payment::PAY_NEW;
    }

    public function getFee()
    {
        $fee = $this->getStoreConfig(self::YABANDPAY_FEE);
        return $fee < 0 ? 0 : $fee;
    }

    public function getAuthSendEmail()
    {
        return (bool)$this->getStoreConfig(self::YABANDPAY_AUTO_EMAIL);
    }

    public function getAuthInvoice()
    {
        return (bool)$this->getStoreConfig(self::YABANDPAY_AUTO_INVOICE);
    }

    public function getPayTimeout()
    {
        $pay_timeout = $this->getStoreConfig(self::YABANDPAY_PAY_TIMEOUT);
        return $pay_timeout<0 ? 0 : $pay_timeout;
    }

    public function getDebug()
    {
        return (bool)$this->getStoreConfig(self::YABANDPAY_DEBUG);
    }

    public function getCancelUrl()
    {
        //return $this->urlBuilder->getUrl(self::ROUTER_NAME . '/checkout/cancel');
        $cancelurl=$this->getStoreConfig(self::YABANDPAY_CANCELURL);
        return $cancelurl;
    }


    /**
     * getOrderAmountByOrder
     *
     * @description
     * @version 1.0.0
     *
     * @param $order
     *
     * @return mixed
     */
    public function getOrderTotalAmount(Order $order)
    {
        $orderAmount = $order->getBaseGrandTotal();
        $fee = $this->getFee();
        if($fee > 0){
            $orderAmount += $orderAmount * ($fee / 100);
        }
        return (string)round($orderAmount, 2);
    }

    public function verifyAccountToken()
    {
        try{
            $info = $this->getApiInstance()->verify();
            return $info;
        }catch(\Exception $e){
            $this->addTolog('info', 'VerifyAccount Exception:' . $e->getMessage());
            return false;
        }
    }
}
