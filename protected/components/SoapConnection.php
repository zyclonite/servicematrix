<?php

class SoapConnection {

    /**
     * @var string From
     * @soap
     */
    public $From;

    /**
     * @var string To
     * @soap
     */
    public $To;

    /**
     * @var int Percent
     * @soap
     */
    public $Percent;

    /**
     * @var string Type
     * @soap
     */
    public $Type;

    public function __construct($from = null, $to = null, $percent = null, $type = null) {
        $this->From = $from;
        $this->To = $to;
        $this->Percent = $percent;
        $this->Type = $type;
    }

    public function getAll() {
        $soapconnections = array();
        $connections = Connections::model()->with(array('fromservice', 'toservice', 'connectiontype'))->findAll();
        foreach ($connections as $connection) {
            $soapconnections[] = new SoapConnection($connection->fromservice->servicename, $connection->toservice->servicename, $connection->percent, $connection->connectiontype->name);
        }
        return $soapconnections;
    }

    public function searchAll($from, $to, $percent, $type) {
        $soapconnections = array();
        if($percent[0] == '<' || $percent[0] == '>') {
            $symbol = $percent[0];
            $percent = (int)substr($percent, 1);
        }else{
            $symbol = '=';
            $percent = (int)$percent;
        }
        $connections = Connections::model()->with(array('fromservice', 'toservice', 'connectiontype'))->findAll('`fromservice`.`servicename` LIKE :s1 AND `toservice`.`servicename` LIKE :s2 AND `connectiontype`.`name` LIKE :s3 AND `percent` '.$symbol.' :s4', array(':s1'=>$from,':s2'=>$to,':s3'=>$type,':s4'=>$percent));
        foreach ($connections as $connection) {
            $soapconnections[] = new SoapConnection($connection->fromservice->servicename, $connection->toservice->servicename, $connection->percent, $connection->connectiontype->name);
        }
        return $soapconnections;
    }

}
