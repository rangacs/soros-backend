<?php
/* @var $this RtaPhysicalConfigController */
/* @var $model RtaPhysicalConfig */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'rta_ID_physical'); ?>
		<?php echo $form->textField($model,'rta_ID_physical'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'rtaMasterID'); ?>
		<?php echo $form->textField($model,'rtaMasterID',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'IPaddress'); ?>
		<?php echo $form->textField($model,'IPaddress',array('size'=>20,'maxlength'=>20)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'goodDataSecondsWeight_physicalCfg'); ?>
		<?php echo $form->textField($model,'goodDataSecondsWeight_physicalCfg',array('size'=>1,'maxlength'=>1)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'massflowWeight_physicalCfg'); ?>
		<?php echo $form->textField($model,'massflowWeight_physicalCfg',array('size'=>1,'maxlength'=>1)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'analysis_timespan'); ?>
		<?php echo $form->textField($model,'analysis_timespan',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'averaging_subinterval_secs'); ?>
		<?php echo $form->textField($model,'averaging_subinterval_secs'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'detectorID'); ?>
		<?php echo $form->textField($model,'detectorID',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->