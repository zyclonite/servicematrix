<?php
$this->breadcrumbs=array(
	'Connections',
);

$this->menu=array(
	array('label'=>'Create Connections', 'url'=>array('create')),
	array('label'=>'Manage Connections', 'url'=>array('admin')),
);
?>

<h1>Connections</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
