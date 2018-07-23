<?php

class Roger_Visanet_Helper_Data extends Magento\Payment\Helper\Data
{
	
	function getPaymentGatewayUrl() 
  {
    return Mage::getUrl('visanet/payment/gateway', array('_secure' => false));
  }
  
  function getConfigData($config_path){
	  	return Mage::getStoreConfig('payment/visanet/'.$config_path);
        
  }
  

}
