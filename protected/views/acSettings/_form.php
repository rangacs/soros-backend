<?php
/* @var $this AcSettingsController */
/* @var $model AcSettings */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'ac-settings-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'element_name'); ?>
		<?php echo $form->textField($model,'element_name',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'element_name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'min'); ?>
		<?php echo $form->textField($model,'min'); ?>
		<?php echo $form->error($model,'min'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'max'); ?>
		<?php echo $form->textField($model,'max'); ?>
		<?php echo $form->error($model,'max'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'diff'); ?>
		<?php echo $form->textField($model,'diff'); ?>
		<?php echo $form->error($model,'diff'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'max_offset_change'); ?>
		<?php echo $form->textField($model,'max_offset_change'); ?>
		<?php echo $form->error($model,'max_offset_change'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'correction_pct'); ?>
		<?php echo $form->textField($model,'correction_pct'); ?>
		<?php echo $form->error($model,'correction_pct'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'last_updated'); ?>
		<?php echo $form->textField($model,'last_updated'); ?>
		<?php echo $form->error($model,'last_updated'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->