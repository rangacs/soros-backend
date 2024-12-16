<?php
/* @var $this RtaDerivedConfigController */
/* @var $model RtaDerivedConfig */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'rta_ID_derived'); ?>
		<?php echo $form->textField($model,'rta_ID_derived',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'rtaMasterID'); ?>
		<?php echo $form->textField($model,'rtaMasterID',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'data_source_rtaMasterID'); ?>
		<?php echo $form->textField($model,'data_source_rtaMasterID',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'filter_type'); ?>
		<?php echo $form->textField($model,'filter_type',array('size'=>0,'maxlength'=>0)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'moving_avg_filter_sample_timespan'); ?>
		<?php echo $form->textField($model,'moving_avg_filter_sample_timespan',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'kalman_filter_sample_timespan'); ?>
		<?php echo $form->textField($model,'kalman_filter_sample_timespan',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'massflowWeight_derivedCfg'); ?>
		<?php echo $form->textField($model,'massflowWeight_derivedCfg',array('size'=>1,'maxlength'=>1)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'goodDataSecondsWeight_derivedCfg'); ?>
		<?php echo $form->textField($model,'goodDataSecondsWeight_derivedCfg',array('size'=>1,'maxlength'=>1)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'percentageGoodDataRequired'); ?>
		<?php echo $form->textField($model,'percentageGoodDataRequired',array('size'=>3,'maxlength'=>3)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'kalman_gain_Q'); ?>
		<?php echo $form->textField($model,'kalman_gain_Q'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'kalman_gain_R'); ?>
		<?php echo $form->textField($model,'kalman_gain_R'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'source_decay_comp'); ?>
		<?php echo $form->textField($model,'source_decay_comp',array('size'=>1,'maxlength'=>1)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'source_decay_ref_date'); ?>
		<?php echo $form->textField($model,'source_decay_ref_date'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->