<?php
/* @var $this RtaJobTableController */
/* @var $model RtaJobTable */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'jobID'); ?>
		<?php echo $form->textField($model,'jobID'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'jobStatus'); ?>
		<?php echo $form->textField($model,'jobStatus',array('size'=>11,'maxlength'=>11)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'linuxPID'); ?>
		<?php echo $form->textField($model,'linuxPID'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'start_time'); ?>
		<?php echo $form->textField($model,'start_time'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'end_time'); ?>
		<?php echo $form->textField($model,'end_time'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'backupTable'); ?>
		<?php echo $form->textField($model,'backupTable',array('size'=>40,'maxlength'=>40)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'tempTable'); ?>
		<?php echo $form->textField($model,'tempTable',array('size'=>40,'maxlength'=>40)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'regenTable'); ?>
		<?php echo $form->textField($model,'regenTable',array('size'=>40,'maxlength'=>40)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'originalTable'); ?>
		<?php echo $form->textField($model,'originalTable',array('size'=>40,'maxlength'=>40)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'originalTableID'); ?>
		<?php echo $form->textField($model,'originalTableID'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'loopsFinished'); ?>
		<?php echo $form->textField($model,'loopsFinished'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'recordsRemaining'); ?>
		<?php echo $form->textField($model,'recordsRemaining'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'recordsTotal'); ?>
		<?php echo $form->textField($model,'recordsTotal'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'maxID'); ?>
		<?php echo $form->textField($model,'maxID'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'dateAdded'); ?>
		<?php echo $form->textField($model,'dateAdded'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'dateModified'); ?>
		<?php echo $form->textField($model,'dateModified'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'dateCompleted'); ?>
		<?php echo $form->textField($model,'dateCompleted'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'userAdded'); ?>
		<?php echo $form->textField($model,'userAdded',array('size'=>20,'maxlength'=>20)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'userModified'); ?>
		<?php echo $form->textField($model,'userModified',array('size'=>20,'maxlength'=>20)); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->