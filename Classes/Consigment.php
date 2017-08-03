<?php

namespace Classes;

use Classes\ConsigmentAddress;
use Classes\ConsigmentDetails;

class Consigment {

    protected $sender_address = array();
    protected $receiver_address = array();
    
    public $notification_on_delivered;
    public $notification_on_exception;
    public $notification_on_new;
    public $notification_on_sent;
    
    public $reference_number;
    public $content;
    public $bank_account_number;
    public $cod_amount;
    
    private static $available_pick_up = array('SELF', 'COURIER');
    public $pickup_type = 'COURIER';
    public $pickup_time_from = '08:00';
    public $pickup_time_to = '16:00';
    public $pickup_date;
    
    private static $available_options = array('POBRANIE', 'ZWROT_DOK', 'DOR_OSOBA_PRYW',
        'DOST_SOB', 'PODPIS_DOROS');
    public $options = '';
    
    private $available_service = array('UPS_K_STANDARD', 'UPS_K_EX_SAV', 'UPS_K_EX',
        'UPS_K_EXP_PLUS', 'UPS_Z_STANDARD', 'UPS_Z_EX_SAV', 'UPS_Z_EX', 'UPS_Z_EXPEDITED',
        'UPS_K_TODAY_STANDARD', 'UPS_K_TODAY_EXPRESS', 'UPS_K_TODAY_EXP_SAV', 'DPD_CLASSIC',
        'DPD_CLASSIC_FOREIGN', 'DHLSTD', 'DHL12', 'DHL09', 'DHL1722', 'KEX_EXPRESS', 'FEDEX',
        'POCZTA_POLSKA', 'POCZTA_POLSKA_E24', 'TNT', 'TNT_Z', 'TNT_Z2');
    public $service = '';
    
    public $is_domestic = TRUE;
    public $shipments = array();

    public function createNotification($param) {
        $this->$notification_on_delivered = $this->setNotification($is_receiver_email, $is_receiver_sms, $is_sender_email, $is_sender_sms);
        $this->$notification_on_exception = $this->setNotification($is_receiver_email, $is_receiver_sms, $is_sender_email, $is_sender_sms);
        $this->$notification_on_new = $this->setNotification($is_receiver_email, $is_receiver_sms, $is_sender_email, $is_sender_sms);
        $this->$notification_on_sent = $this->setNotification($is_receiver_email, $is_receiver_sms, $is_sender_email, $is_sender_sms);
    }

    function setNotification($is_receiver_email, $is_receiver_sms, $is_sender_email, $is_sender_sms) {
        $notification = array();
        $notification['is_receiver_email'] = $is_receiver_email;
        $notification['is_receiver_sms'] = $is_receiver_sms;
        $notification['is_sender_email'] = $is_sender_email;
        $notification['is_sender_sms'] = $is_sender_sms;

        return $notification;
    }

    function setCashOnDelivery($bank_account_number, $cod_amount) {

        if (strlen($bank_account_number) < 26) {
            throw new Exception('Bank account number to short.Available 26 characters');
        }

        if (!($cod_amount > 0)) {
            throw new Exception('Cash on delivery amount must be > 0');
        }

        $this->bank_account_number = $bank_account_number;
        $this->cod_amount = $cod_amount;
        $this->addOrderOption('POBRANIE');
    }

    function setPickUp($pickup_type, $pickup_time_from, $pickup_time_to, $pickupDate) {
        if (!in_array($pickup_type, self::$available_pick_up)) {
            throw new Exception("UNSUPPORTED order pickup type.Available " . self::$available_pick_up);
        }

        $this->pickup_type = $pickup_type;
        $this->pickup_time_from = $pickup_time_from;
        $this->pickup_time_to = $pickup_time_to;
        $this->pickup_date = $pickup_date;
    }

    function addShipment(ConsigmentDetails $shipment) {
        $this->shipments[] = $shipment;
    }

    function createShipment() {
        $return = array();
        $position = 0;
        $t_tmp = $this->shipments;

        if (!is_array($t_tmp)) {
            $t_tmp = array($t_tmp);
        }

        foreach ($t_tmp as $key) {
            $t_ship = array();
            $t_ship['dimension1'] = $key->dimension1;
            $t_ship['dimension2'] = $key->dimension2;
            $t_ship['dimension3'] = $key->dimension3;
            $t_ship['weight'] = $key->weight;
            $t_ship['shipmentTypeCode'] = $key->getShipmentType();
            $t_ship['position'] = $position;

            if ($key->getShipmentValue() > 0) {
                $t_ship['shipmentValue'] = $key->getShipmentValue();
            }

            $t_ship['options'] = $a->getOptions();

            $return[] = $t_ship;

            $position++;
        }

        if ($position === 1) {
            return array('Shipment' => $t_ship);
        }

        return array('Shipment' => $return);
    }

    function getConsigment() {
        $consigment = array();

        if (!($this->bank_account_number == "" || $this->cod_amount == "")) {
            $consigment['bank_account_number'] = $this->bank_account_number;
            $consigment['cod_amount'] = $this->cod_amount;
        }

        $consigment['notification_on_delivered'] = $this->notification_on_delivered;
        $consigment['notification_on_exception'] = $this->notification_on_exception;
        $consigment['notification_on_new'] = $this->notification_on_new;
        $consigment['notification_on_sent'] = $this->notification_on_sent;

        $consigment['pickup_type'] = $this->pickup_type;

        if ($this->pickup_time_from != '' and $this->pickup_time_to != '') {
            $consigment['pickup_time_from'] = $this->pickup_time_from;
            $consigment['pickup_time_to'] = $this->pickup_time_to;
            $consigment['pickup_date'] = $this->pickup_date;
        }

        $consigment['options'] = $this->options;

        $consigment['service'] = $this->service;
        $consigment['reference_number'] = $this->reference_number;
        $consigment['is_domestic'] = $this->is_domestic;
        $consigment['content'] = $this->content;

        $consigment['receiver'] = $this->sender_address;
        $consigment['sender'] = $this->receiver_address;

        $consigment['shipments'] = $this->createShipment();

        return $consigment;
    }

}
