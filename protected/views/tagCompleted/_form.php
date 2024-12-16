<?php
/* @var $this TagCompletedController */
/* @var $model TagCompleted */
/* @var $form CActiveForm */
?>

<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'tag-completed-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'rtaMasterID'); ?>
		<?php echo $form->textField($model,'rtaMasterID',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'rtaMasterID'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'status'); ?>
		<?php echo $form->textField($model,'status',array('size'=>9,'maxlength'=>9)); ?>
		<?php echo $form->error($model,'status'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tagName'); ?>
		<?php echo $form->textField($model,'tagName',array('size'=>30,'maxlength'=>30)); ?>
		<?php echo $form->error($model,'tagName'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'tagGroupID'); ?>
		<?php echo $form->textField($model,'tagGroupID',array('size'=>4,'maxlength'=>4)); ?>
		<?php echo $form->error($model,'tagGroupID'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'LocalstartTime'); ?>
		<?php echo $form->textField($model,'LocalstartTime'); ?>
		<?php echo $form->error($model,'LocalstartTime'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'LocalendTime'); ?>
		<?php echo $form->textField($model,'LocalendTime'); ?>
		<?php echo $form->error($model,'LocalendTime'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'goodDataSecondsWeight'); ?>
		<?php echo $form->textField($model,'goodDataSecondsWeight',array('size'=>1,'maxlength'=>1)); ?>
		<?php echo $form->error($model,'goodDataSecondsWeight'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'massflowWeight'); ?>
		<?php echo $form->textField($model,'massflowWeight',array('size'=>1,'maxlength'=>1)); ?>
		<?php echo $form->error($model,'massflowWeight'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'validTag'); ?>
		<?php echo $form->textField($model,'validTag',array('size'=>3,'maxlength'=>3)); ?>
		<?php echo $form->error($model,'validTag'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'endTic'); ?>
		<?php echo $form->textField($model,'endTic',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'endTic'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'startTic'); ?>
		<?php echo $form->textField($model,'startTic',array('size'=>10,'maxlength'=>10)); ?>
		<?php echo $form->error($model,'startTic'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'goodDataSecs'); ?>
		<?php echo $form->textField($model,'goodDataSecs'); ?>
		<?php echo $form->error($model,'goodDataSecs'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'avgMassFlowTph'); ?>
		<?php echo $form->textField($model,'avgMassFlowTph'); ?>
		<?php echo $form->error($model,'avgMassFlowTph'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'totalTons'); ?>
		<?php echo $form->textField($model,'totalTons'); ?>
		<?php echo $form->error($model,'totalTons'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'Ash'); ?>
		<?php echo $form->textField($model,'Ash'); ?>
		<?php echo $form->error($model,'Ash'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'Sulfur'); ?>
		<?php echo $form->textField($model,'Sulfur'); ?>
		<?php echo $form->error($model,'Sulfur'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'Moisture'); ?>
		<?php echo $form->textField($model,'Moisture'); ?>
		<?php echo $form->error($model,'Moisture'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'BTU'); ?>
		<?php echo $form->textField($model,'BTU'); ?>
		<?php echo $form->error($model,'BTU'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'Na2O'); ?>
		<?php echo $form->textField($model,'Na2O'); ?>
		<?php echo $form->error($model,'Na2O'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'SO2'); ?>
		<?php echo $form->textField($model,'SO2'); ?>
		<?php echo $form->error($model,'SO2'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'TPH'); ?>
		<?php echo $form->textField($model,'TPH'); ?>
		<?php echo $form->error($model,'TPH'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'SiO2'); ?>
		<?php echo $form->textField($model,'SiO2'); ?>
		<?php echo $form->error($model,'SiO2'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'Al2O3'); ?>
		<?php echo $form->textField($model,'Al2O3'); ?>
		<?php echo $form->error($model,'Al2O3'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'Fe2O3'); ?>
		<?php echo $form->textField($model,'Fe2O3'); ?>
		<?php echo $form->error($model,'Fe2O3'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'TEST'); ?>
		<?php echo $form->textField($model,'TEST'); ?>
		<?php echo $form->error($model,'TEST'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'CAL_ID'); ?>
		<?php echo $form->textField($model,'CAL_ID'); ?>
		<?php echo $form->error($model,'CAL_ID'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'MAFBTU'); ?>
		<?php echo $form->textField($model,'MAFBTU'); ?>
		<?php echo $form->error($model,'MAFBTU'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'CaO'); ?>
		<?php echo $form->textField($model,'CaO'); ?>
		<?php echo $form->error($model,'CaO'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'MgO'); ?>
		<?php echo $form->textField($model,'MgO'); ?>
		<?php echo $form->error($model,'MgO'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'K2O'); ?>
		<?php echo $form->textField($model,'K2O'); ?>
		<?php echo $form->error($model,'K2O'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'TiO2'); ?>
		<?php echo $form->textField($model,'TiO2'); ?>
		<?php echo $form->error($model,'TiO2'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'Mn2O3'); ?>
		<?php echo $form->textField($model,'Mn2O3'); ?>
		<?php echo $form->error($model,'Mn2O3'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'P2O5'); ?>
		<?php echo $form->textField($model,'P2O5'); ?>
		<?php echo $form->error($model,'P2O5'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'SO3'); ?>
		<?php echo $form->textField($model,'SO3'); ?>
		<?php echo $form->error($model,'SO3'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'Cl'); ?>
		<?php echo $form->textField($model,'Cl'); ?>
		<?php echo $form->error($model,'Cl'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'LOI'); ?>
		<?php echo $form->textField($model,'LOI'); ?>
		<?php echo $form->error($model,'LOI'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'LSF'); ?>
		<?php echo $form->textField($model,'LSF'); ?>
		<?php echo $form->error($model,'LSF'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'SM'); ?>
		<?php echo $form->textField($model,'SM'); ?>
		<?php echo $form->error($model,'SM'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'IM'); ?>
		<?php echo $form->textField($model,'IM'); ?>
		<?php echo $form->error($model,'IM'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'C4AF'); ?>
		<?php echo $form->textField($model,'C4AF'); ?>
		<?php echo $form->error($model,'C4AF'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'NAEQ'); ?>
		<?php echo $form->textField($model,'NAEQ'); ?>
		<?php echo $form->error($model,'NAEQ'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'C3S'); ?>
		<?php echo $form->textField($model,'C3S'); ?>
		<?php echo $form->error($model,'C3S'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'C3A'); ?>
		<?php echo $form->textField($model,'C3A'); ?>
		<?php echo $form->error($model,'C3A'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'SourceDeployed'); ?>
		<?php echo $form->textField($model,'SourceDeployed'); ?>
		<?php echo $form->error($model,'SourceDeployed'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'SourceStored'); ?>
		<?php echo $form->textField($model,'SourceStored'); ?>
		<?php echo $form->error($model,'SourceStored'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'K'); ?>
		<?php echo $form->textField($model,'K'); ?>
		<?php echo $form->error($model,'K'); ?>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->