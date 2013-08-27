<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'connections-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'from'); ?>
                <?php echo $form->dropDownList($model, 'from',CHtml::listData($services, 'id', 'servicename')); ?>
		<?php echo $form->error($model,'from'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'to'); ?>
                <?php echo $form->dropDownList($model, 'to',CHtml::listData($services, 'id', 'servicename')); ?>
		<?php echo $form->error($model,'to'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'percent'); ?>
		<?php echo $form->hiddenField($model,'percent'); ?>
                <div id="percent_amount"><?php echo CHtml::value($model, 'percent', '100'); ?>%</div>
                <?php $this->widget('zii.widgets.jui.CJuiSlider', array(
                    'id'=>'percent_slider',
                    'options'=>array(
                        'min'=>0,
                        'max'=>100,
                        'value'=>CHtml::value($model, 'percent', '100'),
                        'slide'=>'js:function(event, ui) {
                            $("#'.CHtml::activeId($model, 'percent').'").val(ui.value); 
                            $("#percent_amount").text(ui.value+"%");
                            }',
                    ),
                    'htmlOptions'=>array(
                        'style'=>'width:200px;'
                    ),
                )); ?>
                <?php echo $form->error($model,'percent'); ?>
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