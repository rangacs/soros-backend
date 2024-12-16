<?php
/* @var $this RtaPhysicalConfigController */
/* @var $model RtaPhysicalConfig */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'rta-physical-config-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'rtaMasterID'); ?>
		<?php echo $form->textField($model,'rtaMasterID',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'rtaMasterID'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'IPaddress'); ?>
		<?php echo $form->textField($model,'IPaddress',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'IPaddress'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'goodDataSecondsWeight_physicalCfg'); ?>
		<?php echo $form->textField($model,'goodDataSecondsWeight_physicalCfg',array('size'=>1,'maxlength'=>1)); ?>
		<?php echo $form->error($model,'goodDataSecondsWeight_physicalCfg'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'massflowWeight_physicalCfg'); ?>
		<?php echo $form->textField($model,'massflowWeight_physicalCfg',array('size'=>1,'maxlength'=>1)); ?>
		<?php echo $form->error($model,'massflowWeight_physicalCfg'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'analysis_timespan'); ?>
		<?php echo $form->textField($model,'analysis_timespan',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'analysis_timespan'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'averaging_subinterval_secs'); ?>
		<?php echo $form->textField($model,'averaging_subinterval_secs'); ?>
		<?php echo $form->error($model,'averaging_subinterval_secs'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'detectorID'); ?>
		<?php echo $form->textField($model,'detectorID',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'detectorID'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->