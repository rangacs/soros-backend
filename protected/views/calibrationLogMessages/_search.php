<?php
/* @var $this CalibrationLogMessagesController */
/* @var $model CalibrationLogMessages */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'cal_msg_id'); ?>
		<?php echo $form->textField($model,'cal_msg_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'auto_calib_id'); ?>
		<?php echo $form->textField($model,'auto_calib_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'error_type'); ?>
		<?php echo $form->textField($model,'error_type'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'title'); ?>
		<?php echo $form->textArea($model,'title',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'description'); ?>
		<?php echo $form->textArea($model,'description',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'updated_at'); ?>
		<?php echo $form->textField($model,'updated_at'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'updated_by'); ?>
		<?php echo $form->textField($model,'updated_by'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->