<?php

class SoapService {

    /**
     * @var string Name
     * @soap
     */
    public $Name;

    /**
     * @var string Type
     * @soap
     */
    public $Type;

    public function __construct($name = null, $type = null) {
        $this->Name = $name;
        $this->Type = $type;
    }

    public function getAll() {
        $soapservices = array();
        $services = Services::model()->with('servicetype')->findAll();
        foreach ($services as $service) {
            $soapservices[] = new SoapService($service->servicename, $service->servicetype->name);
        }
        return $soapservices;
    }

    public function searchAll($name, $type) {
        $soapservices = array();
        $services = Services::model()->with(array('servicetype'))->findAll('`servicename` LIKE :s1 AND `servicetype`.`name` LIKE :s2', array(':s1' => $name, ':s2'=> $type));
        foreach ($services as $service) {
            $soapservices[] = new SoapService($service->servicename, $service->servicetype->name);
        }
        return $soapservices;
    }

}
