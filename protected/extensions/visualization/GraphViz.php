<?php

/**
 * Description of GraphViz
 *
 * @author lprettenthaler
 */
class GraphViz extends CApplicationComponent {

    private $dotCommand = 'dot';
    private $dotConfig = array();
    private $neatoCommand = 'neato';
    private $neatoConfig = array();
    private $fdpCommand = 'fdp';
    private $fdpConfig = array();
    private $sfdpCommand = 'sfdp';
    private $sfdpConfig = array();
    private $graph;
    private $tmpdir = '/var/tmp';
    private $usedCommand = 'dot';
    private $name = '';
    
    public function init() {
        parent::init();
        $this->setName($this->name);
        $this->tmpdir = Yii::app()->getRuntimePath();
    }
    
    public function setUsedCommand ($value) {
        if(preg_match('(dot|neato|fdp|sfdp)',$value)) {
        	$this->usedCommand = $value;
	        switch($this->usedCommand) {
	            case 'dot': $attributes = $this->dotConfig;
	                break;
	            case 'neato': $attributes = $this->neatoConfig;
	                break;
	            case 'fdp': $attributes = $this->fdpConfig;
	                break;
	            case 'sfdp': $attributes = $this->sfdpConfig;
	                break;
	            default:
	                $attributes = array();
	        }
	        $this->setMethod($this->usedCommand);
	        $this->setAttributes($attributes);
        }
    }
    
    public function getUsedCommand () {
        return $this->usedCommand;
    }
    
    public function setTmpdir ($value) {
        $this->tmpdir = $value;
    }
    
    public function getTmpdir () {
        return $this->tmpdir;
    }
    
    public function setDotCommand ($value) {
        $this->dotCommand = $value;
    }
    
    public function getDotCommand () {
        return $this->dotCommand;
    }
    
    public function setNeatoCommand ($value) {
        $this->neatoCommand = $value;
    }
    
    public function getNeatoCommand () {
        return $this->neatoCommand;
    }
    
    public function setFdpCommand ($value) {
        $this->fdpCommand = $value;
    }
    
    public function getFdpCommand () {
        return $this->fdpCommand;
    }
    
    public function setSfdpCommand ($value) {
        $this->sfdpCommand = $value;
    }
    
    public function getSfdpCommand () {
        return $this->sfdpCommand;
    }
    
    public function setDotConfig ($value) {
        if (is_array($value)) {
            $this->dotConfig = $value;
        }
    }
    
    public function setNeatoConfig ($value) {
        if (is_array($value)) {
            $this->neatoConfig = $value;
        }
    }
    
    public function setFdpConfig ($value) {
        if (is_array($value)) {
            $this->fdpConfig = $value;
        }
    }
    
    public function setSfdpConfig ($value) {
        if (is_array($value)) {
            $this->sfdpConfig = $value;
        }
    }
    
    public function image($format = 'svg')
    {
        if (($data = $this->fetch($format)) == true) {
            $sendContentLengthHeader = TRUE;

            switch ($format) {
                case 'gif':
                case 'png':
                case 'wbmp': {
                    header('Content-Type: image/' . $format);
                }
                break;

                case 'jpg': {
                    header('Content-Type: image/jpeg');
                }
                break;

                case 'pdf': {
                    header('Content-Type: application/pdf');
                }
                break;

                case 'svg': {
                    header('Content-Type: image/svg+xml');
                }
                break;

                case 'vrml': {
                    header('Content-Type: model/vrml');
                }
                break;

                default: {
                    $sendContentLengthHeader = FALSE;
                }
            }

            if ($sendContentLengthHeader) {
                header('Content-Length: ' . strlen($data));
            }

            echo $data;
        }
    }

    private function fetch($format = 'svg')
    {
        if (($file = $this->saveParsedGraph()) == true) {
            if($format == 'cmapx')
                $format = 'png';
            $outputfile = $file . '.' . $format;
            $command  = $this->getMethod();
            $command .= ' -T' . escapeshellarg($format) . ' -o'  . escapeshellarg($outputfile) . ' ' . escapeshellarg($file);
            //$command .= ' -v > '.$file.'.log';
           
            exec($command);
            //Yii::log($command, CLogger::LEVEL_INFO);
            unlink($file);
   
            $fp = fopen($outputfile, 'rb');
   
            if ($fp) {
                $data = fread($fp, filesize($outputfile));
                fclose($fp);
                unlink($outputfile);
            }
   
            return $data;
        }
   
        return FALSE;
    }

    public function cmapx()
    {
        if (($file = $this->saveParsedGraph()) == true) {
            $outputfile = $file . '.png';
            $outputfile2 = $file . '.map';
            $command  = $this->getMethod();
            $command .= ' -T' . escapeshellarg('cmapx') . ' -o'  . escapeshellarg($outputfile2) . ' -T' . escapeshellarg('png') . ' -o'  . escapeshellarg($outputfile) . ' '. escapeshellarg($file);
            //$command .= ' -v > '.$file.'.log';

            exec($command);
            //Yii::log($command, CLogger::LEVEL_INFO);
            unlink($file);

            $data = array();    
            $fp = fopen($outputfile, 'rb');
            if ($fp) {
                $data['img'] = fread($fp, filesize($outputfile));
                fclose($fp);
                unlink($outputfile);
            }
            $fp = fopen($outputfile2, 'rb');
            if ($fp) {
                $data['map'] = fread($fp, filesize($outputfile2));
                fclose($fp);
                unlink($outputfile2);
            }
   
            return $data;
        }
   
        return FALSE;
    }

    public function addCluster($id, $title, $attributes = array())
    {
        $this->graph['clusters'][$id]['title'] = $title;
        $this->graph['clusters'][$id]['attributes'] = $attributes;
    }

    public function addNode($name, $attributes = array(), $group = 'default')
    {
        $this->graph['nodes'][$group][$name] = $attributes;
    }

    public function removeNode($name, $group = 'default')
    {
        if (isset($this->graph['nodes'][$group][$name])) {
            unset($this->graph['nodes'][$group][$name]);
        }
    }

    public function addEdge($edge, $attributes = array())
    {
        if (is_array($edge)) {
            $from = key($edge);
            $to   = $edge[$from];
            $id   = $from . '_' . $to;

            if (!isset($this->graph['edges'][$id])) {
                $this->graph['edges'][$id] = $edge;
            } else {
                $this->graph['edges'][$id] = array_merge(
                  $this->graph['edges'][$id],
                  $edge
                );
            }

            if (is_array($attributes)) {
                if (!isset($this->graph['edgeAttributes'][$id])) {
                    $this->graph['edgeAttributes'][$id] = $attributes;
                } else {
                    $this->graph['edgeAttributes'][$id] = array_merge(
                      $this->graph['edgeAttributes'][$id],
                      $attributes
                    );
                }
            }
        }
    }

    public function removeEdge($edge)
    {
        if (is_array($edge)) {
              $from = key($edge);
              $to   = $edge[$from];
              $id   = $from . '_' . $to;

            if (isset($this->graph['edges'][$id])) {
                unset($this->graph['edges'][$id]);
            }

            if (isset($this->graph['edgeAttributes'][$id])) {
                unset($this->graph['edgeAttributes'][$id]);
            }
        }
    }

    public function addAttributes($attributes)
    {
        if (is_array($attributes)) {
            $this->graph['attributes'] = array_merge(
              $this->graph['attributes'],
              $attributes
            );
        }
    }

    private function setAttributes($attributes)
    {
        if (is_array($attributes)) {
            $this->graph['attributes'] = $attributes;
        }
    }

    private function setName($name)
    {
        $this->graph['name'] = $name;
    }
    
    public function getName()
    {
        return $this->graph['name'];
    }
    
    private function setMethod($command)
    {
        switch($command)
        {
            case 'dot': $this->graph['method'] = 'dot';
                break;
            case 'neato': $this->graph['method'] = 'neato';
                break;
            case 'fdp': $this->graph['method'] = 'fdp';
                break;
            case 'sfdp': $this->graph['method'] = 'sfdp';
                break;
            default: $this->graph['method'] = 'dot';
        }
    }

    public function getMethod()
    {
        switch($this->graph['method'])
        {
            case 'dot': $retval = $this->dotCommand;
                break;
            case 'neato': $retval = $this->neatoCommand;
                break;
            case 'fdp': $retval = $this->fdpCommand;
                break;
            case 'sfdp': $retval = $this->sfdpCommand;
                break;
            default: $retval = $this->dotCommand;
        }
        return $retval;
    }

    public function load($file)
    {
        if (($serializedGraph = implode('', file($file))) === true) {
            $this->graph = unserialize($serializedGraph);
        }
    }

    public function save($file = '')
    {
        $serializedGraph = serialize($this->graph);

        if (empty($file)) {
            $file = tempnam($this->tmpdir, 'graph_');
        }

        if (($fp = fopen($file, 'w')) === true) {
            fputs($fp, $serializedGraph);
            fclose($fp);

            return $file;
        }

        return FALSE;
    }

    public function parse()
    {
        if (isset($this->graph['name']) && is_string($this->graph['name']) && (strlen($this->graph['name']) > 0)) {
            $parsedGraph = "digraph " . $this->graph['name'] . " {\n";
        } else {
            $parsedGraph = "digraph G {\n";
        }

        if (isset($this->graph['attributes'])) {
            foreach ($this->graph['attributes'] as $key => $value) {
            	if(strtolower($key) == 'label')
            		$value = 'Service Matrix - '.$value;
                $attributeList[] = $key . '="' . $value . '"';
            }

            if (!empty($attributeList)) {
                $parsedGraph .= 'graph [ '.implode(',', $attributeList) . " ];\n";
            }
        }

        if (isset($this->graph['nodes'])) {
            foreach($this->graph['nodes'] as $group => $nodes) {
                if ($group != 'default') {
                    $parsedGraph .= sprintf(
                      "subgraph \"cluster_%s\" {\nlabel=\"%s\";\n",

                      $group,
                      isset($this->graph['clusters'][$group]) ? $this->graph['clusters'][$group]['title'] : ''
                    );

                    if (isset($this->graph['clusters'][$group]['attributes'])) {
                        unset($attributeList);

                        foreach ($this->graph['clusters'][$group]['attributes'] as $key => $value) {
                            $attributeList[] = $key . '="' . $value . '"';
                        }

                        if (!empty($attributeList)) {
                            $parsedGraph .= implode(',', $attributeList) . ";\n";
                        }
                    }
                }

                foreach($nodes as $node => $attributes) {
                    unset($attributeList);

                    foreach($attributes as $key => $value) {
                        $attributeList[] = $key . '="' . $value . '"';
                    }

                    if (!empty($attributeList)) {
                        $parsedGraph .= sprintf(
                          "\"%s\" [ %s ];\n",
                          addslashes(stripslashes($node)),
                          implode(',', $attributeList)
                        );
                    }
                }

                if ($group != 'default') {
                  $parsedGraph .= "}\n";
                }
            }
        }

        if (isset($this->graph['edges'])) {
            foreach($this->graph['edges'] as $label => $node) {
                unset($attributeList);

                $from = key($node);
                $to   = $node[$from];

                foreach($this->graph['edgeAttributes'][$label] as $key => $value) {
                    $attributeList[] = $key . '="' . $value . '"';
                }

                $fromarray = explode(':', addslashes(stripslashes($from)), 2);
                if(array_key_exists(1, $fromarray) && strlen($fromarray[1]) > 0) {
                        $sfrom = '"'.$fromarray[0].'":'.$fromarray[1];
                }else{
                        $sfrom = '"'.$fromarray[0].'"';
                }
                $toarray = explode(':', addslashes(stripslashes($to)), 2);
                if(array_key_exists(1, $toarray) && strlen($toarray[1]) > 0) {
                        $sto = '"'.$toarray[0].'":'.$toarray[1];
                }else{
                        $sto = '"'.$toarray[0].'"';
                }
                $parsedGraph .= sprintf(
                  '%s -> %s',
                  $sfrom,
                  $sto
                );
               
                if (!empty($attributeList)) {
                    $parsedGraph .= sprintf(
                      ' [ %s ]',
                      implode(',', $attributeList)
                    );
                }

                $parsedGraph .= ";\n";
            }
        }

        return $parsedGraph . "}\n";
    }

    public function parseGraphMl()
    {
        if (isset($this->graph['name']) && is_string($this->graph['name']) && (strlen($this->graph['name']) > 0)) {
            $gname = $this->graph['name'];
        } else {
            $gname = 'G';
        }

		$parsedGraph = '<?xml version="1.0" encoding="utf-8"?>'
					.'<graphml xmlns="http://graphml.graphdrawing.org/xmlns/graphml" xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:y="http://www.yworks.com/xml/graphml" xsi:schemaLocation="http://graphml.graphdrawing.org/xmlns/graphml http://www.yworks.com/xml/schema/graphml/1.0/ygraphml.xsd">'
					.'<key for="node" id="d0" yfiles.type="nodegraphics"/>'
					.'<key attr.name="description" attr.type="string" for="node" id="d1"/>'
					.'<key for="edge" id="d2" yfiles.type="edgegraphics"/>'
					.'<key attr.name="description" attr.type="string" for="edge" id="d3"/>'
					.'<key for="graphml" id="d4" yfiles.type="resources"/>'
					.'<graph edgedefault="directed" id="'.htmlentities($gname, ENT_QUOTES, 'UTF-8').'" parse.edges="'.count($this->graph['edges']).'" parse.nodes="'.count($this->graph['nodes']).'" parse.order="free">';

        if (isset($this->graph['nodes'])) {
            foreach($this->graph['nodes'] as $group => $nodes) {
                foreach($nodes as $node => $attributes) {
						$parsedGraph .= '<node id="'.htmlentities(stripslashes($node), ENT_QUOTES, 'UTF-8').'">'
									.'<data key="d0">'
									.'<y:ShapeNode>'
									.'<y:Geometry height="30.0" width="30.0" x="0.0" y="0.0"/>'
									.'<y:Fill color="'.htmlentities(stripslashes($attributes['color']), ENT_QUOTES, 'UTF-8').'" transparent="false"/>'
									.'<y:BorderStyle color="#000000" type="line" width="1.0"/>'
									.'<y:NodeLabel alignment="center" autoSizePolicy="content" fontFamily="Dialog" fontSize="12" fontStyle="plain" hasBackgroundColor="false" hasLineColor="false" modelName="internal" modelPosition="c" textColor="'.htmlentities(stripslashes($attributes['fontcolor']), ENT_QUOTES, 'UTF-8').'" visible="true">'.htmlentities(stripslashes($attributes['label']), ENT_QUOTES, 'UTF-8').'</y:NodeLabel>'
									.'<y:Shape type="rectangle"/>'
									.'</y:ShapeNode>'
									.'</data>'
									.'<data key="d1"/>'
									.'</node>';
                }
            }
        }

        if (isset($this->graph['edges'])) {
			$i=0;
            foreach($this->graph['edges'] as $label => $node) {
                unset($attributeList);

                $from = key($node);
                $to   = $node[$from];

                foreach($this->graph['edgeAttributes'][$label] as $key => $value) {
                    $attributeList[$key] = $value;
                }

                $fromarray = explode(':', stripslashes($from), 2);
                if(array_key_exists(1, $fromarray) && strlen($fromarray[1]) > 0) {
                        $sfrom = $fromarray[0].':'.$fromarray[1];
                }else{
                        $sfrom = $fromarray[0];
                }
                $toarray = explode(':', stripslashes($to), 2);
                if(array_key_exists(1, $toarray) && strlen($toarray[1]) > 0) {
                        $sto = $toarray[0].':'.$toarray[1];
                }else{
                        $sto = $toarray[0];
                }
                $parsedGraph .= '<edge id="e'.$i++.'" source="'.htmlentities($sfrom, ENT_QUOTES, 'UTF-8').'" target="'.htmlentities($sto, ENT_QUOTES, 'UTF-8').'">'
							.'<data key="d2">'
							.'<y:PolyLineEdge>'
							.'<y:LineStyle color="'.htmlentities(stripslashes($attributeList['color']), ENT_QUOTES, 'UTF-8').'" type="line" width="1.0"/>'
							.'<y:Arrows source="none" target="standard"/>'
							.'<y:EdgeLabel alignment="center" distance="2.0" fontFamily="Dialog" fontSize="12" fontStyle="plain" hasBackgroundColor="false" hasLineColor="false" modelName="six_pos" modelPosition="tail" preferredPlacement="anywhere" ratio="0.5" textColor="'.htmlentities(stripslashes($attributeList['fontcolor']), ENT_QUOTES, 'UTF-8').'" visible="true">'.htmlentities(stripslashes($attributeList['label']), ENT_QUOTES, 'UTF-8').'</y:EdgeLabel>'
							.'<y:BendStyle smoothed="false"/>'
							.'</y:PolyLineEdge>'
							.'</data>'
							.'<data key="d3"/>'
							.'</edge>';
               
            }
        }

        return $parsedGraph . '</graph>'
				.'<data key="d4">'
				.'<y:Resources/>'
				.'</data>'
				.'</graphml>';
    }

    private function saveParsedGraph($file = '')
    {
        $parsedGraph = $this->parse();

        if (!empty($parsedGraph)) {
            if (empty($file)) {
                $file = tempnam($this->tmpdir, 'graph_');
            }
            if (($fp = fopen($file, 'w')) == true) {
                fputs($fp, $parsedGraph);
                fclose($fp);

                return $file;
            }
        }

        return FALSE;
    }
}
