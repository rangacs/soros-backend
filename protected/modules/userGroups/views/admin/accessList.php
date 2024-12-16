<?php $name = (int)$what === UserGroupsAccess::USER ? $data->username : $data->groupname; // assign to name it's value ?>
<h3>
	<?php 
	if ((int)$what === UserGroupsAccess::USER) {
		if ($id === 'new')
		{
			echo '<div class="ui-widget message closeable"><div class="ui-state-highlight ui-corner-all">'.
				 '<p><span class="ui-icon ui-icon-info"></span>'. Yii::t('userGroupsModule.admin', 'New User: Data and Access Permissions').'</p>'.
				 '</div></div>';
		}else {
			echo '<div class="ui-widget message closeable"><div class="ui-state-highlight ui-corner-all">'.
				 '<p><span class="ui-icon ui-icon-info"></span>'. Yii::t('userGroupsModule.admin', 'User {username}: Data and Access Permissions', array('{username}' => ucfirst($name))).'</p>'.
				 '</div></div>';				
		}
	} else {
		if ($id === 'new')
			{
			echo '<div class="ui-widget message closeable"><div class="ui-state-highlight ui-corner-all">'.
				 '<p><span class="ui-icon ui-icon-info"></span>'. Yii::t('userGroupsModule.admin', 'New Group: Data and Access Permissions').'</p>'.
				 '</div></div>';
		} else {
			echo '<div class="ui-widget message closeable"><div class="ui-state-highlight ui-corner-all">'.
				 '<p><span class="ui-icon ui-icon-info"></span>'. Yii::t('userGroupsModule.admin', 'Group {groupname}: Data and Access Permissions', array('{groupname}' => ucfirst($name))).'</p>'.
				 '</div></div>';				
		}
	}
	?>
</h3>
<?php $form=$this->beginWidget('bootstrap.widgets.BootActiveForm', array(
	'id'=>'user-groups-access-form-' . $what,
	'enableAjaxValidation'=>false,
	'action'=> Yii::app()->baseUrl .'/userGroups/admin',
)); ?>

<?php
$this->widget('bootstrap.widgets.BootGridView', array(
	'dataProvider'=>$dataProvider,
	'ajaxUpdate'=>false,
	'enableSorting'=>false,
	'summaryText'=>false,
	'itemsCssClass'=>'display',
	'id'=>'rule-list',
	'selectableRows'=>0,
	'columns'=>array(
		
		array(
			'name'=>'Module',
		),
		
		array(
			'name'=>'Controller',
		),
		
		array(
			'name'=>'Read',
			'type'=>'raw',
		),
		
		array(
			'name'=>'Write',
			'type'=>'raw',
		),
		
		array(
			'name'=>'Admin',
			'type'=>'raw',
		),
		
	),
));
?>
<?php 
if (Yii::app()->user->pbac('userGroups.admin.admin')) { ?>
	<div class="row">
		<?php 

		echo '<section class="container_6 clearfix"><div class="grid_4"><div class="portlet"><header><h2>Details</h2></header><section>
		<form class="form has-validation">';
					
		if ((int)$what === UserGroupsAccess::GROUP) {
/*
			echo CHtml::label(Yii::t('userGroupsModule.general','Group Level'), 'UserGroupsAccess_'.$what.'_level', array('class'=>'inline')) . CHtml::dropDownList('UserGroupsAccess['.$what.'][level]', $data->level, array_reverse(range(0,Yii::app()->user->level - 1), true));
			echo CHtml::label(Yii::t('userGroupsModule.general','Home'), 'UserGroupsAccess_'.$what.'_home', array('class'=>'inline')) . CHtml::dropDownList('UserGroupsAccess['.$what.'][home]', $data->home, UserGroupsAccess::homeList());
			echo CHtml::label(Yii::t('userGroupsModule.general','Group Name'), 'UserGroupsAccess_'.$what.'_groupname', array('class'=>'inline'));
			echo CHtml::textField('UserGroupsAccess['.$what.'][groupname]', $name);
 */ 
			echo '<div style="width:100%;height:50px;">
			<div class="clearfix" style="float:left;width:204px;">
				<label for="form-name" class="form-label">'. Yii::t('userGroupsModule.general','Group Name').'<em>*</em></label>
				<div class="form-input"><input type="text" id="UserGroupsAccess_'.$what.'_groupname" name="UserGroupsAccess['.$what.'][groupname]" required="required" /></div>
			</div>
			<div class="clearfix" style="float:left;width:204px;">
				<label for="form-birthday" class="form-label">Home-Page</label>
				<div class="form-input">'.CHtml::dropDownList('UserGroupsAccess['.$what.'][home]', $data->home,  UserGroupsAccess::homeList()).'</div>
			</div></div>
			<div style="width:100%;height:50px;">
			<div class="clearfix" style="float:left;width:204px;">
				<label for="form-email" class="form-label">'.Yii::t('userGroupsModule.general','Group Level').'<em>*</em></label>
				<div class="form-input">'.CHtml::dropDownList('UserGroupsAccess['.$what.'][level]', $data->level, array_reverse(range(0,Yii::app()->user->level - 1), true)).'</div>'.
			'</div></div>';
 
		}
		if ((int)$what === UserGroupsAccess::USER) {
/*
  			echo CHtml::label(Yii::t('userGroupsModule.general','User Name'), 'UserGroupsAccess_'.$what.'_username', array('class'=>'inline'));
			echo CHtml::textField('UserGroupsAccess['.$what.'][username]', $name);
			echo CHtml::label(Yii::t('userGroupsModule.general','Group'), 'UserGroupsAccess_'.$what.'_group_id', array('class'=>'inline')) . CHtml::dropDownList('UserGroupsAccess['.$what.'][group_id]', $data->group_id, UserGroupsGroup::groupList());
			$home_lists = UserGroupsAccess::homeList(); 
			array_unshift($home_lists, Yii::t('userGroupsModule.admin','Group Home: {home}', array('{home}'=>$data->group_home)));
			echo CHtml::label(Yii::t('userGroupsModule.general','Home'), 'UserGroupsAccess_'.$what.'_home', array('class'=>'inline')) . CHtml::dropDownList('UserGroupsAccess['.$what.'][home]', $data->home, $home_lists); 
			echo CHtml::label(Yii::t('userGroupsModule.general','Email'), 'UserGroupsAccess_'.$what.'_email', array('class'=>'inline')) . CHtml::textField('UserGroupsAccess['.$what.'][email]', $data->email);
*/
			echo '<div style="width:100%">
			<div class="clearfix" style="float:left;width:214px;">
				<label for="form-name" class="form-label">'. Yii::t('userGroupsModule.general','User Name').'<em>*</em></label>
				<div class="form-input"><input type="text" id="UserGroupsAccess_'.$what.'_username" name="UserGroupsAccess['.$what.'][username]" required="required" /></div>
			</div>
			<div class="clearfix" style="float:left;width:214px;">
				<label for="form-email" class="form-label">'.Yii::t('userGroupsModule.general','Group').'<em>*</em></label>
				<div class="form-input">'.CHtml::dropDownList('UserGroupsAccess['.$what.'][group_id]', $data->group_id, UserGroupsGroup::groupList()).'</div>'.
			'</div></div>';
			
			$home_lists = UserGroupsAccess::homeList(); 
			array_unshift($home_lists, Yii::t('userGroupsModule.admin','Group Home: {home}', array('{home}'=>$data->group_home)));
							
			echo '<div>
			<div class="clearfix" style="float:left;width:214px;">
				<label for="form-birthday" class="form-label">Home-Page</label>
				<div class="form-input">'.CHtml::dropDownList('UserGroupsAccess['.$what.'][home]', $data->home, $home_lists).'</div>
			</div>
			<div class="clearfix" style="float:left;width:214px;">
				<label for="form-name" class="form-label">'. Yii::t('userGroupsModule.general','Email').'<em>*</em></label>
				<div class="form-input"><input type="text" id="UserGroupsAccess_'.$what.'_email" name="UserGroupsAccess['.$what.'][email]" required="required" /></div>
			</div></div>
			<div>';
			
			if ($id === 'new' && (int)$what === UserGroupsAccess::USER) { 
			echo '<div class="clearfix" style="float:left;width:214px;">
				<label for="form-name" class="form-label">'. Yii::t('userGroupsModule.general','Password').'<em>*</em></label>
				<div class="form-input"><input type="password" id="UserGroupsAccess_'.$what.'_password" name="UserGroupsAccess['.$what.'][password]" required="required" /></div>
			</div>
			<div class="row">
				<p>'.Yii::t('userGroupsModule.admin','By default: Random one will be generated').'</p>
				<p>'.Yii::t('userGroupsModule.admin','By default: User will be able to chose one upon activation of the account').'</p>
			</div></div>';
			}
			

		}
		?>
	</div>
<?php } ?>

<div class="row buttons left-floated">
	<?php echo CHtml::hiddenField('UserGroupsAccess[what]', $what); ?>
	<?php echo CHtml::hiddenField('UserGroupsAccess[id]', $id); ?>
	<?php echo CHtml::hiddenField('UserGroupsAccess[displayname]', ucfirst($name)); ?>
	<?php echo CHtml::submitButton(Yii::t('userGroupsModule.general', 'Save')); ?>
</div>
<?php $this->endWidget(); ?>
<?php if ($id !== 'new' && Yii::app()->user->pbac('userGroups.admin.admin')): ?>
<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'user-groups-delete-form-' . $what,
	'enableAjaxValidation'=>false,
	'action'=> Yii::app()->baseUrl .'/userGroups/admin',
)); ?>
<div class="row buttons right-floated">
	<?php echo CHtml::hiddenField('UserGroupsAccess[what]', $what); ?>
	<?php echo CHtml::hiddenField('UserGroupsAccess[id]', $id); ?>
	<?php echo CHtml::hiddenField('UserGroupsAccess[displayname]', ucfirst($name)); ?>
	<?php echo CHtml::hiddenField('UserGroupsAccess[delete]', 'yes'); ?>
	<?php 
	if ((int)$what === UserGroupsAccess::USER)
		$confirm_message = Yii::t('userGroupsModule.admin', 'Do you really want to delete the user {user}?', array('{user}' => ucfirst($name)));
	else {
		$confirm_message = Yii::t('userGroupsModule.admin', 'Do you really want to delete the group {group}?', array('{group}' => ucfirst($name)));
		$confirm_message .= '\n'. Yii::t('userGroupsModule.admin', 'Remember if you delete a Group you\'ll delete all the users that belongs to it.');
	}
		
	echo '<div class="form-action clearfix">'.
		'<b>'.CHtml::submitButton(Yii::t('userGroupsModule.general','Delete'), array('çlass'=>'ui-button-text', 'data-icon-primary'=>"ui-icon-circle-check",'onclick' => 'js: if(confirm("'.$confirm_message.'")) {return true;}else{return false;}')).
		'</b></div>';		
	?>
</div>
<?php
		echo '</form></section></div></div></div>';
?>
<?php $this->endWidget(); ?>
<?php endif; ?>
