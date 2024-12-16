<?php Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl."/css/bootstrap.min.css"); ?>
<section class="main-section grid_8">
    <nav class="">
		<?php 
				$this->renderPartial('/pageDefaults/authLeftMenu'); 
		?>
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
        
            <div class="grid_6">
				<?php 
				//zii.widgets.grid.CGridView will provide the basic grid view
				//bootstrap.widgets.BootGridView uses bootstrap
				$this->widget('zii.widgets.grid.CGridView', array(
				'id'=>'user-groups-user-grid',
				'itemsCssClass'=>'display',
				'dataProvider'=>$model->search(),
				'filter'=>$model,
				'selectableRows'=>0,
				'columns'=>array(
					array(
						'name'=>'username',
						'value'=> Yii::app()->user->pbac('userGroups.user.admin') || Yii::app()->user->pbac('userGroups.admin.admin') ? 
							'CHtml::link($data->username, Yii::app()->baseUrl ."/userGroups?u=".$data->username)' : '$data->username',
						'type'=>'raw',
					),
					'group_name',
					array(
						'name'=>'email',
						'visible'=>Yii::app()->user->pbac('userGroups.user.admin'),
					),
					array(
						'name'=>'readable_home',
						'type'=>'raw',
						'visible'=>Yii::app()->user->pbac('userGroups.user.admin'),
					),
					array(
						'name'=>'status',
						'value'=>'UserGroupsLookup::resolve("status",$data->status)',
						'visible'=>Yii::app()->user->pbac('userGroups.user.admin'),
						'filter' => CHtml::dropDownList('UserGroupsUser[status]', $model->status, array_merge(array('null' => Yii::t('userGroupsModule.admin','all')), CHtml::listData(UserGroupsLookup::model()->findAll(), 'value', 'text')) ),
					)
					/*
					'group_id',
					'password',
					'id',
					'access',
					'salt',
					,
					'activation_code',
					'activation_time',
					'last_login',
					'ban',
					*/
					),
				)); ?>            	
            </div>
        </section>
   </div>
</section>
<script type="text/javascript">
	$(document).ready(function() {
		
		$("#user-groups-user-grid table").removeClass("items");
		//$("#user-groups-user-grid table").addClass("display");
		var cWidth = $(".grid_6").css("width");

	});
	
</script>
