<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'usergroups-webconfig-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'u_id'); ?>
		<?php echo $form->textField($model,'u_id',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'u_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'rule'); ?>
		<?php echo $form->textField($model,'rule',array('size'=>40,'maxlength'=>40)); ?>
		<?php echo $form->error($model,'rule'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'value'); ?>
		<?php echo $form->textField($model,'value',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'value'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'options'); ?>
		<?php echo $form->textArea($model,'options',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'options'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'description'); ?>
		<?php echo $form->textArea($model,'description',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'description'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->