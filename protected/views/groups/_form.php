<div class="form">

<?php $this->widget(
      'application.extensions.emultiselect.EMultiSelect',
      array('sortable'=>false, 'searchable'=>true)
); ?>

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'groups-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>30,'maxlength'=>30)); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'description'); ?>
		<?php echo $form->textField($model,'description',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'description'); ?>
	</div>

       	<div class="row">
                <?php #echo $form->checkBoxList($model, 'services', CHtml::listData($services, 'id', 'servicename'), array('attributeitem' => 'id', 'checkAll' => 'Check All')); ?>
                <?php echo $form->listBox($model, 'services', CHtml::listData($services, 'id', 'servicename'), array('multiple'=>'multiple', 'class'=>'multiselect')); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
