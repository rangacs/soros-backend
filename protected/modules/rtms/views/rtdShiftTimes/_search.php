<?php
/* @var $this RtdShiftTimesController */
/* @var $model RtdShiftTimes */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'shiftTimeID'); ?>
		<?php echo $form->textField($model,'shiftTimeID',array('size'=>11,'maxlength'=>11)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'shiftStart1'); ?>
		<?php echo $form->textField($model,'shiftStart1'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'shiftDuration1'); ?>
		<?php echo $form->textField($model,'shiftDuration1'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'shiftStart2'); ?>
		<?php echo $form->textField($model,'shiftStart2'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'shiftDuration2'); ?>
		<?php echo $form->textField($model,'shiftDuration2'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'shiftStart3'); ?>
		<?php echo $form->textField($model,'shiftStart3'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'shiftDuration3'); ?>
		<?php echo $form->textField($model,'shiftDuration3'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->