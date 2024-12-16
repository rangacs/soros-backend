<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'rta-tag-index-queued-tagHandler-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'rtaMasterID'); ?>
		<?php echo $form->textField($model,'rtaMasterID'); ?>
		<?php echo $form->error($model,'rtaMasterID'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'status'); ?>
		<?php echo $form->textField($model,'status'); ?>
		<?php echo $form->error($model,'status'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tagName'); ?>
		<?php echo $form->textField($model,'tagName'); ?>
		<?php echo $form->error($model,'tagName'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tagGroupID'); ?>
		<?php echo $form->textField($model,'tagGroupID'); ?>
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
		<?php echo CHtml::submitButton('Submit'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->