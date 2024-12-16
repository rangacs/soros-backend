<?php
/* @var $this RtaTagIndexQueuedController */
/* @var $model RtaTagIndexQueuedNew */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'rta-tag-index-queued-new-form',
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
		<?php echo $form->labelEx($model,'status'); ?>
		<?php echo $form->textField($model,'status',array('size'=>9,'maxlength'=>9)); ?>
		<?php echo $form->error($model,'status'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tagName'); ?>
		<?php echo $form->textField($model,'tagName',array('size'=>30,'maxlength'=>30)); ?>
		<?php echo $form->error($model,'tagName'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tagGroupID'); ?>
		<?php echo $form->textField($model,'tagGroupID',array('size'=>1,'maxlength'=>1)); ?>
		<?php echo $form->error($model,'tagGroupID'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'LocalstartTime'); ?>
		<?php echo $form->textField($model,'LocalstartTime'); ?>
		<?php echo $form->error($model,'LocalstartTime'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'LocalendTime'); ?>
		<?php echo $form->textField($model,'LocalendTime'); ?>
		<?php echo $form->error($model,'LocalendTime'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->