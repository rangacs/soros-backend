<?php
/* @var $this SetPointsController */
/* @var $model SetPoints */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'sp_id'); ?>
		<?php echo $form->textField($model,'sp_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'product_id'); ?>
		<?php echo $form->textField($model,'product_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'sp_name'); ?>
		<?php echo $form->textField($model,'sp_name',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'sp_value_num'); ?>
		<?php echo $form->textField($model,'sp_value_num',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'sp_measured'); ?>
		<?php echo $form->textField($model,'sp_measured',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'sp_value_den'); ?>
		<?php echo $form->textField($model,'sp_value_den',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'sp_const_value_num'); ?>
		<?php echo $form->textField($model,'sp_const_value_num',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'sp_const_value_den'); ?>
		<?php echo $form->textField($model,'sp_const_value_den',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'sp_tolerance_ulevel'); ?>
		<?php echo $form->textField($model,'sp_tolerance_ulevel',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'sp_tolerance_llevel'); ?>
		<?php echo $form->textField($model,'sp_tolerance_llevel',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'sp_weight'); ?>
		<?php echo $form->textField($model,'sp_weight',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'sp_status'); ?>
		<?php echo $form->textField($model,'sp_status'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'sp_priority'); ?>
		<?php echo $form->textField($model,'sp_priority'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->