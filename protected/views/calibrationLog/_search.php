<?php
/* @var $this CalibrationLogController */
/* @var $model CalibrationLog */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'id'); ?>
		<?php echo $form->textField($model,'id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'SiO2_gain'); ?>
		<?php echo $form->textField($model,'SiO2_gain'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'SiO2_offset'); ?>
		<?php echo $form->textField($model,'SiO2_offset'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'Fe2O3_gain'); ?>
		<?php echo $form->textField($model,'Fe2O3_gain'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'Fe2O3_offset'); ?>
		<?php echo $form->textField($model,'Fe2O3_offset'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'Al2O3_gain'); ?>
		<?php echo $form->textField($model,'Al2O3_gain'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'Al2O3_offset'); ?>
		<?php echo $form->textField($model,'Al2O3_offset'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'CaO_gain'); ?>
		<?php echo $form->textField($model,'CaO_gain'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'CaO_offset'); ?>
		<?php echo $form->textField($model,'CaO_offset'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'updated_by'); ?>
		<?php echo $form->textField($model,'updated_by'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'updated'); ?>
		<?php echo $form->textField($model,'updated'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->