<?php
$this->breadcrumbs=array(
	'Connectiontypes'=>array('index'),
	$model->name=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Connectiontypes', 'url'=>array('index')),
	array('label'=>'Create Connectiontypes', 'url'=>array('create')),
	array('label'=>'View Connectiontypes', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Connectiontypes', 'url'=>array('admin')),
);
?>

<h1>Update Connectiontypes <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>