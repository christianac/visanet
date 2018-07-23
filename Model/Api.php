<?php
Class Roger_Visanet_Model_Api {
  /**
   * @var string
   */
   protected $_wsdl;
 /**
   * @var SoapClient
   */
   public $_client;
 
   /**
    * provide the wsdl and endpoint so we can construct the soap object.
    * @param string $wsdl
    */
	
	
	public function cancelar_transaction($environment,$merchantId,$accessKey,$secretKey,$ordernumber)
	{
		
		if($environment==1){
    		$urlAccion= "https://api.vnforapps.com/api.tokenization/api/v2/merchant/".$merchantId."/cancelDeposit/".$ordernumber;	
		}
		else
		{
			$urlAccion= "https://devapi.vnforapps.com/api.tokenization/api/v2/merchant/".$merchantId."/cancelDeposit/".$ordernumber;	

		}		
	    
		$method="PUT";		
		$data = array("comment" => ""); // data u want to post                                                                   
		$data_string = json_encode($data);                                                                                   
	
	
		$ch = curl_init(); 
		curl_setopt($ch, CURLOPT_URL, $urlAccion);    
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);  
		curl_setopt($ch, CURLOPT_POST, true);                                                                   
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                                                  
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);     
		curl_setopt($ch, CURLOPT_USERPWD, $accessKey.':'.$secretKey);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC); 
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(   
			'Accept: application/json',
			'Content-Type: application/json')                                                           
		);             
		
																										  
		$errors = curl_error($ch);                                                                                                            
		$result = curl_exec($ch);
		$returnCode = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
		$info = curl_getinfo($ch);
		//print_r($info['request_header']);
		curl_close($ch); 
		//echo $returnCode;
		//var_dump($errors);	
		return json_decode($result);
		
	}
	public function depositar_transaction($environment,$merchantId,$accessKey,$secretKey,$ordernumber)
	{
		
	    if($environment==1){
    		$urlAccion= "https://api.vnforapps.com/api.tokenization/api/v2/merchant/".$merchantId."/deposit/".$ordernumber;	
		}
		else
		{
			$urlAccion= "https://devapi.vnforapps.com/api.tokenization/api/v2/merchant/".$merchantId."/deposit/".$ordernumber;	

		}		

		$method="PUT";		
		$data = array("comment" => ""); // data u want to post                                                                   
		$data_string = json_encode($data);                                                                                   
	    	 
		$ch = curl_init(); 
		curl_setopt($ch, CURLOPT_URL, $urlAccion);    
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);  
		curl_setopt($ch, CURLOPT_POST, true);                                                                   
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                                                  
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);     
		curl_setopt($ch, CURLOPT_USERPWD, $accessKey.':'.$secretKey);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC); 
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(   
			'Accept: application/json',
			'Content-Type: application/json')                                                           
		);             
		
																										  
		$errors = curl_error($ch);                                                                                                            
		$result = curl_exec($ch);
		$returnCode = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
		$info = curl_getinfo($ch);
		//print_r($info['request_header']);
		curl_close($ch); 
		//echo $returnCode;
		//var_dump($errors);	
		return json_decode($result);
	}
    public function anular_transaction($environment,$merchantId,$accessKey,$secretKey,$ordernumber)
	{
		if($environment==1){
    		$urlAccion= "https://api.vnforapps.com/api.tokenization/api/v2/merchant/".$merchantId."/void/".$ordernumber;	
		}
		else
		{
			$urlAccion= "https://devapi.vnforapps.com/api.tokenization/api/v2/merchant/".$merchantId."/void/".$ordernumber;	

		}		

		$method="PUT";		
		$data = array("comment" => ""); // data u want to post                                                                   
		$data_string = json_encode($data);                                                                                   
	   
		$ch = curl_init(); 
		curl_setopt($ch, CURLOPT_URL, $urlAccion);    
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);  
		curl_setopt($ch, CURLOPT_POST, true);                                                                   
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                                                  
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);     
		curl_setopt($ch, CURLOPT_USERPWD, $accessKey.':'.$secretKey);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC); 
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(   
			'Accept: application/json',
			'Content-Type: application/json')                                                           
		);             
		
																										  
		$errors = curl_error($ch);                                                                                                            
		$result = curl_exec($ch);
		$returnCode = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
		$info = curl_getinfo($ch);
		//print_r($info['request_header']);
		curl_close($ch); 
		//echo $returnCode;
		//var_dump($errors);	
		return json_decode($result);
	}	
	 public function query_transaction($environment,$merchantId,$accessKey,$secretKey,$ordernumber)
	{
		if($environment==1){
    		$urlAccion= "https://api.vnforapps.com/api.tokenization/api/v2/merchant/".$merchantId."/query/".$ordernumber;	
		}
		else
		{
			$urlAccion= "https://devapi.vnforapps.com/api.tokenization/api/v2/merchant/".$merchantId."/query/".$ordernumber;	

		}		

		$method="PUT";		
		$data = array("comment" => ""); // data u want to post                                                                   
		$data_string = json_encode($data);                                                                                   
	   
		$ch = curl_init(); 
		curl_setopt($ch, CURLOPT_URL, $urlAccion);    
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);  
		curl_setopt($ch, CURLOPT_POST, true);                                                                   
		curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);                                                                  
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);     
		curl_setopt($ch, CURLOPT_USERPWD, $accessKey.':'.$secretKey);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC); 
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(   
			'Accept: application/json',
			'Content-Type: application/json')                                                           
		);             
		
																										  
		$errors = curl_error($ch);                                                                                                            
		$result = curl_exec($ch);
		$returnCode = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
		$info = curl_getinfo($ch);
		//print_r($info['request_header']);
		curl_close($ch); 
		//echo $returnCode;
		//var_dump($errors);	
		return json_decode($result);
	}	
	public function create_token($environment,$urlDev, $urlProd, $amount,$merchantId,$accessKey,$secretKey,$uuid){
		
		$url = $urlDev;
		if($environment==1) { $url = $urlProd; }
		
		$header = array("Content-Type: application/json","VisaNet-Session-Key: $uuid");
		$request_body="{
			\"amount\":{$amount}
		}";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($ch, CURLOPT_USERPWD, "$accessKey:$secretKey");
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $request_body);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		$response = curl_exec($ch);
		Mage::log("url".$url);
		Mage::log('response of the token: '.$response  );
		$json = json_decode($response);
		/*$dato = $json->sessionKey;
		 Mage::log('response of the token: '.$dato  );*/
		return $json;
	}
	

	function authorization($environment,$urlDev, $urlProd, $merchantId,$transactionToken,$accessKey,$secretKey,$sessionToken){
		
		$url = $urlDev;
		if($environment==1) { $url = $urlProd; }
		
		
		$header = array("Content-Type: application/json","VisaNet-Session-Key: $sessionToken");
		$request_body="{
			\"transactionToken\":\"$transactionToken\",
			\"sessionToken\":\"$sessionToken\"
		}";
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		curl_setopt($ch, CURLOPT_USERPWD, "$accessKey:$secretKey");
		curl_setopt($ch, CURLOPT_HEADER, FALSE);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $request_body);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		$response = curl_exec($ch);
		 Mage::Log("respuesta api :".$response );
		 Mage::Log("environment: ".$environment);
		   Mage::Log("urdev: ".$urlDev);
		   Mage::Log("urlpro: ".$urlProd);
		   Mage::Log("merchantId: ".$merchantId);
		   Mage::Log("transactionToken: ".$transactionToken);
		   Mage::Log("accessKey: ".$accessKey);	   	   	   	   	   
		   Mage::Log("secretKey: ".$secretKey);
		   Mage::Log("sessionToken: ".$sessionToken);
		$json = json_decode($response);
		//$json = json_encode($json, JSON_PRETTY_PRINT);
		//$dato = $json->sessionKey;
		return $json;
	}

   
}