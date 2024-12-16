<?php
/* @var $this CalibrationLogController */
/* @var $model CalibrationLog */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'calibration-log-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'SiO2_gain'); ?>
		<?php echo $form->textField($model,'SiO2_gain'); ?>
		<?php echo $form->error($model,'SiO2_gain'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'SiO2_offset'); ?>
		<?php echo $form->textField($model,'SiO2_offset'); ?>
		<?php echo $form->error($model,'SiO2_offset'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'Fe2O3_gain'); ?>
		<?php echo $form->textField($model,'Fe2O3_gain'); ?>
		<?php echo $form->error($model,'Fe2O3_gain'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'Fe2O3_offset'); ?>
		<?php echo $form->textField($model,'Fe2O3_offset'); ?>
		<?php echo $form->error($model,'Fe2O3_offset'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'Al2O3_gain'); ?>
		<?php echo $form->textField($model,'Al2O3_gain'); ?>
		<?php echo $form->error($model,'Al2O3_gain'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'Al2O3_offset'); ?>
		<?php echo $form->textField($model,'Al2O3_offset'); ?>
		<?php echo $form->error($model,'Al2O3_offset'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'CaO_gain'); ?>
		<?php echo $form->textField($model,'CaO_gain'); ?>
		<?php echo $form->error($model,'CaO_gain'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'CaO_offset'); ?>
		<?php echo $form->textField($model,'CaO_offset'); ?>
		<?php echo $form->error($model,'CaO_offset'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'updated_by'); ?>
		<?php echo $form->textField($model,'updated_by'); ?>
		<?php echo $form->error($model,'updated_by'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'updated'); ?>
		<?php echo $form->textField($model,'updated'); ?>
		<?php echo $form->error($model,'updated'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->