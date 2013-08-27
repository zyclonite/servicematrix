<?php
$this->breadcrumbs=array(
	'Servicetypes'=>array('index'),
	$model->name,
);

$this->menu=array(
	array('label'=>'List Servicetypes', 'url'=>array('index')),
	array('label'=>'Create Servicetypes', 'url'=>array('create')),
	array('label'=>'Update Servicetypes', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Servicetypes', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Servicetypes', 'url'=>array('admin')),
);
?>

<h1>View Servicetype #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'name',
		'level',
		'color',
		'image',
	),
)); ?>
