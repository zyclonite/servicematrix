<?php
$this->breadcrumbs=array(
	'Servicetypes'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Servicetypes', 'url'=>array('index')),
	array('label'=>'Manage Servicetypes', 'url'=>array('admin')),
);
?>

<h1>Create Servicetypes</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model, 'images'=>$images)); ?>