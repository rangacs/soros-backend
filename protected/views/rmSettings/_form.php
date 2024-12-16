<?php
/* @var $this RmSettingsController */
/* @var $model RmSettings */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'rm-settings-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'varName'); ?>
		<?php echo $form->textField($model,'varName',array('size'=>60,'maxlength'=>500)); ?>
		<?php echo $form->error($model,'varName'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'varKey'); ?>
		<?php echo $form->textField($model,'varKey',array('size'=>60,'maxlength'=>200)); ?>
		<?php echo $form->error($model,'varKey'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'varValue'); ?>
		<?php echo $form->textField($model,'varValue',array('size'=>60,'maxlength'=>500)); ?>
		<?php echo $form->error($model,'varValue'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->