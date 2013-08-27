<?php
$urlreplace = 'function redirect(url, level, group, method) {'
			.'if(level > "0") {'
			.'url = url + (url.indexOf(\'?\') != -1 ? "&" : "?") + "level=" + level'
			.'}'
			.'if(group > 0) {'
			.'url = url + (url.indexOf(\'?\') != -1 ? "&" : "?") + "group=" + group'
			.'}'
			.'if(method == "dot" || method == "neato" || method == "fdp" || method == "sfdp") {'
			.'url = url + (url.indexOf(\'?\') != -1 ? "&" : "?") + "method=" + method'
			.'}'
			.'window.location.replace(url)'
			.'}';
$this->beginWidget('zii.widgets.jui.CJuiDialog', array(
    'id'=>'nodeaction',
    // additional javascript options for the dialog plugin
    'options'=>array(
        'title'=>'Action',
        'autoOpen'=>false,
        'modal'=>true,
        'resizable'=>false,
        'skin'=>false,
        'buttons'=>array(
             'Mark as offline'=>'js:function(){ redirect("'.$this->createUrl('graph/offline',array('id'=>'')).'"+$(this).attr("nodeid").substring(1),"'.(isset($_GET['level'])?$_GET['level']:'0').'",'.(isset($_GET['group'])?$_GET['group']:'0').',"'.(isset($_GET['method'])?$_GET['method']:'dot').'")}',
             'Direct dependencies'=>'js:function(){ redirect("'.$this->createUrl('graph/direct',array('id'=>'')).'"+$(this).attr("nodeid").substring(1),"'.(isset($_GET['level'])?$_GET['level']:'0').'",'.(isset($_GET['group'])?$_GET['group']:'0').',"'.(isset($_GET['method'])?$_GET['method']:'dot').'")}',
             'Cancel'=>'js:function(){ $(this).dialog("close");}',
         ),
     ),
));
$this->endWidget('zii.widgets.jui.CJuiDialog');

$idarray=array();
if($id > 0)
	$idarray = array('id'=>$id);
echo CHtml::label('Group: ','group');
echo CHtml::dropDownList('group', (isset($_GET['group'])?$_GET['group']:'0'), array('0'=>'none')+CHtml::listData($groups, 'id', 'name')) .'&nbsp;';
echo CHtml::label('Level: ','level');
echo CHtml::dropDownList('level', (isset($_GET['level'])?$_GET['level']:'0'), array('0'=>'0 only','0:1'=>'0 to 1','1'=>'1 only','1:2'=>'1 to 2','2'=>'2 only','2:3'=>'2 to 3','3'=>'3 only','3:4'=>'3 to 4','4'=>'4 only','4:5'=>'4 to 5','5'=>'5 only')) .'&nbsp;';
echo CHtml::label('Method: ','method');
echo CHtml::dropDownList('method', (isset($_GET['method'])?$_GET['method']:'dot'), array('dot'=>'dot','neato'=>'neato','fdp'=>'fdp','sfdp'=>'sfdp')) .'&nbsp;';
echo CHtml::button('Generate',array('id'=>'newgraph'));
echo CHtml::script('$("#newgraph").click(function(){redirect("'.$this->createUrl($this->getRoute(),$idarray).'",$("#level").val(),$("#group").val(),$("#method").val());});');

//echo '<pre>'.$graph->parse().'</pre>';
$data = $graph->cmapx();
$rand = mt_rand(0, 9999999);
$graph = 'graph_'.date('Y.m.d').'_'.$rand.'.png';
if (!is_dir(Yii::app()->assetManager->getBasePath().'/graph')) mkdir(Yii::app()->assetManager->getBasePath().'/graph');
$fp = fopen(Yii::app()->assetManager->getBasePath().'/graph/'.$graph, 'wb');
fwrite($fp, $data['img']);
fclose($fp);
echo $data['map'];
echo CHtml::tag('div', array('style'=>'overflow: auto;'), CHtml::tag('div', array('style'=>'width: 100%;text-align: center;'), CHtml::image(Yii::app()->assetManager->getBaseUrl().'/graph/'.$graph, '', array('usemap'=>'#G'))));
echo CHtml::script($urlreplace);
echo CHtml::tag('div', array('style'=>'float: right;'), CHtml::label('Static Image: ',false,array('style'=>'font-size: 10px')).CHtml::link('link',$this->createAbsoluteUrl($this->getRoute(),$idarray+$_GET+array('output'=>'image')),array('style'=>'font-size: 10px')).'&nbsp;'.CHtml::label('GraphML: ',false,array('style'=>'font-size: 10px')).CHtml::link('download',$this->createAbsoluteUrl($this->getRoute(),$idarray+$_GET+array('output'=>'graphml')),array('style'=>'font-size: 10px')));
