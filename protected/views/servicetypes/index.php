<?php
$this->breadcrumbs=array(
	'Servicetypes',
);

$this->menu=array(
	array('label'=>'Create Servicetypes', 'url'=>array('create')),
	array('label'=>'Manage Servicetypes', 'url'=>array('admin')),
);
?>

<h1>Servicetypes</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
