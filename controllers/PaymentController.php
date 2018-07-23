<?php
// app/code/local/Envato/Custompaymentmethod/controllers/PaymentController.php
use Magento\Framework\App\Action\Action;
class Roger_Visanet_PaymentController extends Action 

{
  public function execute() 
  {
    /*if ($this->getRequest()->get("orderId"))
    {
      $arr_querystring = array(
        'flag' => 1, 
        'orderId' => $this->getRequest()->get("orderId")
      );
       
      Mage_Core_Controller_Varien_Action::_redirect('visanet/payment/response', array('_secure' => false, '_query'=> $arr_querystring));
    }*/
	$sessionCheckout = Mage::getSingleton('checkout/session');
	$quoteId = $sessionCheckout->getQuoteId();
	$quote = Mage::getModel("sales/quote")->load($quoteId);


	$transctionToken=$this->getRequest()->getParam('transactionToken');
	
	if(!isset($transctionToken) || trim($transctionToken)==='')
	{
		echo("error")	;
		//redirect to checkout cart
	}else
	{
	  /*Mage::Log("quoteid".$quoteId);
	  Mage::Log("custom field one".$quote->getPayment()->getData('custom_field_one'));
	  Mage::Log("custom Field two:".$quote->getPayment()->getData('custom_field_two'));
	  Mage::Log("Transction Token recover".$this->getRequest()->getParam('transactionToken'));
	  Mage::Log("Payment:". $quote->getPayment()->getMethod());
  	  Mage::Log("MerchantId:".Mage::helper('visanet')->getConfigData('merchantid'));*/
	  
	  //Retrieve the wsdl and endpoint from the magento global config table.
	  $quote->getPayment()->setData('custom_field_two',$this->getRequest()->getParam('transactionToken'));
	  $quote->getPayment()->save();
		

	  
	  $environment=Mage::helper('visanet')->getConfigData('production');
	  $urlDev=Mage::helper('visanet')->getConfigData('authorizationapidev');
	  $urlProd=Mage::helper('visanet')->getConfigData('authorizationapipro');
	  $merchantId=Mage::helper('visanet')->getConfigData('merchantid');
	  $transactionToken=$this->getRequest()->getParam('transactionToken');
	  $accessKey=Mage::helper('visanet')->getConfigData('merchantaccesskey');
	  $secretKey=Mage::helper('visanet')->getConfigData('merchantsecretaccesskey');
	  $sessionToken=$quote->getPayment()->getData('custom_field_one');
	
	  
      $api = new Roger_Visanet_Model_Api();
	  $response = $api->authorization($environment,$urlDev, $urlProd, $merchantId,$transactionToken,$accessKey,$secretKey,$sessionToken);
	  $sessionCheckout->setData('visaErrorCode',$response->errorCode);
      $sessionCheckout->setData('visaErrorMessage',$response->data->DSC_COD_ACCION);
	  $sessionCheckout->setData('visaTransactionUUID',$response->transactionUUID);
      $sessionCheckout->setData('visaExternalTransactionId',$response->externalTransactionId);
	  $sessionCheckout->setData('visaTransactionDateTime',$response->transactionDateTime);
 	  $sessionCheckout->setData('visaTransactionDuration',$response->transactionDuration);
	  $sessionCheckout->setData('visaMerchantId',$response->merchantId);
	  $sessionCheckout->setData('visaUserTokenId',$response->userTokenId);
	  $sessionCheckout->setData('visaAliasName',$response->aliasName);
      $sessionCheckout->setData('visaData',$response->data);
	   $sessionCheckout->setData('visaCodaction',$response->data->CODACCION);
	  
	  if($response->data->CODACCION=="000"){
		  
	
		 Mage_Core_Controller_Varien_Action::_redirect('visanet/payment/success', array('_secure' => false));
	  
	  }else {
		   
		   Mage_Core_Controller_Varien_Action::_redirect('visanet/payment/cancel', array('_secure' => false));
	  }
	  
	  
	  
	}

  }
   
  public function redirectAction() 
  {

	
	$sessionCheckout = Mage::getSingleton('checkout/session');
	/*Mage::log("Session Order Id:".$sessionCheckout->getData("apiOrderId"));
	Mage::log("Session order increment id:".$sessionCheckout->getData("apiOrderIncrementId"));
	Mage::log("Session old quote:".$sessionCheckout->getData("rogerVisanetQuoteId"));
	Mage::log("Session new quote:".$sessionCheckout->getQuoteId());*/
	$quoteId = $sessionCheckout->getQuoteId();
	$quote = Mage::getModel("sales/quote")->load($quoteId);
	
	$quote->setIsActive(1)->save();		
	
    $this->loadLayout();
    $block = $this->getLayout()->createBlock('Mage_Core_Block_Template','visanet',array('template' => 'visanet/redirect.phtml'));
    $this->getLayout()->getBlock('content')->append($block);
    $this->renderLayout();
  }
 
  public function depositarAction(){
	$orderId= $this->getRequest()->getParam('orderId');
	$order=Mage::getModel('sales/order')->load($orderId);
	$api = new Roger_Visanet_Model_Api();	
	$environment=$order->getPayment()->getMethodInstance()->getConfigData('production');
	$merchantId=$order->getPayment()->getMethodInstance()->getConfigData('merchantid');
	$accessKey=$order->getPayment()->getMethodInstance()->getConfigData('merchantaccesskey');
	$secretKey=$order->getPayment()->getMethodInstance()->getConfigData('merchantsecretaccesskey');
	$ordernumber=$order->getIncrementId();	

    $response = $api->depositar_transaction($environment,$merchantId,$accessKey,$secretKey,$ordernumber);

    $this->_redirect('adminhtml/sales_order/view', array('order_id' => $this->getRequest()->getParam('orderId'),'visaErrorCode' => $response->errorCode, 'visaErrorMessage' => $response->errorMessage));
  }
  public function anularAction(){	 
	$orderId= $this->getRequest()->getParam('orderId');
	$order=Mage::getModel('sales/order')->load($orderId);
	$api = new Roger_Visanet_Model_Api();	
	$environment=$order->getPayment()->getMethodInstance()->getConfigData('production');
	$merchantId=$order->getPayment()->getMethodInstance()->getConfigData('merchantid');
	$accessKey=$order->getPayment()->getMethodInstance()->getConfigData('merchantaccesskey');
	$secretKey=$order->getPayment()->getMethodInstance()->getConfigData('merchantsecretaccesskey');
	$ordernumber=$order->getIncrementId();	
    $response = $api->anular_transaction($environment,$merchantId,$accessKey,$secretKey,$ordernumber);

    $this->_redirect('adminhtml/sales_order/view', array('order_id' => $this->getRequest()->getParam('orderId'),'visaErrorCode' => $response->errorCode, 'visaErrorMessage' => $response->errorMessage));
  }
  public function cancelarAction(){	 
    $orderId= $this->getRequest()->getParam('orderId');
	$order=Mage::getModel('sales/order')->load($orderId);
	$api = new Roger_Visanet_Model_Api();	
	$environment=$order->getPayment()->getMethodInstance()->getConfigData('production');
	$merchantId=$order->getPayment()->getMethodInstance()->getConfigData('merchantid');
	$accessKey=$order->getPayment()->getMethodInstance()->getConfigData('merchantaccesskey');
	$secretKey=$order->getPayment()->getMethodInstance()->getConfigData('merchantsecretaccesskey');
	$ordernumber=$order->getIncrementId();	
    $response = $api->cancelar_transaction($environment,$merchantId,$accessKey,$secretKey,$ordernumber);

    $this->_redirect('adminhtml/sales_order/view', array('order_id' => $this->getRequest()->getParam('orderId'),'visaErrorCode' => $response->errorCode, 'visaErrorMessage' => $response->errorMessage));
  }
  public function responseAction() 
  {
    /*if ($this->getRequest()->get("flag") == "1" && $this->getRequest()->get("orderId")) 
    {
      $orderId = $this->getRequest()->get("orderId");
      $order = Mage::getModel('sales/order')->loadByIncrementId($orderId);
      $order->setState(Mage_Sales_Model_Order::STATE_PAYMENT_REVIEW, true, 'Payment Success.');
      $order->save();
       
      Mage::getSingleton('checkout/session')->unsQuoteId();
      Mage_Core_Controller_Varien_Action::_redirect('checkout/onepage/success', array('_secure'=> false));
    }
    else
    {
      Mage_Core_Controller_Varien_Action::_redirect('checkout/onepage/error', array('_secure'=> false));
    }*/
  }
  
  
  
  
    protected function _getApiQuoteId(){
        $quoteId = Mage::getSingleton('checkout/session')->getData('apiQuoteId');
        //Mage::log('Returned quoteId ' . $quoteId);
        return $quoteId;
    }
    
    protected  function _getApiOrderId(){
        $orderId = Mage::getSingleton('checkout/session')->getData('apiOrderId');
        //Mage::log('Returned orderId ' . $orderId);
        return $orderId;
    }
	
	public function  successAction()
    {
        //mage::log('Called custom ' . __METHOD__);
        /*if(!$this->_isValidToken()){
            Mage::Log('Token is invalid.');
            $this->_redirect('checkout/cart');    
        }*/
        
       /* try{
            $wsdl = Mage::getStoreConfig('payment/mockpay/wsdl');
            $api = new My_Mockpay_Model_Api($wsdl);
            $response = $api->queryPayment($this->_getApiToken());
        }catch (Exception $e){
            Mage::throwException($e->getMessage(),$e->getCode());
        }*/
        
        //if($response['hasErrors'] == 'N' && empty($response['errorMessage'])){
            //payment was captured successfully.
            //Mage::log('Payment Captured successfully');           
            $session = Mage::getSingleton('checkout/session');
            $session->setQuoteId($this->_getApiQuoteId());                        
            //Change the state of the order to pending and add comment.
            /* @var $order Mage_Sales_Model_Order */
            $order = Mage::getSingleton('sales/order');
            $order->load($this->_getApiOrderId());
            $state = $order->getState();
            $order2 = Mage::getModel('sales/order')->load(Mage::getSingleton('checkout/session')->getLastOrderId());
            if($order2->getCustomerId() === NULL){
                $custom = $order2->getBillingAddress()->getFirstname()." ".$order2->getBillingAddress()->getLastname();
            } else {
                //else, they're a normal registered user.
                $customer = Mage::getModel('customer/customer')->load($order2->getCustomerId());
                $custom = $customer->getDefaultBillingAddress()->getFirstname()." ".$customer->getDefaultBillingAddress()->getLastname();
            }
            if($state == Mage_Sales_Model_Order::STATE_PENDING_PAYMENT){
               $this->_createInvoice($order);
                //sets the status to 'pending'.
				$data= $session->getData('visaData');
                $msg = 'Pago via Visanet';
                $order->setState(Mage_Sales_Model_Order::STATE_PROCESSING,true,$msg,false);
                $order->save();
                
                /* @var $quote Mage_Sales_Model_Quote */
                $quote = Mage::getSingleton('checkout/session')->getQuote();
                $quote->setIsActive(false)->save();
				
				$Message="<b>Visa</b> </br><div style=\"text-align:left; width:100%;\"><b> Su pago fue aceptado  </b></br> <b>Cliente:</b> ".$custom.
        "</br> <b>Numero de pedido:</b> ".$data->NUMORDEN.
				" </br><b>Numero de Tarjeta:</b>".$data->PAN.
				"</br><b>Fecha y Hora:</b> ".$data->FECHAYHORA_TX."</br><a href='#' target='_blank'>Ver Términos y Condiciones</a><br/><input type ='button' onclick='window.print();' class='button' value='Imprimir'></div> ";
				Mage::getSingleton('core/session')->addSuccess($Message);
            }
           
            $this->_redirect('checkout/onepage/success', array('_secure'=>true));
        //}
    }
	
	 protected function _createInvoice($orderObj)
    {
        if (!$orderObj->canInvoice()) {
            return false;
        }
        $invoice = $orderObj->prepareInvoice();
        $invoice->register();
        if($invoice->canCapture()){
            $invoice->capture();
        }
        $invoice->save();
        $orderObj->addRelatedObject($invoice);
        return $invoice;
    }
    /**
     * When a customer cancel payment from api
     */
    protected function _cancelAction()
    {
        //Mage::Log('Called ' . __METHOD__);
       /*if(!$this->_isValidToken()){
            Mage::Log('Token is invalid.');
            $this->_redirect('checkout/cart');    
        }*/
        //TODO: add Api specific values. Copied form paypal standard.
        $session = Mage::getSingleton('checkout/session');
        $session->setQuoteId($this->_getApiQuoteId());
         /* @var $quote Mage_Sales_Model_Quote */
        $quote = $session->getQuote();
        $quote->setIsActive(false)->save();
        $quote->delete();
        
        $orderId = $this->_getApiOrderId();
        Mage::Log('Canceling order ' . $orderId);
        if ($orderId) {
            $order = Mage::getSingleton('sales/order');
            $order->load($orderId);
            if ($order->getId()) {
                $state = $order->getState();
                if($state == Mage_Sales_Model_Order::STATE_PENDING_PAYMENT){
                    $order->cancel()->save();
					
					
					$errorManager = new Roger_Visanet_Model_ErrorList();
					$errordescriptionvisanet= $errorManager->get_errorDescription($session->getData('visaErrorCode'));
					$data= $session->getData('visaData');
					
                    Mage::getSingleton('core/session')->addNotice('<b>Visa</b> </br><b>El Pago fue Negado.</b></br> <b>Codigo de Error Retornado :</b>'. $this->getMotivo($session->getData('visaCodaction')).' </br><b>Numero de pedido:</b> '.$data->NUMORDEN .'</br><b>Fecha y Hora:</b> '.$data->FECHAYHORA_TX .'</br><b>Descripcion:</b> '.$errordescriptionvisanet);
                }
            }
        }
        //$this->_redirect('checkout/cart');
        $this->_redirect('checkout/onepage/failure', array('_secure'=>true));
    }
    
  
   /**
     * Handles 'falures' from api
     * Failure could occur if api system failure, insufficent funds, or system error.
     * @throws Exception
     */
    public function failureAction(){
        //Mage::Log('Called ' . __METHOD__);
        $this->cancelAction();
    }
    
    public function cancelAction(){
        //Mage::Log('Called ' . __METHOD__);
        $this->_cancelAction();
    }

    public function getMotivo($id){
    $motivos = array(101 => "(101) Operación Denegada. Tarjeta Vencida. Verifique los datos en su tarjeta e ingréselos correctamente.",
                                102 => "(102) Operación Denegada. Contactar con entidad emisora de su tarjeta. ",
                                104 => "(104) Operación Denegada. Operación no permitida para esta tarjeta. Contactar con la entidad emisora de su tarjeta. ",
                                106 => "(106) Operación Denegada. Intentos de clave secreta excedidos. Contactar con la entidad emisora de su tarjeta. ",
                                107 => "(107) Operación Denegada. Contactar con la entidad emisora de su tarjeta. ",
                                108 => "(108) Operación Denegada. Contactar con la entidad emisora de su tarjeta. ",
                                109 => "(109) Operación Denegada. Contactar con el comercio. ",
                                110 => "(110) Operación Denegada. Operación no permitida para esta tarjeta. Contactar con la entidad emisora de su tarjeta. ",
                                111 => "(111) Operación Denegada. Contactar con el comercio. ",
                                112 => "(112) Operación Denegada. Se requiere clave secreta. ",
                                116 => "(116) Operación Denegada. Fondos insuficientes. Contactar con entidad emisora de su tarjeta ",
                                117 => "(117) Operación Denegada. Clave secreta incorrecta. ",
                                118 => "(118) Operación Denegada. Tarjeta Inválida. Contactar con entidad emisora de su tarjeta. ",
                                119 => "(119) Operación Denegada. Intentos de clave secreta excedidos. Contactar con entidad emisora de su tarjeta. ",
                                121 => "(121) Operación Denegada. ",
                                126 => "(126) Operación Denegada. Clave secreta inválida. ",
                                129 => "(129) Operación Denegada. Código de seguridad invalido. Contactar con entidad emisora de su tarjeta ",
                                180 => "(180) Operación Denegada. Tarjeta Inválida. Contactar con entidad emisora de su tarjeta. ",
                                181 => "(181) Operación Denegada. Tarjeta con restricciones de débito. Contactar con entidad emisora de su tarjeta. ",
                                182 => "(182) Operación Denegada. Tarjeta con restricciones de crédito. Contactar con entidad emisora de su tarjeta. ",
                                183 => "(183) Operación Denegada. Problemas de comunicación. Intente más tarde. ",
                                190 => "(190) Operación Denegada. Contactar con entidad emisora de su tarjeta. ",
                                191 => "(191) Operación Denegada. Contactar con entidad emisora de su tarjeta. ",
                                192 => "(192) Operación Denegada. Contactar con entidad emisora de su tarjeta. ",
                                199 => "(199) Operación Denegada. ",
                                201 => "(201) Operación Denegada. Tarjeta vencida. Contactar con entidad emisora de su tarjeta. ",
                                202 => "(202) Operación Denegada. Contactar con entidad emisora de su tarjeta ",
                                204 => "(204) Operación Denegada. Operación no permitida para esta tarjeta. Contactar con entidad emisora de su tarjeta. ",
                                206 => "(206) Operación Denegada. Intentos de clave secreta excedidos. Contactar con la entidad emisora de su tarjeta. ",
                                207 => "(207) Operación Denegada. Contactar con entidad emisora de su tarjeta.. ",
                                208 => "(208) Operación Denegada. Contactar con entidad emisora de su tarjeta. ",
                                209 => "(209) Operación Denegada. Contactar con entidad emisora de su tarjeta ",
                                263 => "(263) Operación Denegada. Contactar con el comercio. ",
                                264 => "(264) Operación Denegada. Entidad emisora de la tarjeta no está disponible para realizar la autenticación. ",
                                265 => "(265) Operación Denegada. Clave secreta del tarjetahabiente incorrecta. Contactar con entidad emisora de su tarjeta. ",
                                266 => "(266) Operación Denegada. Tarjeta Vencida. Contactar con entidad emisora de su tarjeta. ",
                                280 => "(280) Operación Denegada. Clave secreta errónea. Contactar con entidad emisora de su tarjeta. ",
                                290 => "(290) Operación Denegada. Contactar con entidad emisora de su tarjeta. ",
                                300 => "(300) Operación Denegada. Número de pedido del comercio duplicado. Favor no atender. ",
                                306 => "(306) Operación Denegada. Contactar con entidad emisora de su tarjeta. ",
                                401 => "(401) Operación Denegada. Contactar con el comercio. ",
                                402 => "(402) Operación Denegada. ",
                                403 => "(403) Operación Denegada. Tarjeta no autenticada. ",
                                404 => "(404) Operación Denegada. Contactar con el comercio. ",
                                405 => "(405) Operación Denegada. Contactar con el comercio. ",
                                406 => "(406) Operación Denegada. Contactar con el comercio. ",
                                407 => "(407) Operación Denegada. Contactar con el comercio. ",
                                408 => "(408) Operación Denegada. Código de seguridad no coincide. Contactar con entidad emisora de su tarjeta ",
                                409 => "(409) Operación Denegada. Código de seguridad no procesado por la entidad emisora de la tarjeta ",
                                410 => "(410) Operación Denegada. Código de seguridad no ingresado. ",
                                411 => "(411) Operación Denegada. Código de seguridad no procesado por la entidad emisora de la tarjeta  ",
                                412 => "(412) Operación Denegada. Código de seguridad no reconocido por la entidad emisora de la tarjeta ",
                                413 => "(413) Operación Denegada. Contactar con entidad emisora de su tarjeta. ",
                                414 => "(414) Operación Denegada. ",
                                415 => "(415) Operación Denegada. ",
                                416 => "(416) Operación Denegada. ",
                                417 => "(417) Operación Denegada. ",
                                418 => "(418) Operación Denegada. ",
                                419 => "(419) Operación Denegada. ",
                                420 => "(420) Operación Denegada. Tarjeta no es VISA. ",
                                421 => "(421) Operación Denegada. Contactar con entidad emisora de su tarjeta. ",
                                422 => "(422) Operación Denegada. El comercio no está configurado para usar este medio de pago. Contactar con el comercio. ",
                                423 => "(423) Operación Denegada. Se canceló el proceso de pago. ",
                                424 => "(424) Operación Denegada. ",
                                666 => "(666) Operación Denegada. Problemas de comunicación. Intente más tarde. ",
                                667 => "(667) Operación Denegada. Transacción sin respuesta de Verified by Visa. ",
                                668 => "(668) Operación Denegada. Contactar con el comercio. ",
                                669 => "(669) Operación Denegada. Contactar con el comercio. ",
                                670 => "(670) Operación Denegada. Contactar con el comercio. ",
                                672 => "(672) Operación Denegada. Módulo antifraude. ",
                                673 => "(673) Operación Denegada. Contactar con el comercio. ",
                                674 => "(674) Operación Denegada. Contactar con el comercio. ",
                                676 => "(676) Operación Denegada. Contactar con el comercio. ",
                                677 => "(677) Operación Denegada. Contactar con el comercio. ",
                                678 => "(678) Operación Denegada. Contactar con el comercio. ",
                                904 => "(904) Operación Denegada. ",
                                909 => "(909) Operación Denegada. Problemas de comunicación. Intente más tarde. ",
                                910 => "(910) Operación Denegada. ",
                                912 => "(912) Operación Denegada. Entidad emisora de la tarjeta no disponible ",
                                913 => "(913) Operación Denegada. ",
                                916 => "(916) Operación Denegada. ",
                                928 => "(928) Operación Denegada. ",
                                940 => "(940) Operación Denegada. ",
                                941 => "(941) Operación Denegada. ",
                                942 => "(942) Operación Denegada. ",
                                943 => "(943) Operación Denegada. ",
                                945 => "(945) Operación Denegada. ",
                                946 => "(946) Operación Denegada. Operación de anulación en proceso. ",
                                947 => "(947) Operación Denegada. Problemas de comunicación. Intente más tarde. ",
                                948 => "(948) Operación Denegada. ",
                                949 => "(949) Operación Denegada. ",
                                965 => "(965) Operación Denegada. ");
      return $motivos[$id];
      }

}