<?php
/* @var $this AcSettingsController */
/* @var $model AcSettings */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'ac_id'); ?>
		<?php echo $form->textField($model,'ac_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'element_name'); ?>
		<?php echo $form->textField($model,'element_name',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'min'); ?>
		<?php echo $form->textField($model,'min'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'max'); ?>
		<?php echo $form->textField($model,'max'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'diff'); ?>
		<?php echo $form->textField($model,'diff'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'max_offset_change'); ?>
		<?php echo $form->textField($model,'max_offset_change'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'correction_pct'); ?>
		<?php echo $form->textField($model,'correction_pct'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'last_updated'); ?>
		<?php echo $form->textField($model,'last_updated'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->