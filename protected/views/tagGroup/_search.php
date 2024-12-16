<div class="wide form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<div class="row">
		<?php echo $form->label($model,'tagGroupID'); ?>
		<?php echo $form->textField($model,'tagGroupID',array('size'=>4,'maxlength'=>4)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'tagGroupName'); ?>
		<?php echo $form->textField($model,'tagGroupName',array('size'=>60,'maxlength'=>120)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'rtaMasterID'); ?>
		<?php echo $form->textField($model,'rtaMasterID',array('size'=>4,'maxlength'=>4)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'massflowWeight'); ?>
		<?php echo $form->textField($model,'massflowWeight',array('size'=>1,'maxlength'=>1)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'goodDataSecondsWeight'); ?>
		<?php echo $form->textField($model,'goodDataSecondsWeight',array('size'=>1,'maxlength'=>1)); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->