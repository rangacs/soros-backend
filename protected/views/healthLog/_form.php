<?php
/* @var $this HealthLogController */
/* @var $model HealthLog */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'health-log-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'wcl_plantCode'); ?>
		<?php echo $form->textField($model,'wcl_plantCode',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'wcl_plantCode'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'wcl_unloaderId'); ?>
		<?php echo $form->textField($model,'wcl_unloaderId',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'wcl_unloaderId'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'wcl_healthStatus'); ?>
		<?php echo $form->textField($model,'wcl_healthStatus',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'wcl_healthStatus'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'wcl_auto_tag_id'); ?>
		<?php echo $form->textField($model,'wcl_auto_tag_id',array('size'=>60,'maxlength'=>200)); ?>
		<?php echo $form->error($model,'wcl_auto_tag_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'wcl_auto_tag_mCode'); ?>
		<?php echo $form->textField($model,'wcl_auto_tag_mCode',array('size'=>60,'maxlength'=>200)); ?>
		<?php echo $form->error($model,'wcl_auto_tag_mCode'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'wcl_ash'); ?>
		<?php echo $form->textField($model,'wcl_ash',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'wcl_ash'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'wcl_moisture'); ?>
		<?php echo $form->textField($model,'wcl_moisture',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'wcl_moisture'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'wcl_sulfur'); ?>
		<?php echo $form->textField($model,'wcl_sulfur',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'wcl_sulfur'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'wcl_gcv'); ?>
		<?php echo $form->textField($model,'wcl_gcv',array('size'=>50,'maxlength'=>50)); ?>
		<?php echo $form->error($model,'wcl_gcv'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'wcl_timestamp'); ?>
		<?php echo $form->textField($model,'wcl_timestamp',array('size'=>60,'maxlength'=>200)); ?>
		<?php echo $form->error($model,'wcl_timestamp'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->