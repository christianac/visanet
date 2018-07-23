<?php
// app/code/local/Envato/Custompaymentmethod/Model/Paymentmethod.php
use Magento\Payment\Model\Method\AbstractMethod;
use Magento\Framework\DataObject;
class Roger_Visanet_Model_Paymentmethod extends AbstractMethod {
  //payment method code. Used in XML config and Db.
  protected $_code  = 'visanet';
  //flag which causes initalize() to run when checkout is completed. 
    protected $_isInitializeNeeded      = true;
    //Disable payment method in admin/order pages.
    protected $_canUseInternal          = false;
    //Disable multi-shipping for this payment module.
    protected $_canUseForMultishipping  = false;
    //token provided by mockpay api when communicating back to magento.
  /*form of the fields */
  protected $_formBlockType = 'visanet/form_visanet';
  /*info form */
  protected $_infoBlockType = 'visanet/info_visanet';
 
 public function __construct(
        array $data = []
    )
 {
    parent::__construct(
            $data
        );
        $this->initializeData($data);
  }
  public function assignData(DataObject $data)
  {
    $info = $this->getInfoInstance();
     
    if ($data->getCustomFieldOne())
    {
      //$info->setCustomFieldOne($data->getCustomFieldOne());
    }
     
    if ($data->getCustomFieldTwo())
    {
     // $info->setCustomFieldTwo($data->getCustomFieldTwo());
    }
 
    return $this;
  }
 
  public function validate()
  {
    parent::validate();
    $info = $this->getInfoInstance();
     
    if (!$info->getCustomFieldOne())
    {
      //$errorCode = 'invalid_data';
      //$errorMsg = $this->_getHelper()->__("CustomFieldOne is a required field.\n");
    }
     
    if (!$info->getCustomFieldTwo())
    {
      //$errorCode = 'invalid_data';
      //$errorMsg .= $this->_getHelper()->__('CustomFieldTwo is a required field.');
    }
 
    if ($errorMsg) 
    {
      Mage::throwException($errorMsg);
    }
 
    return $this;
  }
	 /**
     * 
     * <payment_action>Sale</payment_action>
     * Initialize payment method. Called when purchase is complete.
     * Order is created after this method is called.
     *
     * @param string $paymentAction
     * @param Varien_Object $stateObject
     *
     * @return Mage_Payment_Model_Abstract
     */
    public function initialize($paymentAction, $stateObject)
    {       
        Mage::log('Called ' . __METHOD__ . ' with payment ' . $paymentAction);
        parent::initialize($paymentAction, $stateObject);
        
        //Payment is also used for refund and other backend functions.
        //Verify this is a sale before continuing.
        if($paymentAction != 'sale'){
            return $this;
        }
        
        //Set the default state of the new order.
        $state = Mage_Sales_Model_Order::STATE_PENDING_PAYMENT; // state now = 'pending_payment'
        $stateObject->setState($state); 
        $stateObject->setStatus('pending_payment');
        $stateObject->setIsNotified(false);
        
        //Extract order details and send to mockpay api. Get api token and save it to checkout/session.
        try{
            $this->_customBeginPayment();
        }catch (Exception $e){
            Mage::log($e);    
            Mage::throwException($e->getMessage());
        }
        return $this;
    }
	
	
	
	
	
	 /**
     * 
     * Extract cart/quote details and send to api.
     * Respond with token
     * @throws SoapFault
     * @throws Mage_Exception
     * @throws Exception
     */
    protected  function _customBeginPayment(){
		
		
	     Mage::log('got to the custonBeginPayment to call the api' );
		 
		 /*create the variable guid manager*/
		 $guicontroller= new Roger_Visanet_Model_GuidManager();
		 
		 /* create the info variable **/
		 $info=$this->getInfoInstance();
		 Mage::log('got to the custonBeginPayment to call the api1' );
		/*First create the guid and assign it to the customfieldone -- customfiledone is sessiontoken*/
		 $info->setCustomFieldOne($guicontroller->createGuidToken());
		 
		 Mage::log('got to the custonBeginPayment to call the api2' );
		 
        //Retrieve the wsdl and endpoint from the magento global config table.
        $api = new Roger_Visanet_Model_Api();
        
		 Mage::log('create the url' );
		
		
        //Most API's require an id and key to access them. Mock pay is unauthenticated.
        //This information should be stored in the configData. You configure these options in the system.xml file.
        //$api->setMerchantKey($this->getConfigData('merchantkey'));
        //$api->setMerchantId($this->getConfigData('merchantid'));

        //Retrieve cart/quote information.
        $sessionCheckout = Mage::getSingleton('checkout/session');
        $quoteId = $sessionCheckout->getQuoteId();
        //The quoteId will be removed from the session once the order is placed. 
        //If you need it, save it to the session yourself.
        $sessionCheckout->setData('rogerVisanetQuoteId',$quoteId);
        
        $quote = Mage::getModel("sales/quote")->load($quoteId);
        $grandTotal = $quote->getData('grand_total');
        $subTotal = $quote->getSubtotal();
        $shippingHandling = ($grandTotal-$subTotal);
        Mage::Log("Sub Total: $subTotal | Shipping & Handling: $shippingHandling | Grand Total $grandTotal");
        Mage::Log("Payment:". $quote->getPayment()->getMethod());
        //Set required items.
        $billingData = $quote->getBillingAddress()->getData();
        $apiEmail = $billingData['email'];
        $apiAmount = $grandTotal;
        $apiOrderId = (str_pad($quoteId, 9,0,STR_PAD_LEFT));
        //Retrieve items from the quote.
        $items = $quote->getItemsCollection()->getItems();
		
        $apiDesc = '';
        foreach($items as $item){
           $sku = $item->getSku();
           $unitPrice = $item->getPrice();
           $qty = $item->getQty();
           $desc = $item->getName();
           Mage::log("LINEITEM: $sku - $unitPrice - $qty - $desc \n");
           //since our simple api doesn't support line items.
           //we can provide more info via the description field.
           $apiDesc .= $sku . '(x' . $qty . ') ';
        }
        //Add Shipping as line item so total matches magento's charge.
        if($shippingHandling > 0){
            $apiDesc .= ' + S&H';
        }
		Mage::log("cart items".Mage::helper('checkout/cart')->getItemsCount())	;
		Mage::log("quote is active ".$quote->getIsActive())	;
        //Build urls back to our modules controller actions as required by the api.
        $oUrl = Mage::getModel('core/url');
        $apiHrefSuccess = $oUrl->getUrl("mockpay/standard/success");
        $apiHrefFailure = $oUrl->getUrl("mockpay/standard/failure");
        $apiHrefCancel = $oUrl->getUrl("mockpay/standard/cancel");
		
        //Soap Call
		
		
		//Real Info 
		
		$apienvironment=$this->getConfigData('production');
		$apiinvocationdev=$this->getConfigData('invocationapidev');
		$apiinvocationprod=$this->getConfigData('invocationapipro');

		$merchantId=$this->getConfigData('merchantid');
		$apiaccessKey=$this->getConfigData('merchantaccesskey');
		$apisecretKey=$this->getConfigData('merchantsecretaccesskey');
		$uuid=$info->getCustomFieldOne();
		$quote->getPayment()->setData('custom_field_one',$uuid);
		$quote->getPayment()->save();
		
		
		Mage::log("Guid:".$uuid);
		 Mage::log("pass : quoteid : ". $quoteId);
		 Mage::log("pass : quoteid : ".	 $info->getCustomFieldOne());
        $response = $api->create_token($apienvironment,$apiinvocationdev,$apiinvocationprod, $apiAmount,$merchantId,$apiaccessKey,$apisecretKey,$uuid);
		
        if(property_exists($response,"sessionKey")){
            Mage::log("Successfully preparePayment with visanetpayment time expiration: ".$response->expirationTime.$response->sessionKey);
			
            $sessionKeyReturn = $response->sessionKey;
			$sessionToken=$info->getCustomFieldOne();
			$sessionExpirationTime=$response->expirationTime;
            
			//add into the session checkdata
				        
			$sessionCheckout->setData('sessionKeyReturn',$sessionKeyReturn);
			$sessionCheckout->setData('sessionToken',$sessionToken);
			$sessionCheckout->setData('sessionExpirationTime',$sessionExpirationTime);
			  
			
        }else{
				//$dato = $json->sessionKey;
           $msg = '(' . $response->sessionKey . ') ' . $response->sessionKey;
           Mage::log("BeginPayment failed with [$msg]".$response->sessionKey);
           //You can add error messages to the checkout process. These show up when you view the cart.
           $sessionCheckout->addError('An unrecoverable error occured while processing your payment information. ' . $msg);
           Mage::throwException($msg);
        }
        return $this;
    }
	
	
	
  public function getOrderPlaceRedirectUrl()
  {
	  mage::log('Called custom jose pecho' . __METHOD__);
      return Mage::getUrl('visanet/payment/redirect', array('_secure' => false));
	
  }
}