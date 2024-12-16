<?php
/* @var $this ProductProfileController */
/* @var $model ProductProfile */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'product_id'); ?>
		<?php echo $form->textField($model,'product_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'user_id'); ?>
		<?php echo $form->textField($model,'user_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'product_name'); ?>
		<?php echo $form->textField($model,'product_name',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'created_on'); ?>
		<?php echo $form->textField($model,'created_on'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'updated_on'); ?>
		<?php echo $form->textField($model,'updated_on'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'target_flow'); ?>
		<?php echo $form->textField($model,'target_flow'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'max_flow_deviation'); ?>
		<?php echo $form->textField($model,'max_flow_deviation'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'estimate_lsq_mins'); ?>
		<?php echo $form->textField($model,'estimate_lsq_mins'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'sensitivity'); ?>
		<?php echo $form->textField($model,'sensitivity'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'control_period_mins'); ?>
		<?php echo $form->textField($model,'control_period_mins'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'actual_fpm'); ?>
		<?php echo $form->textField($model,'actual_fpm'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'actual_tph'); ?>
		<?php echo $form->textField($model,'actual_tph'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'default_profile'); ?>
		<?php echo $form->textField($model,'default_profile'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'status'); ?>
		<?php echo $form->textField($model,'status',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'comment'); ?>
		<?php echo $form->textField($model,'comment',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->