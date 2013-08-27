<?php
$this->breadcrumbs=array(
	'Services'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List Services', 'url'=>array('index')),
	array('label'=>'Manage Services', 'url'=>array('admin')),
);
?>

<h1>Create Services</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model, 'types'=>$types)); ?>