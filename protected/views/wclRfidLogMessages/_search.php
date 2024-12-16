<?php
/* @var $this WclRfidLogMessagesController */
/* @var $model WclRfidLogMessages */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'message_type'); ?>
		<?php echo $form->textField($model,'message_type',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'long_descrip'); ?>
		<?php echo $form->textField($model,'long_descrip',array('size'=>60,'maxlength'=>255)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'trip_id'); ?>
		<?php echo $form->textField($model,'trip_id',array('size'=>60,'maxlength'=>75)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'unloaderID'); ?>
		<?php echo $form->textField($model,'unloaderID',array('size'=>50,'maxlength'=>50)); ?>
	</div>
	<div class="row">
		<?php echo $form->label($model,'timestamp'); ?>
		<?php echo $form->textField($model,'timestamp'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->