<?php
$this->breadcrumbs=array(
	'Connectiontypes',
);

$this->menu=array(
	array('label'=>'Create Connectiontypes', 'url'=>array('create')),
	array('label'=>'Manage Connectiontypes', 'url'=>array('admin')),
);
?>

<h1>Connectiontypes</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
