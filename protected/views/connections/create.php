<?php
$this->breadcrumbs=array(
	'Connections'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Connections', 'url'=>array('index')),
	array('label'=>'Manage Connections', 'url'=>array('admin')),
);
?>

<h1>Create Connections</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model, 'types'=>$types, 'services'=>$services)); ?>