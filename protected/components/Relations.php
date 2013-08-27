<?php

/**
 * Description of Relations
 *
 * @author lprettenthaler
 */
class Relations extends CComponent {

    private $marked = array();
    private $serviceid = 0;
    private $dim = 0;

	public function __construct($serviceid)
	{
    	$this->setService($serviceid);
	}
	
    public function setService($service)
    {
        $this->serviceid = (int)$service;
    }

    public function getService()
    {
        return $this->serviceid;
    }

    public function getMarked()
    {
        return array($this->dim, $this->marked);
    }

    private function pushMarked($id, $key, $value)
    {
        if($id > $this->dim) {
                $this->marked[$id] = array();
                $this->dim++;
        }
        if(($value > 0) && (!$this->checkKeyMarked($id-1, $key))) {
                if(array_key_exists($key, $this->marked[$id])) {
                        $this->marked[$id][$key] += $value;
                        if($this->marked[$id][$key] > 100)
                                $this->marked[$id][$key] = 100;
                }else{
                        $this->marked[$id][$key] = $value;
                }
        }
    }

    public function getPercent($id, $key)
    {
        if(!is_array($this->marked[$id])) return false;
        return $this->marked[$id][$key];
    }

    public function getColor($id, $key)
    {
                        $percent = $this->getPercent($id, $key);
                        $green = hexdec('00ff00');
                        $yellow = hexdec('ffff00');
                        if($percent < 50){
                                $color = '#'.$this->dec2hex($green+(round($percent*5.10)*65536));
                        }else{
                                $color = '#'.$this->dec2hex($yellow-(round(($percent-50)*5.10)*256));
                        }
                        return $color;
    }

    private function dec2hex($number)
    {
        $i = 0;
        $hex = array();

        while($i < 6) {
            if($number == 0) {
                  array_push($hex, '0');
                } else {
                array_push($hex, strtoupper(dechex(bcmod($number, '16'))));
                  $number = bcdiv($number, '16', 0);
                }
          $i++;
        }
        krsort($hex);
        return implode($hex);
    }
                
    private function countMarked($id)
    {
        return count($this->marked[$id]);
    }

    public function checkKeyMarked($id, $value, $recursive = true)
    {
        $return = false;
        if($recursive) {
                for($i = $id; $i > 0; $i--) {
                        if((count($this->marked[$i]) > 0) && array_key_exists($value, $this->marked[$i])) {
                                return true;
                        }
                }
        }else{
                if((count($this->marked[$i]) > 0) && array_key_exists($value, $this->marked[$i])) {
                        return true;
                }
        }
        return $return;
    }

    public function getRelations($limit=3)
    {
    	if(!isset($limit) || $limit < 0 || $limit > 5)
    		$limit = 3;
        if($this->serviceid > 0) {
                $dim = 1;
                $service = Services::model()->with('depconn')->findByPk($this->serviceid);
                //$connections = Connections::model()->with(array('fromservice','toservice')->findAllByAttributes(array('to'=>$this->serviceid));
                $connections = $service->depconn;
                while($dim < $limit) {
                		$services = array();
                        foreach($connections as $connection) {
                        		$services[$connection->fromservice->id] = $connection->fromservice;
                        		if($dim <= 1) {
                                	$this->pushMarked($dim, $connection->fromservice->id, (int)$connection->percent);
                                }else{
                                	$percent = ((int)$connection->percent) * ($this->getPercent($dim-1, $connection->toservice->id)) / 100;
                                	$this->pushMarked($dim, $connection->fromservice->id, (int)ceil($percent));
                                }
                        }
                        if($this->countMarked($dim) > 0) {
                                $connections = array();
								foreach($services as $service) {
									$connections = array_merge($connections, $service->depconn);
								}
                                $dim++;
                        }else{
                                $dim = $limit;
                        }
                }
        }
    }
}
