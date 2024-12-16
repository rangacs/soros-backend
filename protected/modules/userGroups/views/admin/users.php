<h1><?php echo Yii::t('userGroupsModule.general', 'Users'); ?></h1>
<?php if(Yii::app()->user->hasFlash('user')):
	$msg = Yii::app()->user->getFlash('user');

	if(stristr($msg, 'problems') === FALSE) {
    	$tokenFlash = 'success';
		$tokenIcon  = 'çheck';
  	}
	else {
    	$tokenFlash = 'error';
		$tokenIcon  = 'alert';
  	}
?>	
<div class="ui-widget message closeable">
    <div class="ui-state-<?php echo $tokenFlash;?> ui-corner-all"> 
        <p>
            <span class="ui-icon ui-icon-circle-<?php echo $tokenIcon;?>"></span>
            <strong><?php echo $msg; ?></strong>.
        </p>
    </div>
</div>
<?php endif; ?>
<?php $this->widget('bootstrap.widgets.BootGridView', array(
	'dataProvider'=>$userModel->search(),
	'id'=>'user-groups-user-grid',
	'enableSorting'=>false,
	'itemsCssClass'=>'display',
	'enablePagination'=>false,
	'filter'=>$userModel,
	'summaryText'=>false,
	//'selectionChanged'=>'function(id) { getPermission("'.Yii::app()->baseUrl.'", "'.UserGroupsAccess::USER.'", $.fn.yiiGridView.getSelection(id))}',
	'columns'=>array(
		'username',
		array(
			'name'=>'status',
			'value'=>'UserGroupsLookup::resolve("status",$data->status).
				((int)$data->status === UserGroupsUser::WAITING_ACTIVATION || (int)$data->status === UserGroupsUser::PASSWORD_CHANGE_REQUEST 
				? ": <b>".$data->activation_code."</b>" : NULL).
				((int)$data->status === UserGroupsUser::BANNED ? ": <b>".$data->ban."</b>" : NULL)',
			'type'=>'raw',
			'filter' => CHtml::dropDownList('UserGroupsUser[status]', $userModel->status, array_merge(array('null' => Yii::t('userGroupsModule.admin','all')), CHtml::listData(UserGroupsLookup::model()->findAll(), 'value', 'text')) ),
		),
		'group_name',	
	),
)); ?>


<?php

$timeString = time();

if (Yii::app()->user->pbac('userGroups.admin.admin')) 	
	echo '<a href="#" class="button" id="new-user-'.$timeString.'" data-icon-primary="ui-icon-person" >New User</a>';
	
?>

<script type="text/javascript">
	$(document).ready(function() {
		$("#new-user-<?php echo $timeString;?>").click(function() {
			$.ajax({
				  type: "GET",
				  url: "<?php echo Yii::app()->createUrl('/userGroups/admin/accessList', array('what'=>UserGroupsAccess::USER, 'id'=>'new')); ?>",
				}).done(function(data){
					$("#user-detail").html(data);
					$("#user-detail").dialog( "open" );
				});
		});
	});
</script>
<div id="user-detail" style="display:none;" title="Add New User" class="ui-helper-hidden"></div>


<script type="text/javascript">
// increase the default animation speed to exaggerate the effect
$.fx.speeds._default = 1000;
$(function() {
	$( "#user-detail" ).dialog({
		autoOpen: false,
		show: "fade",
		hide: "fade",
		modal:true,
		width:750,
		height:650,
		
	})
    // IE7 FIX
    .parents('.ui-dialog').find(".ui-dialog-titlebar-close").after('<div/>');

});
</script>
