<?php
/* @var $this WclRfidLogMessagesController */
/* @var $model WclRfidLogMessages */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'wcl-rfid-log-messages-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'message_type'); ?>
		<?php echo $form->textField($model,'message_type',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'message_type'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'short_descrip'); ?>
		<?php echo $form->textField($model,'short_descrip',array('size'=>60,'maxlength'=>255)); ?>
		<?php echo $form->error($model,'short_descrip'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'long_descrip'); ?>
		<?php echo $form->textArea($model,'long_descrip',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'long_descrip'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'trip_id'); ?>
		<?php echo $form->textField($model,'trip_id',array('size'=>60,'maxlength'=>75)); ?>
		<?php echo $form->error($model,'trip_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'unloaderID'); ?>
		<?php echo $form->textField($model,'unloaderID',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'unloaderID'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'vehNo'); ?>
		<?php echo $form->textField($model,'vehNo',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'vehNo'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'flag'); ?>
		<?php echo $form->textField($model,'flag'); ?>
		<?php echo $form->error($model,'flag'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'timestamp'); ?>
		<?php echo $form->textField($model,'timestamp'); ?>
		<?php echo $form->error($model,'timestamp'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->