<?php
Class Roger_Visanet_Model_GuidManager {
  /**
   * @var string
   */
  

	public function createGuidToken(){
		if (function_exists('com_create_guid')){
			return com_create_guid();
		}else{
			mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
			$charid = strtoupper(md5(uniqid(rand(), true)));
			$hyphen = chr(45);// "-"
			$uuid = chr(123)// "{"
				.substr($charid, 0, 8).$hyphen
				.substr($charid, 8, 4).$hyphen
				.substr($charid,12, 4).$hyphen
				.substr($charid,16, 4).$hyphen
				.substr($charid,20,12).$hyphen
				.chr(125);// "}"
			$uuid = substr($uuid, 1, 36);
			return $uuid;
		}		
	}

}