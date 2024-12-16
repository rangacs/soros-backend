<?php
/* @var $this RtaTagIndexCompletedController */
/* @var $model RtaTagIndexCompletedNew */
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
		<?php echo $form->textField($model,'tagGroupID',array('size'=>4,'maxlength'=>4)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'LocalstartTime'); ?>
		<?php echo $form->textField($model,'LocalstartTime'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'LocalendTime'); ?>
		<?php echo $form->textField($model,'LocalendTime'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'goodDataSecondsWeight'); ?>
		<?php echo $form->textField($model,'goodDataSecondsWeight',array('size'=>1,'maxlength'=>1)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'massflowWeight'); ?>
		<?php echo $form->textField($model,'massflowWeight',array('size'=>1,'maxlength'=>1)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'validTag'); ?>
		<?php echo $form->textField($model,'validTag',array('size'=>3,'maxlength'=>3)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'endTic'); ?>
		<?php echo $form->textField($model,'endTic',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'startTic'); ?>
		<?php echo $form->textField($model,'startTic',array('size'=>10,'maxlength'=>10)); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'goodDataSecs'); ?>
		<?php echo $form->textField($model,'goodDataSecs'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'avgMassFlowTph'); ?>
		<?php echo $form->textField($model,'avgMassFlowTph'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'totalTons'); ?>
		<?php echo $form->textField($model,'totalTons'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'Ash'); ?>
		<?php echo $form->textField($model,'Ash'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'Sulfur'); ?>
		<?php echo $form->textField($model,'Sulfur'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'Moisture'); ?>
		<?php echo $form->textField($model,'Moisture'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'BTU'); ?>
		<?php echo $form->textField($model,'BTU'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'Na2O'); ?>
		<?php echo $form->textField($model,'Na2O'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'SO2'); ?>
		<?php echo $form->textField($model,'SO2'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'TPH'); ?>
		<?php echo $form->textField($model,'TPH'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'SiO2'); ?>
		<?php echo $form->textField($model,'SiO2'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'Al2O3'); ?>
		<?php echo $form->textField($model,'Al2O3'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'Fe2O3'); ?>
		<?php echo $form->textField($model,'Fe2O3'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'MAFBTU'); ?>
		<?php echo $form->textField($model,'MAFBTU'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'CaO'); ?>
		<?php echo $form->textField($model,'CaO'); ?>
	</div>

	<div class="row">
		<?php echo $form->label($model,'K'); ?>
		<?php echo $form->textField($model,'K'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton('Search'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- search-form -->