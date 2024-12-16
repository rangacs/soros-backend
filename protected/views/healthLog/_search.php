<?php
/* @var $this HealthLogController */
/* @var $model HealthLog */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'wcl_a_id'); ?>
		<?php echo $form->textField($model,'wcl_a_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'wcl_plantCode'); ?>
		<?php echo $form->textField($model,'wcl_plantCode',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'wcl_unloaderId'); ?>
		<?php echo $form->textField($model,'wcl_unloaderId',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'wcl_healthStatus'); ?>
		<?php echo $form->textField($model,'wcl_healthStatus',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'wcl_auto_tag_id'); ?>
		<?php echo $form->textField($model,'wcl_auto_tag_id',array('size'=>60,'maxlength'=>200)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'wcl_auto_tag_mCode'); ?>
		<?php echo $form->textField($model,'wcl_auto_tag_mCode',array('size'=>60,'maxlength'=>200)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'wcl_ash'); ?>
		<?php echo $form->textField($model,'wcl_ash',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'wcl_moisture'); ?>
		<?php echo $form->textField($model,'wcl_moisture',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'wcl_sulfur'); ?>
		<?php echo $form->textField($model,'wcl_sulfur',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'wcl_gcv'); ?>
		<?php echo $form->textField($model,'wcl_gcv',array('size'=>50,'maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'wcl_timestamp'); ?>
		<?php echo $form->textField($model,'wcl_timestamp',array('size'=>60,'maxlength'=>200)); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->