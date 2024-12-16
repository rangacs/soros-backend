<?php
/* @var $this SourceController */
/* @var $model Source */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'source-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'src_name'); ?>
		<?php echo $form->textField($model,'src_name',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'src_name'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'src_type'); ?>
		<?php echo $form->textField($model,'src_type',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'src_type'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'product_id'); ?>
		<?php echo $form->textField($model,'product_id',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'product_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'src_priority'); ?>
		<?php echo $form->textField($model,'src_priority',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'src_priority'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'src_distance'); ?>
		<?php echo $form->textField($model,'src_distance',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'src_distance'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'src_delay'); ?>
		<?php echo $form->textField($model,'src_delay',array('size'=>60,'maxlength'=>100)); ?>
		<?php echo $form->error($model,'src_delay'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'src_min_feedrate'); ?>
		<?php echo $form->textField($model,'src_min_feedrate',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'src_min_feedrate'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'src_max_feedrate'); ?>
		<?php echo $form->textField($model,'src_max_feedrate',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'src_max_feedrate'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'src_proposed_feedrate'); ?>
		<?php echo $form->textField($model,'src_proposed_feedrate',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'src_proposed_feedrate'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'src_measured_feedrate'); ?>
		<?php echo $form->textField($model,'src_measured_feedrate',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'src_measured_feedrate'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'src_cost'); ?>
		<?php echo $form->textField($model,'src_cost',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'src_cost'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'src_status'); ?>
		<?php echo $form->textField($model,'src_status',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'src_status'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->