<?php
/* @var $this SourceController */
/* @var $model Source */
/* @var $form CActiveForm */
?>

<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'src_id'); ?>
		<?php echo $form->textField($model,'src_id'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'src_name'); ?>
		<?php echo $form->textField($model,'src_name',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'src_type'); ?>
		<?php echo $form->textField($model,'src_type',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'product_id'); ?>
		<?php echo $form->textField($model,'product_id',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'src_priority'); ?>
		<?php echo $form->textField($model,'src_priority',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'src_distance'); ?>
		<?php echo $form->textField($model,'src_distance',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'src_delay'); ?>
		<?php echo $form->textField($model,'src_delay',array('size'=>60,'maxlength'=>100)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'src_min_feedrate'); ?>
		<?php echo $form->textField($model,'src_min_feedrate',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'src_max_feedrate'); ?>
		<?php echo $form->textField($model,'src_max_feedrate',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'src_proposed_feedrate'); ?>
		<?php echo $form->textField($model,'src_proposed_feedrate',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'src_measured_feedrate'); ?>
		<?php echo $form->textField($model,'src_measured_feedrate',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'src_cost'); ?>
		<?php echo $form->textField($model,'src_cost',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'src_status'); ?>
		<?php echo $form->textField($model,'src_status',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->