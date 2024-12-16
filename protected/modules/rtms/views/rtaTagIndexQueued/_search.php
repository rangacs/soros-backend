<?php
/* @var $this RtaTagIndexQueuedController */
/* @var $model RtaTagIndexQueuedNew */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'tagID'); ?>
		<?php echo $form->textField($model,'tagID',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'rtaMasterID'); ?>
		<?php echo $form->textField($model,'rtaMasterID',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'status'); ?>
		<?php echo $form->textField($model,'status',array('size'=>9,'maxlength'=>9)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'tagName'); ?>
		<?php echo $form->textField($model,'tagName',array('size'=>30,'maxlength'=>30)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'tagGroupID'); ?>
		<?php echo $form->textField($model,'tagGroupID',array('size'=>1,'maxlength'=>1)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'LocalstartTime'); ?>
		<?php echo $form->textField($model,'LocalstartTime'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'LocalendTime'); ?>
		<?php echo $form->textField($model,'LocalendTime'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->