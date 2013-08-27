<?php
$this->breadcrumbs=array(
	'Servicetypes'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Servicetypes', 'url'=>array('index')),
	array('label'=>'Create Servicetypes', 'url'=>array('create')),
	array('label'=>'View Servicetypes', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Servicetypes', 'url'=>array('admin')),
);
?>

<h1>Update Servicetypes <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model, 'images'=>$images)); ?>