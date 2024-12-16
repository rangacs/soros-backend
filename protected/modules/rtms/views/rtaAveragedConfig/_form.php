<?php
/* @var $this RtaAveragedConfigController */
/* @var $model RtaAveragedConfig */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'rta-averaged-config-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'rtaMasterID'); ?>
		<?php echo $form->textField($model,'rtaMasterID',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'rtaMasterID'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'groupAveragingID'); ?>
		<?php echo $form->textField($model,'groupAveragingID',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'groupAveragingID'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'massflowWeight_averagedCfg'); ?>
		<?php echo $form->textField($model,'massflowWeight_averagedCfg',array('size'=>1,'maxlength'=>1)); ?>
		<?php echo $form->error($model,'massflowWeight_averagedCfg'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'goodDataSecondsWeight_averagedCfg'); ?>
		<?php echo $form->textField($model,'goodDataSecondsWeight_averagedCfg',array('size'=>1,'maxlength'=>1)); ?>
		<?php echo $form->error($model,'goodDataSecondsWeight_averagedCfg'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->