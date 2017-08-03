<?php

use Classes\Auth;
use Classes\Consigment;
use Classes\ConsigmentAddress;
use Classes\ConsigmentDetails;

/**
 * Description of ApiClient
 *
 * @author dawidorzechowski
 */
class ApiClient {

    public $url = "https://www.apaczka.pl/webservice/order";
    public $wsdl = "https://www.apaczka.pl/webservice/order?wsdl";
    private $mode = array('trace' => 1, 'exceptions' => 0, 'encoding' => 'UTF-8');
    protected $client;

    public function initialize() {

        $this->client = new SoapClient($this->wsdl, $this->mode);
        $this->client->__setLocation($this->url);
    }

    function placeOrder(Consigment $Consigment) {
        $PlaceOrderRequest = array();
        $auth = new Auth();
        $PlaceOrderRequest['authorization'] = $auth->getAuthData();
        
        $PlaceOrderRequest['order'] = $Consigment->getConsigment();

        $resp = $this->Call("placeOrder", array(
            'placeOrder' => array('PlaceOrderRequest' => $PlaceOrderRequest)));

        return $resp;
    }

    function Call($action, $action_body) {
		if ($action != "placeOrder") {
			throw new Exception('Unsupported action: [' . $action . ']');
		}

		try {
			$resp = $this->client->__soapCall($action, $action_body);
			
			print_r( "[" . date('c') . "]\n" . "SoapCall: [$action]\n");
			print_r( "Request: \n" . $this->client->__getLastRequest() . "\n");
			print_r ("Response: \n" . $this->client->__getLastResponse() . "\n\n");
		} catch (Exception $ex) {
			
			print_r( "[" . date('c') . "]\n" . "SoapCall: [$action]\n");
			print_r( "Request: \n" . $this->client->__getLastRequest() . "\n");
			print_r ("Response: \n" . $this->client->__getLastResponse() . "\n\n");
			

			return false;
		}

		

		return $resp;
	}

}
