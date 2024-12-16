<section class="main-section grid_8">
    <nav class="">
		<?php include_once(LogicalHelper::osWrapper( dirname(__FILE__) . "\..\pageDefaults\authLeftMenu.php")); ?>
    </nav>
    <div class="main-content">
        <header>
            <ul class="action-buttons clearfix">
                <li><a href="<?php echo Yii::app()->baseUrl; ?>/userGroups/user/invite" class="button" data-icon-primary="ui-icon-person" >Invite User</a></li>
                <li><a href="<?php echo Yii::app()->baseUrl; ?>/userGroups/user/index" class="button" rel="#overlay" data-icon-primary="ui-icon-copy" >User List</a></li>
            </ul>
            <h2>
                Admin Tools
            </h2>
        </header>
        <section class="container_6 clearfix">
             <div class="grid_6">
				<div id="userGroups-container">
					<?php if ((int)Yii::app()->user->id === UserGroupsUser::ROOT && UserGroupsConfiguration::findRule('version') < UserGroupsInstallation::VERSION): ?>
					<div class="info">
						<?php echo CHtml::link(Yii::t('userGroupsModule.admin','click here update userGroups'), array('admin/update')); ?>
					</div>
					<?php endif; ?>

					<?php if (!UserGroupsConfiguration::findRule('dumb_admin') || Yii::app()->user->pbac('admin')): ?>
					<?php $this->renderPartial('configurations', array('confDataProvider'=>$confDataProvider))?>
					<hr/>
					<?php $this->renderPartial('crons', array('cronDataProvider'=>$cronDataProvider))?>
					<hr/>
					<?php endif; ?>
					<?php $this->renderPartial('groups', array('groupModel'=>$groupModel))?>
					<hr/>
					<?php $this->renderPartial('users', array('userModel'=>$userModel))?>
				</div>
            	
            </div>
        </section>
   </div>
</section>