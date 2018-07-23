<?php
Class Roger_Visanet_Model_ErrorList{ 
   /**
    * provide the wsdl and endpoint so we can construct the soap object.
    * @param string $wsdl
    */
	private $errorArray = array(
								array(101,"Operación Denegada. Tarjeta Vencida. Verifique los datos en su tarjeta e ingréselos correctamente."),
								array(102,"Operación Denegada. Contactar con entidad emisora de su tarjeta."),
								array(104,"Operación Denegada. Operación no permitida para esta tarjeta. Contactar con la entidad emisora de su tarjeta."),
								array(106,"Operación Denegada. Intentos de clave secreta excedidos. Contactar con la entidad emisora de su tarjeta."),
								array(107,"Operación Denegada. Contactar con la entidad emisora de su tarjeta."),
								array(108,"Operación Denegada. Contactar con la entidad emisora de su tarjeta."),
								array(109,"Operación Denegada. Contactar con el comercio."),
								array(110,"Operación Denegada. Operación no permitida para esta tarjeta. Contactar con la entidad emisora de su tarjeta."),
								array(111,"Operación Denegada. Contactar con el comercio."),
								array(112,"Operación Denegada. Se requiere clave secreta."),
								array(116,"Operación Denegada. Fondos insuficientes. Contactar con entidad emisora de su tarjeta"),
								array(117,"Operación Denegada. Clave secreta incorrecta."),
								array(118,"Operación Denegada. Tarjeta Inválida. Contactar con entidad emisora de su tarjeta."),
								array(119,"Operación Denegada. Intentos de clave secreta excedidos. Contactar con entidad emisora de su tarjeta."),
								array(121,"Operación Denegada."),
								array(126,"Operación Denegada. Clave secreta inválida."),
								array(129,"Operación Denegada. Código de seguridad invalido. Contactar con entidad emisora de su tarjeta"),
								array(180,"Operación Denegada. Tarjeta Inválida. Contactar con entidad emisora de su tarjeta."),
								array(181,"Operación Denegada. Tarjeta con restricciones de débito. Contactar con entidad emisora de su tarjeta."),
								array(182,"Operación Denegada. Tarjeta con restricciones de crédito. Contactar con entidad emisora de su tarjeta."),
								array(183,"Operación Denegada. Problemas de comunicación. Intente más tarde."),
								array(190,"Operación Denegada. Contactar con entidad emisora de su tarjeta."),
								array(191,"Operación Denegada. Contactar con entidad emisora de su tarjeta."),
								array(192,"Operación Denegada. Contactar con entidad emisora de su tarjeta."),
								array(199,"Operación Denegada."),
								array(201,"Operación Denegada. Tarjeta vencida. Contactar con entidad emisora de su tarjeta."),
								array(202,"Operación Denegada. Contactar con entidad emisora de su tarjeta"),
								array(204,"Operación Denegada. Operación no permitida para esta tarjeta. Contactar con entidad emisora de su tarjeta."),
								array(206,"Operación Denegada. Intentos de clave secreta excedidos. Contactar con la entidad emisora de su tarjeta."),
								array(207,"Operación Denegada. Contactar con entidad emisora de su tarjeta.."),
								array(208,"Operación Denegada. Contactar con entidad emisora de su tarjeta."),
								array(209,"Operación Denegada. Contactar con entidad emisora de su tarjeta"),
								array(263,"Operación Denegada. Contactar con el comercio."),
								array(264,"Operación Denegada. Entidad emisora de la tarjeta no está disponible para realizar la autenticación."),
								array(265,"Operación Denegada. Clave secreta del tarjetahabiente incorrecta. Contactar con entidad emisora de su tarjeta."),
								array(266,"Operación Denegada. Tarjeta Vencida. Contactar con entidad emisora de su tarjeta."),
								array(280,"Operación Denegada. Clave secreta errónea. Contactar con entidad emisora de su tarjeta."),
								array(290,"Operación Denegada. Contactar con entidad emisora de su tarjeta."),
								array(300,"Operación Denegada. Número de pedido del comercio duplicado. Favor no atender."),
								array(306,"Operación Denegada. Contactar con entidad emisora de su tarjeta."),
								array(401,"Operación Denegada. Contactar con el comercio."),
								array(402,"Operación Denegada."),
								array(403,"Operación Denegada. Tarjeta no autenticada."),
								array(404,"Operación Denegada. Contactar con el comercio."),
								array(405,"Operación Denegada. Contactar con el comercio."),
								array(406,"Operación Denegada. Contactar con el comercio."),
								array(407,"Operación Denegada. Contactar con el comercio."),
								array(408,"Operación Denegada. Código de seguridad no coincide. Contactar con entidad emisora de su tarjeta"),
								array(409,"Operación Denegada. Código de seguridad no procesado por la entidad emisora de la tarjeta"),
								array(410,"Operación Denegada. Código de seguridad no ingresado."),
								array(411,"Operación Denegada. Código de seguridad no procesado por la entidad emisora de la tarjeta"),
								array(412,"Operación Denegada. Código de seguridad no reconocido por la entidad emisora de la tarjeta"),
								array(413,"Operación Denegada. Contactar con entidad emisora de su tarjeta."),
								array(414,"Operación Denegada."),
								array(415,"Operación Denegada."),
								array(416,"Operación Denegada."),
								array(417,"Operación Denegada."),
								array(418,"Operación Denegada."),
								array(419,"Operación Denegada."),
								array(420,"Operación Denegada. Tarjeta no es VISA."),
								array(421,"Operación Denegada. Contactar con entidad emisora de su tarjeta."),
								array(422,"Operación Denegada. El comercio no está configurado para usar este medio de pago. Contactar con el comercio."),
								array(423,"Operación Denegada. Se canceló el proceso de pago."),
								array(424,"Operación Denegada."),
								array(666,"Operación Denegada. Problemas de comunicación. Intente más tarde."),
								array(667,"Operación Denegada. Transacción sin respuesta de Verified by Visa."),
								array(668,"Operación Denegada. Contactar con el comercio."),
								array(669,"Operación Denegada. Contactar con el comercio."),
								array(670,"Operación Denegada. Contactar con el comercio."),
								array(672,"Operación Denegada. Módulo antifraude."),
								array(673,"Operación Denegada. Contactar con el comercio."),
								array(674,"Operación Denegada. Contactar con el comercio."),
								array(675,"Inicialización de transacción"),
								array(676,"Operación Denegada. Contactar con el comercio."),
								array(677,"Operación Denegada. Contactar con el comercio."),
								array(678,"Operación Denegada. Contactar con el comercio."),
								array(682,"Operación Denegada. Operación Denegada."),
								array(683,"Operación Denegada Registro Incorrecto de E-Ticket"),
								array(684,"Operación Denegada Registro Incorrecto Antifraude"),
								array(685,"Operación Denegada Registro Incorrecto Autorizador"),
								array(904,"Operación Denegada."),
								array(909,"Operación Denegada. Problemas de comunicación. Intente más tarde."),
								array(910,"Operación Denegada."),
								array(912,"Operación Denegada. Entidad emisora de la tarjeta no disponible"),
								array(913,"Operación Denegada."),
								array(916,"Operación Denegada."),
								array(928,"Operación Denegada."),
								array(940,"Operación Denegada."),
								array(941,"Operación Denegada."),
								array(942,"Operación Denegada."),
								array(943,"Operación Denegada."),
								array(945,"Operación Denegada."),
								array(946,"Operación Denegada. Operación de anulación en proceso."),
								array(947,"Operación Denegada. Problemas de comunicación. Intente más tarde."),
								array(948,"Operación Denegada."),
								array(949,"Operación Denegada."),
								array(965,"Operación Denegada."),
								array(400,"Operación Denegada.")
						);

	public function get_errorDescription($errorId){

		foreach ( $this->errorArray as $error) {
		
			if($error[0]==$errorId)
			{
				
				$textToreturn =$error[1];
			}
		}
	

		return $textToreturn;
	}
	


   
}