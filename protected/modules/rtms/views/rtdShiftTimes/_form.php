<?php
/* @var $this RtdShiftTimesController */
/* @var $model RtdShiftTimes */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'rtd-shift-times-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'shiftStart1'); ?>
		<?php echo $form->textField($model,'shiftStart1'); ?>
		<?php echo $form->error($model,'shiftStart1'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'shiftDuration1'); ?>
		<?php echo $form->textField($model,'shiftDuration1'); ?>
		<?php echo $form->error($model,'shiftDuration1'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'shiftStart2'); ?>
		<?php echo $form->textField($model,'shiftStart2'); ?>
		<?php echo $form->error($model,'shiftStart2'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'shiftDuration2'); ?>
		<?php echo $form->textField($model,'shiftDuration2'); ?>
		<?php echo $form->error($model,'shiftDuration2'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'shiftStart3'); ?>
		<?php echo $form->textField($model,'shiftStart3'); ?>
		<?php echo $form->error($model,'shiftStart3'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'shiftDuration3'); ?>
		<?php echo $form->textField($model,'shiftDuration3'); ?>
		<?php echo $form->error($model,'shiftDuration3'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->