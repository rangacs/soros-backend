<?php
/* @var $this ProductProfileController */
/* @var $model ProductProfile */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'product-profile-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'user_id'); ?>
		<?php echo $form->textField($model,'user_id'); ?>
		<?php echo $form->error($model,'user_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'product_name'); ?>
		<?php echo $form->textField($model,'product_name',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'product_name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'created_on'); ?>
		<?php echo $form->textField($model,'created_on'); ?>
		<?php echo $form->error($model,'created_on'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'updated_on'); ?>
		<?php echo $form->textField($model,'updated_on'); ?>
		<?php echo $form->error($model,'updated_on'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'target_flow'); ?>
		<?php echo $form->textField($model,'target_flow'); ?>
		<?php echo $form->error($model,'target_flow'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'max_flow_deviation'); ?>
		<?php echo $form->textField($model,'max_flow_deviation'); ?>
		<?php echo $form->error($model,'max_flow_deviation'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'estimate_lsq_mins'); ?>
		<?php echo $form->textField($model,'estimate_lsq_mins'); ?>
		<?php echo $form->error($model,'estimate_lsq_mins'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'sensitivity'); ?>
		<?php echo $form->textField($model,'sensitivity'); ?>
		<?php echo $form->error($model,'sensitivity'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'control_period_mins'); ?>
		<?php echo $form->textField($model,'control_period_mins'); ?>
		<?php echo $form->error($model,'control_period_mins'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'actual_fpm'); ?>
		<?php echo $form->textField($model,'actual_fpm'); ?>
		<?php echo $form->error($model,'actual_fpm'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'actual_tph'); ?>
		<?php echo $form->textField($model,'actual_tph'); ?>
		<?php echo $form->error($model,'actual_tph'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'default_profile'); ?>
		<?php echo $form->textField($model,'default_profile'); ?>
		<?php echo $form->error($model,'default_profile'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'status'); ?>
		<?php echo $form->textField($model,'status',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'status'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'comment'); ?>
		<?php echo $form->textField($model,'comment',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'comment'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->