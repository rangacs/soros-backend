<?php
/* @var $this ElementCompositionController */
/* @var $model ElementComposition */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'element_id'); ?>
		<?php echo $form->textField($model,'element_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'source_id'); ?>
		<?php echo $form->textField($model,'source_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'element_name'); ?>
		<?php echo $form->textField($model,'element_name',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'element_value'); ?>
		<?php echo $form->textField($model,'element_value',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'element_type'); ?>
		<?php echo $form->textField($model,'element_type'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'estimated_prob_error'); ?>
		<?php echo $form->textField($model,'estimated_prob_error'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'estimated_max'); ?>
		<?php echo $form->textField($model,'estimated_max'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'estimated_min'); ?>
		<?php echo $form->textField($model,'estimated_min'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'update_timestamp'); ?>
		<?php echo $form->textField($model,'update_timestamp'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->