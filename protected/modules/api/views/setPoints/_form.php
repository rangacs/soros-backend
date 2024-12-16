<?php
/* @var $this SetPointsController */
/* @var $model SetPoints */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'set-points-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'product_id'); ?>
		<?php echo $form->textField($model,'product_id'); ?>
		<?php echo $form->error($model,'product_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'sp_name'); ?>
		<?php echo $form->textField($model,'sp_name',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'sp_name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'sp_value_num'); ?>
		<?php echo $form->textField($model,'sp_value_num',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'sp_value_num'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'sp_measured'); ?>
		<?php echo $form->textField($model,'sp_measured',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'sp_measured'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'sp_value_den'); ?>
		<?php echo $form->textField($model,'sp_value_den',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'sp_value_den'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'sp_const_value_num'); ?>
		<?php echo $form->textField($model,'sp_const_value_num',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'sp_const_value_num'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'sp_const_value_den'); ?>
		<?php echo $form->textField($model,'sp_const_value_den',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'sp_const_value_den'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'sp_tolerance_ulevel'); ?>
		<?php echo $form->textField($model,'sp_tolerance_ulevel',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'sp_tolerance_ulevel'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'sp_tolerance_llevel'); ?>
		<?php echo $form->textField($model,'sp_tolerance_llevel',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'sp_tolerance_llevel'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'sp_weight'); ?>
		<?php echo $form->textField($model,'sp_weight',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'sp_weight'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'sp_status'); ?>
		<?php echo $form->textField($model,'sp_status'); ?>
		<?php echo $form->error($model,'sp_status'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'sp_priority'); ?>
		<?php echo $form->textField($model,'sp_priority'); ?>
		<?php echo $form->error($model,'sp_priority'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->