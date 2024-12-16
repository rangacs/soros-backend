<section class="main-section grid_8">
    <nav class="">
		<?php include_once( LogicalHelper::osWrapper(dirname(__FILE__) . "\..\pageDefaults\authLeftMenu.php")); ?>
    </nav>
    <div class="main-content">
        <section class="container_6 clearfix">
        <?php
		if($_POST["passChanged"] == "success"){
		?>
			<header><h2 class="ui-state-highlight">Password Changed Successfully !</h2></header>
		<?php
		}
		?>
			<div class="grid_3">
				<div class="portlet">
					<header class="ui-widget-header ui-corner-top">
						<h2><?php echo ('General Info'); ?></h2>
					</header>
					<section>
						
					<div class="form">
					
						<?php $form=$this->beginWidget('CActiveForm', array(
							'id'=>'user-groups-misc-form',
							'enableAjaxValidation'=>true,
							'enableClientValidation'=>true,
						)); ?>
							<p class="note">Fields with <span class="required">*</span> are required.</p>
					
							<?php if (UserGroupsConfiguration::findRule('personal_home') || Yii::app()->user->pbac(array('user.admin', 'admin.admin'))): ?>
							<div class="row clearfix">
								<?php echo $form->labelEx($miscModel,'home *'); ?>
								<?php
								$home_lists = UserGroupsAccess::homeList();
								
								array_unshift($home_lists, Yii::t('userGroupsModule.admin','Group Home: {home}', array('{home}'=>$miscModel->relUserGroupsGroup->home)));
								?>
								<?php echo $form->dropDownList($miscModel,'home', $home_lists); ?>
								<?php echo $form->error($miscModel,'home'); ?>
							</div>
							<?php endif; ?>
							<br/>
							<div class="row clearfix">
								<?php echo "Email *"; ?>
								<?php echo $form->textField($miscModel,'email',array('size'=>30,'maxlength'=>120)); ?>
								<?php echo $form->error($miscModel,'email'); ?>
							</div>
							<br/>
							
							<div class="row buttons clearfix" style="padding-left:40px;">							
								<?php echo CHtml::hiddenField('formID', $form->id) ?>
								<?php echo CHtml::ajaxSubmitButton(('Update User Profile'), Yii::app()->baseUrl . '/userGroups/user/update/id/'.$miscModel->id, array('update' => '#userGroups-container'), array('id' => 'submit-mail' . $miscModel->id.rand(),'class' => "button ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary" ) );
								?>
							</div>
							<hr/>
							<p>Please Note: If you experience any problems you can always contact admin using abhi@sabiainc.com</p>
							<hr/>
						<?php $this->endWidget(); ?>
						</div><!-- form -->						
						
					<section>
				</div>
	     </div> <!-- Grid_3 -->
	     
			<div class="grid_3">
				<div class="portlet">
					<header class="ui-widget-header ui-corner-top">
						<h2><?php echo ('Security'); ?></h2>
					</header>
					<section>
 					<div class="form">
				
					<?php $form=$this->beginWidget('CActiveForm', array(
						'id'=>'user-groups-password-form',
						'enableAjaxValidation'=>true,
						'enableClientValidation'=>true,
					)); ?>
						<p class="note">Fields with <span class="required">*</span> are required.</p>
				
						<?php if (Yii::app()->user->pbac('userGroups.user.admin') && Yii::app()->user->id !== $passModel->id) :?>
							<?php echo $form->hiddenField($passModel,'old_password', array('value'=>'filler'))?>
						<?php else: ?>
						<div class="row">
							<?php echo "<span style='margin-right:10px;'>Old Password *</span>"; ?>
							<?php echo $form->passwordField($passModel,'old_password',array('size'=>30,'maxlength'=>120)); ?>
							<?php echo $form->error($passModel,'old_password'); ?>
						</div><br/>
						<?php endif; ?>
						<div class="row">
							<?php echo "<span style='margin-right:4px;'>New Password *</span>"; ?>
							<?php echo $form->passwordField($passModel,'password',array('size'=>30,'maxlength'=>120)); ?>
							<?php echo $form->error($passModel,'password'); ?>
						</div><br/>
						<div class="row">
							<?php echo "<span style='margin-right:12px;'>Confirm Pass *</span>"; ?>
							<?php echo $form->passwordField($passModel,'password_confirm',array('size'=>30,'maxlength'=>120)); ?>
							<?php echo $form->error($passModel,'password_confirm'); ?>
						</div><hr/>
						<?php if (UserGroupsConfiguration::findRule('simple_password_reset') === false): ?>
						<div class="row">
							<?php echo "<span style='margin-right:12px;'>Question *</span>"; ?>
							<?php echo $form->textField($passModel,'question'); ?>
							<?php echo $form->error($passModel,'question'); ?>
						</div><br/>
						<div class="row">
							<?php echo "<span style='margin-right:18px;'>Answer *</span>"; ?>
							<?php echo $form->passwordField($passModel,'answer'); ?>
							<?php echo $form->error($passModel,'answer'); ?>
						</div>
						<?php endif; ?>
						<div class="row buttons clearfix" style="padding-left: 160px;" >
							<?php echo CHtml::hiddenField('formID', $form->id) ?>
							<br/>
							<?php echo CHtml::ajaxSubmitButton(('Change Password'), Yii::app()->baseUrl .'/userGroups/user/update/id/'.$passModel->id, array('update' => '#userGroups-container'), array('id' => 'submit-pass'.$passModel->id.rand(),'class' => "button ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary") ); ?>
						</div>
						
				
					<?php $this->endWidget(); ?>
					</div><!-- form -->						
					<section>
				</div>
    		 </div> <!-- Grid_3 -->            
        </section>
   </div>
</section>

