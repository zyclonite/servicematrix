<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'servicetypes-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'name'); ?>
		<?php echo $form->textField($model,'name',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'level'); ?>
		<?php echo $form->dropDownList($model,'level',array('0'=>'0','1'=>'1','2'=>'2','3'=>'3','4'=>'4','5'=>'5')); ?>
		<?php echo $form->error($model,'level'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'color'); ?>
                <?php $this->widget('application.extensions.colorpicker.EColorPicker', array(
                    'model'=>$model,
                    'attribute'=>'color',
                    'mode'=>'textfield',
                    'fade' => false,
                    'slide' => false,
                    'curtain' => true,
                   )); ?>
                <?php echo $form->error($model,'color'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'image'); ?>
		<?php //echo $form->textField($model,'image',array('size'=>30,'maxlength'=>30)); ?>
		<?php echo $form->dropDownList($model,'image',$images); ?>
		<?php echo $form->error($model,'image'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->