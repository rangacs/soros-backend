<?php
/* @var $this RtaJobTableController */
/* @var $model RtaJobTable */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'rta-job-table-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'jobStatus'); ?>
		<?php echo $form->textField($model,'jobStatus',array('size'=>11,'maxlength'=>11)); ?>
		<?php echo $form->error($model,'jobStatus'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'linuxPID'); ?>
		<?php echo $form->textField($model,'linuxPID'); ?>
		<?php echo $form->error($model,'linuxPID'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'start_time'); ?>
		<?php echo $form->textField($model,'start_time'); ?>
		<?php echo $form->error($model,'start_time'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'end_time'); ?>
		<?php echo $form->textField($model,'end_time'); ?>
		<?php echo $form->error($model,'end_time'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'backupTable'); ?>
		<?php echo $form->textField($model,'backupTable',array('size'=>40,'maxlength'=>40)); ?>
		<?php echo $form->error($model,'backupTable'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tempTable'); ?>
		<?php echo $form->textField($model,'tempTable',array('size'=>40,'maxlength'=>40)); ?>
		<?php echo $form->error($model,'tempTable'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'regenTable'); ?>
		<?php echo $form->textField($model,'regenTable',array('size'=>40,'maxlength'=>40)); ?>
		<?php echo $form->error($model,'regenTable'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'originalTable'); ?>
		<?php echo $form->textField($model,'originalTable',array('size'=>40,'maxlength'=>40)); ?>
		<?php echo $form->error($model,'originalTable'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'originalTableID'); ?>
		<?php echo $form->textField($model,'originalTableID'); ?>
		<?php echo $form->error($model,'originalTableID'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'loopsFinished'); ?>
		<?php echo $form->textField($model,'loopsFinished'); ?>
		<?php echo $form->error($model,'loopsFinished'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'recordsRemaining'); ?>
		<?php echo $form->textField($model,'recordsRemaining'); ?>
		<?php echo $form->error($model,'recordsRemaining'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'recordsTotal'); ?>
		<?php echo $form->textField($model,'recordsTotal'); ?>
		<?php echo $form->error($model,'recordsTotal'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'maxID'); ?>
		<?php echo $form->textField($model,'maxID'); ?>
		<?php echo $form->error($model,'maxID'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'dateAdded'); ?>
		<?php echo $form->textField($model,'dateAdded'); ?>
		<?php echo $form->error($model,'dateAdded'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'dateModified'); ?>
		<?php echo $form->textField($model,'dateModified'); ?>
		<?php echo $form->error($model,'dateModified'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'dateCompleted'); ?>
		<?php echo $form->textField($model,'dateCompleted'); ?>
		<?php echo $form->error($model,'dateCompleted'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'userAdded'); ?>
		<?php echo $form->textField($model,'userAdded',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'userAdded'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'userModified'); ?>
		<?php echo $form->textField($model,'userModified',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'userModified'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->