<div class="view">

	<?php echo CHtml::tag('div', array('style'=>'float: right;'),CHtml::image(Yii::app()->request->baseUrl . '/' . Yii::app()->params['nodeImages'] . $data->servicetype->image, '')); ?>

	<b><?php echo CHtml::encode($data->getAttributeLabel('id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->id), array('view', 'id'=>$data->id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('servicename')); ?>:</b>
	<?php echo CHtml::encode($data->servicename); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('type')); ?>:</b>
	<?php echo CHtml::tag('font', array('style'=>'color: #'.$data->servicetype->color.';'),CHtml::encode($data->servicetype->name)); ?>
	<br />

</div>