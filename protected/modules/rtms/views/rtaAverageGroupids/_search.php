<?php
/* @var $this RtaAverageGroupidsController */
/* @var $model RtaAverageGroupids */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'recordID'); ?>
		<?php echo $form->textField($model,'recordID',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'rtaMasterID'); ?>
		<?php echo $form->textField($model,'rtaMasterID',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'groupAveragingID'); ?>
		<?php echo $form->textField($model,'groupAveragingID',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->