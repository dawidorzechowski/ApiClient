<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Classes;

use Classes\Consigment;

/**
 * Description of ConsigmentDetails
 *
 * @author dawidorzechowski
 */
class ConsigmentDetails extends Consigment {

    public $dimension1;
    public $dimension2;
    public $dimension3;
    public $weight;
    
    private static $available_shipment_type = array('LIST', 'PACZ', 'PALETA');
    private $shipment_type;
    private $position = 0;
    
    private $shipmentValue;
    
    private static $available_options = array('UBEZP', 'PRZES_NIETYP', 'DUZA_PACZKA');
    private $options = '';

    public function createConsigmentDetails($shipment_type, $dimension1, $dimension2, $dimension3, $weight) {
        if ($shipment_type == 'LIST') {
            $this->createShipment($shipment_type, 0, 0, 0, 0);
        } else {
            if ($dimension1 != '' && $dimension2 != '' && $dimension3 != '' && $weight != '' && $shipment_type != '') {
                $this->createShipment($shipment_type, $dimension1, $dimension2, $dimension3, $weight);
            }
        }
    }

    public function createShipment($shipment_type, $dimension1, $dimension2, $dimension3, $weight) {
        if (!in_array($shipment_type, self::$available_shipment_type)) {
            throw new Exception("Unsupported shipment type. Available " . self::$available_shipment_type);
        }
        $this->setShipmentType($shipment_type);

        $this->dimension1 = $dimension1;
        $this->dimension2 = $dimension2;
        $this->dimension3 = $dimension3;

        $this->weight = $weight;
    }

    function addOption($option) {
        if (!in_array($option, self::$available_options)) {
            throw new Exception('UNSUPPORTED  option.Avaialble ' . self::$available_options);
        }

        $this->options[] = $option;
    }

    function getShipmentType() {
        return $this->shipment_type;
    }

    function getPosition() {
        return $this->position;
    }

    function getShipmentValue() {
        return $this->shipmentValue;
    }

    function getOptions() {
        return $this->options;
    }

    function setShipmentType($shipment_type) {
        
        if (!in_array($shipment_type, self::$available_shipment_type)) {
            throw new Exception("Unsupported shipment type. Available " . self::$available_shipment_type);
        }
        $this->shipment_type = $shipment_type;
    }

    function setPosition($position) {
        $this->position = $position;
    }

    function setShipmentValue($shipmentValue) {
        if (!$shipmentValue > 0) {
            throw new Exception('ShipmentValue must be greater then 0');
        }

        $this->shipmentValue = $shipmentValue;
        $this->addOption('UBEZP');
       
    }


}
