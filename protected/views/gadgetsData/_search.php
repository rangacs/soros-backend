<?php
/* @var $this GadgetsDataController */
/* @var $model GadgetsData */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'gadget_data_id'); ?>
		<?php echo $form->textField($model,'gadget_data_id',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'gadget_type'); ?>
		<?php echo $form->textField($model,'gadget_type',array('size'=>20,'maxlength'=>20)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'lay_id'); ?>
		<?php echo $form->textField($model,'lay_id',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'widgetsPos'); ?>
		<?php echo $form->textField($model,'widgetsPos',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'gadget_name'); ?>
		<?php echo $form->textField($model,'gadget_name',array('size'=>30,'maxlength'=>30)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'gadget_size'); ?>
		<?php echo $form->textField($model,'gadget_size',array('size'=>6,'maxlength'=>6)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'last_updated'); ?>
		<?php echo $form->textField($model,'last_updated'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'data_source'); ?>
		<?php echo $form->textArea($model,'data_source',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'detector_source'); ?>
		<?php echo $form->textArea($model,'detector_source',array('rows'=>6, 'cols'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'group_style'); ?>
		<?php echo $form->textField($model,'group_style',array('size'=>60,'maxlength'=>200)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'display_style'); ?>
		<?php echo $form->textField($model,'display_style',array('size'=>60,'maxlength'=>200)); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->