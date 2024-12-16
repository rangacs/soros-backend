<h1><?php echo Yii::t('userGroupsModule.general', 'Groups'); ?></h1>
<?php if(Yii::app()->user->hasFlash('group')):
	$msg = Yii::app()->user->getFlash('group');

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
	'dataProvider'=>$groupModel->search(),
	'id'=>'user-groups-group-grid',
	'enableSorting'=>false,
	'itemsCssClass'=>'display',
	'filter'=>$groupModel,
	'summaryText'=>false,
	//'selectionChanged'=> 'function(id) { getPermission("'.Yii::app()->baseUrl.'", "'.UserGroupsAccess::GROUP.'", $.fn.yiiGridView.getSelection(id))}',
	'columns'=>array(
		'groupname',
		'level',
	),
)); ?>

<?php

$timeString = time();

if (Yii::app()->user->pbac('userGroups.admin.admin')) 	
	echo '<a href="#" class="button" id="new-group-'.$timeString.'" data-icon-primary="ui-icon-person" >New Group</a>';
?>
<script type="text/javascript">
	$(document).ready(function() {
		$("#new-group-<?php echo $timeString;?>").click(function() {
			$.ajax({
				  type: "GET",
				  url: "<?php echo Yii::app()->createUrl('/userGroups/admin/accessList', array('what'=>UserGroupsAccess::GROUP, 'id'=>'new')); ?>",
				}).done(function(data){
					$("#group-detail").html(data);
					$("#group-detail").dialog( "open" );
				});
		});
	});
</script>

<div id="group-detail" style="display:none;" title="Add New Group" class="ui-helper-hidden"></div>


<script type="text/javascript">
// increase the default animation speed to exaggerate the effect
$.fx.speeds._default = 1000;
$(function() {
	$( "#group-detail" ).dialog({
		autoOpen: false,
		show: "fade",
		hide: "fade",
		modal:true,
		width:750,
		height:400,
		
	})
    // IE7 FIX
    .parents('.ui-dialog').find(".ui-dialog-titlebar-close").after('<div/>');

});
</script>
