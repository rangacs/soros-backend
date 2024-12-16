<?php
/* @var $this GadgetsDataController */
/* @var $model GadgetsData */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'gadgets-data-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'gadget_type'); ?>
		<?php echo $form->textField($model,'gadget_type',array('size'=>20,'maxlength'=>20)); ?>
		<?php echo $form->error($model,'gadget_type'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'lay_id'); ?>
		<?php echo $form->textField($model,'lay_id',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'lay_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'widgetsPos'); ?>
		<?php echo $form->textField($model,'widgetsPos',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'widgetsPos'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'gadget_name'); ?>
		<?php echo $form->textField($model,'gadget_name',array('size'=>30,'maxlength'=>30)); ?>
		<?php echo $form->error($model,'gadget_name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'gadget_size'); ?>
		<?php echo $form->textField($model,'gadget_size',array('size'=>6,'maxlength'=>6)); ?>
		<?php echo $form->error($model,'gadget_size'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'last_updated'); ?>
		<?php echo $form->textField($model,'last_updated'); ?>
		<?php echo $form->error($model,'last_updated'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'data_source'); ?>
		<?php echo $form->textArea($model,'data_source',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'data_source'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'detector_source'); ?>
		<?php echo $form->textArea($model,'detector_source',array('rows'=>6, 'cols'=>50)); ?>
		<?php echo $form->error($model,'detector_source'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'group_style'); ?>
		<?php echo $form->textField($model,'group_style',array('size'=>60,'maxlength'=>200)); ?>
		<?php echo $form->error($model,'group_style'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'display_style'); ?>
		<?php echo $form->textField($model,'display_style',array('size'=>60,'maxlength'=>200)); ?>
		<?php echo $form->error($model,'display_style'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->