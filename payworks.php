<?php

$datosTerminal = array('name' => '',
                       'clientId' => '',
                       'password' => '',
                       'transType' => '',
                       'mode' => 'R',
                       'orderId' => '') ; /*data*/
$payworks = new PayWorks($datosTerminal);
$ccData  = array('Number' =>  '', 'Expires' => '0317', 'Cvv2Indicator' => '1', 'Cvv2Val' => '213', 'Total' => '100');
$payworks->makePayment($ccData);


class PayWorks
{

	private $_tpvData = array();

	function __construct($tpvData)
	{
	
		if(is_array($tpvData))
		{

			$this->_tpvData['Name'] = $tpvData['name'];
			$this->_tpvData['ClientId'] = $tpvData['clientId'];
			$this->_tpvData['Password'] = $tpvData['password'];
			$this->_tpvData['TransType'] = $tpvData['transType'];
			$this->_tpvData['Mode'] = $tpvData['mode'];
			$this->_tpvData['OrderId'] = $tpvData['orderId'];
			if(!empty($tpvData['responsepath'])) $this->_tpvData['responsePath'] = $tpvData['responsepath'];
		} 
	}

	public function requestData($postData)
	{
		foreach($postData as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
		$postData= substr($fields_string,0,strlen($fields_string) -1 );
		
		$ch = curl_init();
		/*curl_setopt($ch, CURLOPT_URL, '');*/
		curl_setopt($ch,CURLOPT_URL, 'https://eps.banorte.com/recibo');
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($ch, CURLOPT_POST, count($postData));
 		curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);	
 		curl_setopt($ch, CURLOPT_HEADER,1);  // DO NOT RETURN HTTP HEADERS
 		curl_setopt($ch, CURLOPT_RETURNTRANSFER  ,1);  // RETURN THE CONTENTS OF THE CALL
		$respuesta = curl_exec($ch);
		return $respuesta;
	}

	public function makePayment($ccData)
	{
		/*
			$array {
	
				'Number' => ''
				'Expires' => ''
				'Cvv2Indicator' => ''
				'Cvv2Val' => ''
				'Total' => ''
			}
		*/
		$promociones = array(
				'NumberOfPayments' => '06',
				'PlanType' => '03'
			);
		$data = array_merge($this->_tpvData,$ccData);
		$datos = array_merge($data,$promociones);
		print_r($datos);
		echo $this->requestData($datos);
	}
	

}


?>
