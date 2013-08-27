<?php
$this->breadcrumbs=array(
	'Connectiontypes'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Connectiontypes', 'url'=>array('index')),
	array('label'=>'Manage Connectiontypes', 'url'=>array('admin')),
);
?>

<h1>Create Connectiontypes</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>