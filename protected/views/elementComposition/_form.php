<?php
/* @var $this ElementCompositionController */
/* @var $model ElementComposition */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'element-composition-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'element_id'); ?>
		<?php echo $form->textField($model,'element_id'); ?>
		<?php echo $form->error($model,'element_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'source_id'); ?>
		<?php echo $form->textField($model,'source_id'); ?>
		<?php echo $form->error($model,'source_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'element_name'); ?>
		<?php echo $form->textField($model,'element_name',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'element_name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'element_value'); ?>
		<?php echo $form->textField($model,'element_value',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'element_value'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'element_type'); ?>
		<?php echo $form->textField($model,'element_type'); ?>
		<?php echo $form->error($model,'element_type'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'estimated_prob_error'); ?>
		<?php echo $form->textField($model,'estimated_prob_error'); ?>
		<?php echo $form->error($model,'estimated_prob_error'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'estimated_max'); ?>
		<?php echo $form->textField($model,'estimated_max'); ?>
		<?php echo $form->error($model,'estimated_max'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'estimated_min'); ?>
		<?php echo $form->textField($model,'estimated_min'); ?>
		<?php echo $form->error($model,'estimated_min'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'update_timestamp'); ?>
		<?php echo $form->textField($model,'update_timestamp'); ?>
		<?php echo $form->error($model,'update_timestamp'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->