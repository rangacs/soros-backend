<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'tag-group-taggroup-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'tagGroupName'); ?>
		<?php echo $form->textField($model,'tagGroupName'); ?>
		<?php echo $form->error($model,'tagGroupName'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'rtaMasterID'); ?>
		<?php echo $form->textField($model,'rtaMasterID'); ?>
		<?php echo $form->error($model,'rtaMasterID'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'massflowWeight'); ?>
		<?php echo $form->textField($model,'massflowWeight'); ?>
		<?php echo $form->error($model,'massflowWeight'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'goodDataSecondsWeight'); ?>
		<?php echo $form->textField($model,'goodDataSecondsWeight'); ?>
		<?php echo $form->error($model,'goodDataSecondsWeight'); ?>
	</div>


	<div class="row buttons">
		<?php echo CHtml::submitButton('Submit'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->