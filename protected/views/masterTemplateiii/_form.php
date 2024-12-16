<?php
/* @var $this MasterTemplateiiiController */
/* @var $model MasterTemplateiii */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'master-templateiii-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'_key_'); ?>
		<?php echo $form->textField($model,'_key_',array('size'=>60,'maxlength'=>120)); ?>
		<?php echo $form->error($model,'_key_'); ?>
	</div>
  
  
  <!-- Oct28th Modifying.. -->
  <div class="row">
		<label for="MasterTemplateiii_action_" class="required">Action <span class="required">*</span></label>		<input size="60" maxlength="120" name="MasterTemplateiii[action]" id="MasterTemplateiii_action" type="text" />			</div>

  <div class="row">
		<label for="MasterTemplateiii_controller_" class="required">Controller <span class="required">*</span></label>		<input size="60" maxlength="120" name="MasterTemplateiii[controller]" id="MasterTemplateiii_controller" type="text" />			</div>

  <div class="row">
		<label for="MasterTemplateiii_category_" class="required">Category <span class="required">*</span></label>		<input size="60" maxlength="120" name="MasterTemplateiii[category]" id="MasterTemplateiii_category" type="text" />			</div>

  <div class="row">
		<label for="MasterTemplateiii_priority_" class="required">Priority <span class="required">*</span></label>		<input size="60" maxlength="120" name="MasterTemplateiii[priority]" id="MasterTemplateiii_priority" type="text" />			</div>

  <div class="row">
		<label for="MasterTemplateiii_message_type_" class="required">Message Type <span class="required">*</span></label>		<input size="60" maxlength="120" name="MasterTemplateiii[message_type]" id="MasterTemplateiii_message_type" type="text" />			</div>

  <!-- end Modifying -->


	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->