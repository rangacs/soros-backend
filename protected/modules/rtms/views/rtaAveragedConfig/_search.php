<?php
/* @var $this RtaAveragedConfigController */
/* @var $model RtaAveragedConfig */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'rta_ID_averaged'); ?>
		<?php echo $form->textField($model,'rta_ID_averaged',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'rtaMasterID'); ?>
		<?php echo $form->textField($model,'rtaMasterID',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'groupAveragingID'); ?>
		<?php echo $form->textField($model,'groupAveragingID',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'massflowWeight_averagedCfg'); ?>
		<?php echo $form->textField($model,'massflowWeight_averagedCfg',array('size'=>1,'maxlength'=>1)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'goodDataSecondsWeight_averagedCfg'); ?>
		<?php echo $form->textField($model,'goodDataSecondsWeight_averagedCfg',array('size'=>1,'maxlength'=>1)); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->