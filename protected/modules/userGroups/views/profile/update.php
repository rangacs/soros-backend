<h2><?php echo Yii::t('userGroupsModule.general', 'External Profile'); ?></h2>
<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'user-groups-profile-form',
	'enableAjaxValidation'=>true,
)); ?>
	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<div class="row">
		<?php echo $form->labelEx($model,'hobbies'); ?>
		<?php echo $form->textField($model,'hobbies',array('size'=>30,'maxlength'=>60)); ?>
		<?php echo $form->error($model,'hobbies'); ?>
	</div>

	<div class="row buttons clearfix" style="padding-left:40px;">	
		<?php echo CHtml::ajaxSubmitButton(Yii::t('userGroupsModule.general','Update External Profile'), Yii::app()->baseUrl . '/userGroups/user/update/id/'.$user_id, array('update' => '#userGroups-container'), array('id' => 'submit-profile-'.$model->id.rand(), ,'class' => "button ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary") ); 
		?>
	</div>

<?php $this->endWidget(); ?>
</div>

<h2><?php echo Yii::t('userGroupsModule.general', 'Avatar'); ?></h2>
<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'user-groups-avatar-form',
	'action'=>array('/profile/load'),
	'htmlOptions'=> array(
		'enctype'=>'multipart/form-data',
	)
)); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'avatar'); ?>
		<?php echo CHtml::activeFileField($model,'avatar'); ?>
		<?php echo $form->error($model,'avatar'); ?>
	</div>
	<div class="row">
		<p>you can just upload images.</p>
	</div>

	<div class="row buttons">
		<?php echo CHtml::submitButton(Yii::t('userGroupsModule.general','load avatar')); ?>
	</div>

<?php $this->endWidget(); ?>
</div>