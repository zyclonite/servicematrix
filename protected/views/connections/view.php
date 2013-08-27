<?php
$this->breadcrumbs=array(
	'Connections'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List Connections', 'url'=>array('index')),
	array('label'=>'Create Connections', 'url'=>array('create')),
	array('label'=>'Update Connections', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete Connections', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage Connections', 'url'=>array('admin')),
);
?>

<h1>View Connection #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		array(
                        'label'=>CHtml::encode($model->getAttributeLabel('from')),
                        'type'=>'text',
                        'value'=>CHtml::encode($model->fromservice->servicename),
                ),
		array(
                        'label'=>CHtml::encode($model->getAttributeLabel('to')),
                        'type'=>'text',
                        'value'=>CHtml::encode($model->toservice->servicename),
                ),
		'percent',
		array(
                        'label'=>CHtml::encode($model->getAttributeLabel('type')),
                        'type'=>'text',
                        'value'=>CHtml::encode($model->connectiontype->name),
                ),
	),
)); ?>
