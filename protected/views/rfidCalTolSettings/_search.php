<?php
/* @var $this RfidCalTolSettingsController */
/* @var $model RfidCalTolSettings */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'wcl_cal_tol_id'); ?>
		<?php echo $form->textField($model,'wcl_cal_tol_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'wcl_cal_tol_item_code'); ?>
		<?php echo $form->textField($model,'wcl_cal_tol_item_code',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'Moisture'); ?>
		<?php echo $form->textField($model,'Moisture'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'Moisture_Tol'); ?>
		<?php echo $form->textField($model,'Moisture_Tol'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'Ash'); ?>
		<?php echo $form->textField($model,'Ash'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'Ash_Tol'); ?>
		<?php echo $form->textField($model,'Ash_Tol'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'Sulfur'); ?>
		<?php echo $form->textField($model,'Sulfur'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'Sulfur_Tol'); ?>
		<?php echo $form->textField($model,'Sulfur_Tol'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'GCV'); ?>
		<?php echo $form->textField($model,'GCV'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'GCV_Tol'); ?>
		<?php echo $form->textField($model,'GCV_Tol'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'BTU'); ?>
		<?php echo $form->textField($model,'BTU'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'BTU_Tol'); ?>
		<?php echo $form->textField($model,'BTU_Tol'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'updated_on'); ?>
		<?php echo $form->textField($model,'updated_on',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->