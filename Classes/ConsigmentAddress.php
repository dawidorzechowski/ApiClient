<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Classes;

use Classes\Consigment;

/**
 * Description of Consigment_Address
 *
 * @author dawidorzechowski
 */
class ConsigmentAddress extends Consigment {

    private static $address_type = array('SENDER', 'RECEIVER');
    
    public $name;
    public $contact_person;
    public $address_line1;
    public $address_line2;
    public $city;
    public $postal_code;
    public $country_id = 0;
    public $state_code;
    public $email;
    public $phone;

    function createAddress($name = '', $contact_person = '', $address_line1 = '', $address_line2 = '', $city = '', $postal_code = '', $country_id = '', $state_code = '', $email = '', $phone = '') {

        $address = array();
        $address['name'] = substr($name, 0, 50);
        $address['contanct_person'] = $contact_person;

        $address['address_line1'] = $address_line1;
        $address['$address_line2'] = $address_line2;
        $address['city'] = $city;

        $address['postal_code'] = $postal_code;
        $address['countryId'] = $country_id;
        if ($state_code != '') {
            $address['state_code'] = $state_code;
        }

        $address['email'] = $email;
        $address['phone'] = $phone;

        return $address;
    }

    function setAddress($address_type, $name = '', $contact_person = '', $address_line1 = '', $address_line2 = '', $city = '', $postal_code = '', $country_id = '', $state_code = '', $email = '', $phone = '') {
       
        if(!in_array($address_type, self::$address_type)){
            throw new Exception("Unsupported address type. Available:".self::$address_type);
        }
        if($address_type == 'SENDER'){
             $this->sender_address = $this->createAddress($name, $contact_person, $address_line1, $address_line2, $city, $postal_code, $country_id, $state_code, $email, $phone);
        }
        if($address_type == 'RECEIVER'){
            $this->receiver_address = $this->createAddress($name, $contact_person, $address_line1, $address_line2, $city, $postal_code, $country_id, $state_code, $email, $phone);
        }
        
    }


}
