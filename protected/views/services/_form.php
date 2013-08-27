<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'services-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'servicename'); ?>
		<?php echo $form->textField($model,'servicename',array('size'=>30,'maxlength'=>30)); ?>
		<?php echo $form->error($model,'servicename'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'type'); ?>
                <?php echo $form->dropDownList($model, 'type',CHtml::listData($types, 'id', 'name')); ?>
		<?php echo $form->error($model,'type'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->