<?php

class GraphController extends Controller {

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('index','offline','direct','service'),
				'users'=>array('@'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

    public function actionOffline($id) {
        $this->prePaintGraph('offline', $id);
    }

    public function actionDirect($id) {
        $this->prePaintGraph('direct', $id);
    }

    public function actionIndex() {
        $this->prePaintGraph('full');
    }

    private function prePaintGraph($type, $id = 0) {
        $graph = $this->paintGraph($type, isset($_GET['method']) ? $_GET['method'] : 'dot', $id, isset($_GET['level']) ? $_GET['level'] : '0', isset($_GET['group']) ? $_GET['group'] : 0);
        if (isset($_GET['output'])) {
	    switch($_GET['output']) {
		case 'dot':
                        $this->renderPartial('dot', array(
                                'graph' => $graph,
                        ));
                        break;
                case 'graphml':
                        $this->renderPartial('graphml', array(
                                'graph' => $graph,
                        ));
                        break;
                case 'image':
                default:
                        $this->renderPartial('image', array(
                                'graph' => $graph,
                        ));
		}
        } else {
            $groups = Groups::model()->findAll();
            $this->registerClientScript();
            $this->render('index', array(
                'graph' => $graph,
                'groups' => $groups,
                'id' => $id,
            ));
        }
    }

    private function registerClientScript() {
        $script = 'jQuery("area").click(function() {
                    $("#nodeaction").attr("nodeid", $(this).attr("href"));
                    $("#nodeaction").text("...loading...");
                    $("#nodeaction").dialog("open"); 
                    $.getJSON("'.$this->createUrl('api/services',array('id'=>'')).'"+$(this).attr("href").substring(1), function(data) {
                      $("#nodeaction").html("Element: "+data["servicename"]);
                    });
                    return false;});';
        Yii::app()->clientscript->registerScript('handler', $script, CClientScript::POS_READY);
    }

    private function paintGraph($graphtype = 'full', $method = 'dot', $serviceid = 0, $getlevel = '0', $group = 0) {
        $white = '#FFFFFF';
        $grey = '#AAAAAA';
        $red = '#FF0000';
        $inlevel = '';
        if (strlen($getlevel) > 0) {
            $levels = explode(':', $getlevel);
            foreach ($levels as $level) {
                if ($level >= 0) {
                    $inlevel .= (int) $level . ',';
                }
            }
            $inlevel = substr($inlevel, 0, -1);
        }

        $graph = Yii::app()->graphviz;
        $graph->setUsedCommand($method);
        $servicesingroup = '';
        $connectionsingroup = '';
        if ($group > 0) {
            $groups = Groups::model()->with('services')->findByPk($group);
            if (is_object($groups)) {
                $ingroup = '';
                foreach ($groups->services as $service) {
                    $ingroup .= $service->id . ',';
                }
                $ingroup = substr($ingroup, 0, -1);
                $servicesingroup = '`t`.`id` IN (' . $ingroup . ')';
                $connectionsingroup = '`t`.`from` IN (' . $ingroup . ') AND `t`.`to` IN (' . $ingroup . ')';
            }
        }
        if ($inlevel != '') {
            $servicetypes = Servicetypes::model()->findAll('`level` IN ('.$inlevel.')');
        } else {
            $servicetypes = Servicetypes::model()->findAll();
        }
        foreach ($servicetypes as $servicetype) {
            $graph->addCluster($servicetype->name, $servicetype->name, array(
                'color' => $white,
                    )
            );
        }
        $services = array();
        switch ($graphtype) {
            case 'direct':
                $service = Services::model()->with('servicetype')->findByPk($serviceid);
                $services[] = $service;
                $services = array_merge($services, $service->depending, $service->services);
                break;
            case 'offline':
                $relation = new Relations($serviceid);
                $relation->getRelations(4);
                if ($inlevel != '') {
                    $services = Services::model()->with(array(
                                'servicetype' => array(
                                    'condition' => '`servicetype`.`level` IN (' . $inlevel . ')'
                                )
                            ))->findAll($servicesingroup);
                } else {
                    $services = Services::model()->with('servicetype')->findAll($servicesingroup);
                }
                break;
            default:
                if ($inlevel != '') {
                    $services = Services::model()->with(array(
                                'servicetype' => array(
                                    'condition' => '`servicetype`.`level` IN (' . $inlevel . ')'
                                )
                            ))->findAll($servicesingroup);
                } else {
                    $services = Services::model()->with('servicetype')->findAll($servicesingroup);
                }
        }
        foreach ($services as $service) {
            if ($graphtype == 'offline') {
                if ($service->id == $serviceid) {
                    $label = $service->servicename . '\nOFFLINE';
                    $color = $red . '99';
                } elseif ($relation->checkKeyMarked(3, $service->id)) {
                    $label = $service->servicename . '\nIMPACT ' . $relation->getPercent(3, $service->id) . '%';
                    $color = $relation->getColor(3, $service->id) . '99';
                    if ($relation->checkKeyMarked(2, $service->id)) {
                        $label = $service->servicename . '\nIMPACT ' . $relation->getPercent(2, $service->id) . '%';
                        $color = $relation->getColor(2, $service->id) . '99';
                        if ($relation->checkKeyMarked(1, $service->id)) {
                            $label = $service->servicename . '\nIMPACT ' . $relation->getPercent(1, $service->id) . '%';
                            $color = $relation->getColor(1, $service->id) . '99';
                        }
                    }
                } else {
                    $label = $service->servicename;
                    $color = $grey . '99';
                }
            } else {
                $label = $service->servicename;
                $color = '#' . $service->servicetype->color . '99';
            }
            $graph->addNode(
                    $service->servicename, array(
                'label' => $label,
                'labelloc' => 't',
                'fontname' => 'Lucida-Sans',
                'fontsize' => '8',
                'fontcolor' => '#000000',
                'image' => Yii::app()->params['nodeImages'] . $service->servicetype->image,
                'color' => $color,
                'shape' => 'ellipse',
                'fixedsize' => 'true',
                'width' => '0.40',
                'height' => '0.40',
                'style' => 'dashed,bold',
                'margin' => '0.01,0.01',
                'URL' => '#' . $service->id,
                    ), ($service->servicetype->name != "") ? $service->servicetype->name : false
            );
        }
        switch ($graphtype) {
            case 'direct':
                $connections = Connections::model()->findAll('`to`=:s1 OR `from`=:s1', array(':s1' => $serviceid));
                break;
            case 'offline':
            default:
                if ($inlevel != '') {
                    $connections = Connections::model()->with(array(
                                'fromservice.servicetype' => array(
                                    'select' => false,
                                    'condition' => '`servicetype`.`level` IN (' . $inlevel . ')'
                                ),
                                'toservice.servicetype' => array(
                                    'alias' => 'servicetype2',
                                    'select' => false,
                                    'condition' => '`servicetype2`.`level` IN (' . $inlevel . ')'
                                )
                            ))->findAll($connectionsingroup);
                } else {
                    $connections = Connections::model()->findAll($connectionsingroup);
                }
        }
        foreach ($connections as $connection) {
            $connectiontype = str_replace(',', '\n', $connection->connectiontype->name);
			$constraint = ($connection->fromservice->type == $connection->toservice->type)?'false':'true';
            $graph->addEdge(
                    array(
                $connection->fromservice->servicename => $connection->toservice->servicename
                    ), array(
                'fontsize' => '7',
                'fontname' => 'Lucida-Sans',
                'label' => $connectiontype,
                'color' => '#' . $connection->connectiontype->color . '55',
                'arrowsize' => '0.5',
                'fontcolor' => '#' . $connection->connectiontype->color,
                'headclip' => 'true',
                'tailclip' => 'true',
                //'constraint' => $constraint,
                    )
            );
        }
        $this->cleanupTmpFiles();
        return $graph;
    }

    private function cleanupTmpFiles() {
        //cleanup old files
        if (is_dir('assets/graph') && (($dh = opendir('assets/graph')) == true)) {
            while (false !== ($obj = readdir($dh))) {
                if ($obj == '.' || $obj == '..')
                    continue;
                if (filemtime('assets/graph/' . $obj) < (time() - 3600))
                    unlink('assets/graph/' . $obj);
            }
            closedir($dh);
        }
    }

}
