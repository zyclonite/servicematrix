<?php
$this->breadcrumbs=array(
	'Connections'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List Connections', 'url'=>array('index')),
	array('label'=>'Create Connections', 'url'=>array('create')),
	array('label'=>'View Connections', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage Connections', 'url'=>array('admin')),
);
?>

<h1>Update Connections <?php echo $model->id; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model, 'types'=>$types, 'services'=>$services)); ?>