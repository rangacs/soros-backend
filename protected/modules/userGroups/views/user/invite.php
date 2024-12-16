<section class="main-section grid_8">
    <nav class="">
		<?php include_once( LogicalHelper::osWrapper(dirname(__FILE__) . "\..\pageDefaults\authLeftMenu.php")); ?>
    </nav>
    <div class="main-content">
        <header>
            <ul class="action-buttons clearfix">
                <li><a href="<?php echo Yii::app()->baseUrl; ?>/userGroups/user/invite" class="button" data-icon-primary="ui-icon-person" >Invite User</a></li>
                <li><a href="<?php echo Yii::app()->baseUrl; ?>/userGroups/user/index" class="button" rel="#overlay" data-icon-primary="ui-icon-copy" >User List</a></li>
            </ul>
            <h2>
                Users List
            </h2>
        </header>
        <section class="container_6 clearfix">
        
			<div class="grid_3">
				<div class="portlet">
					<header >
						<h2>Invite User</h2>
					</header>
					<section>
						<form class="form has-validation">
						<div class="clear"><br/></div>
						<div class="clear"></div>
						<div class="clearfix">
							<label for="form-email" class="form-label">
								User's Email<em>*</em>
								<small>Not username.</small>
							</label>
							<div class="form-input">
								<input type="email" id="form-email" name="email" maxlength="30"/>
							</div>
						</div>
						
						<div class="form-action clearfix">
							<button class="button ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary" type="submit" data-icon-primary="ui-icon-circle-check" role="button" aria-disabled="false">
								<span class="ui-button-text">Invite</span>
							</button>
						</div>
						<div class="clear"></div>
						<div class="clear"><br/></div>
						</form>
						<section>
				</div>
            </div>
        </section>
   </div>
</section>

	
<?php 
	/*
	echo '<div class="userGroupsMenu-container">';	
	$this->renderPartial('/admin/menu', array('mode' => 'profile', 'list' => true));
	echo '</div>';
	



	<div class="form">
		<?php $form=$this->beginWidget('CActiveForm', array(
			'id'=>'user-groups-passrequest-form',
			'enableAjaxValidation'=>true,
		)); ?>
		<div class="row">
			<?php echo $form->labelEx($model,'email'); ?>
			<?php echo $form->textField($model,'email'); ?>
			<?php echo $form->error($model,'email'); ?>
		</div>
		<div class="row buttons">
			<?php echo CHtml::submitButton(Yii::t('userGroupsModule.general','Invite User')); ?>
		</div>
		<?php $this->endWidget(); ?>
	</div>
</div>
*/
?>