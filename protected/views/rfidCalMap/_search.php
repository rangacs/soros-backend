<?php
/* @var $this RfidCalMapController */
/* @var $model RfidCalMap */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'wcl_acid'); ?>
		<?php echo $form->textField($model,'wcl_acid'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'wcl_item_code'); ?>
		<?php echo $form->textField($model,'wcl_item_code',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'wcl_item_namev'); ?>
		<?php echo $form->textField($model,'wcl_item_namev',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'wcl_sabia_cal_name'); ?>
		<?php echo $form->textField($model,'wcl_sabia_cal_name',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'wcl_updated'); ?>
		<?php echo $form->textField($model,'wcl_updated',array('size'=>60,'maxlength'=>200)); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->