<?php
/* @var $this RtaDerivedConfigController */
/* @var $model RtaDerivedConfig */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'rta-derived-config-form',
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
		<?php echo $form->labelEx($model,'data_source_rtaMasterID'); ?>
		<?php echo $form->textField($model,'data_source_rtaMasterID',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'data_source_rtaMasterID'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'filter_type'); ?>
		<?php echo $form->textField($model,'filter_type',array('size'=>0,'maxlength'=>0)); ?>
		<?php echo $form->error($model,'filter_type'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'moving_avg_filter_sample_timespan'); ?>
		<?php echo $form->textField($model,'moving_avg_filter_sample_timespan',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'moving_avg_filter_sample_timespan'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'kalman_filter_sample_timespan'); ?>
		<?php echo $form->textField($model,'kalman_filter_sample_timespan',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'kalman_filter_sample_timespan'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'massflowWeight_derivedCfg'); ?>
		<?php echo $form->textField($model,'massflowWeight_derivedCfg',array('size'=>1,'maxlength'=>1)); ?>
		<?php echo $form->error($model,'massflowWeight_derivedCfg'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'goodDataSecondsWeight_derivedCfg'); ?>
		<?php echo $form->textField($model,'goodDataSecondsWeight_derivedCfg',array('size'=>1,'maxlength'=>1)); ?>
		<?php echo $form->error($model,'goodDataSecondsWeight_derivedCfg'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'percentageGoodDataRequired'); ?>
		<?php echo $form->textField($model,'percentageGoodDataRequired',array('size'=>3,'maxlength'=>3)); ?>
		<?php echo $form->error($model,'percentageGoodDataRequired'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'kalman_gain_Q'); ?>
		<?php echo $form->textField($model,'kalman_gain_Q'); ?>
		<?php echo $form->error($model,'kalman_gain_Q'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'kalman_gain_R'); ?>
		<?php echo $form->textField($model,'kalman_gain_R'); ?>
		<?php echo $form->error($model,'kalman_gain_R'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'source_decay_comp'); ?>
		<?php echo $form->textField($model,'source_decay_comp',array('size'=>1,'maxlength'=>1)); ?>
		<?php echo $form->error($model,'source_decay_comp'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'source_decay_ref_date'); ?>
		<?php echo $form->textField($model,'source_decay_ref_date'); ?>
		<?php echo $form->error($model,'source_decay_ref_date'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->