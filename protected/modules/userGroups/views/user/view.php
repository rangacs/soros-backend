<section class="main-section grid_8">
    <nav class="">
		<?php include_once( LogicalHelper::osWrapper(dirname(__FILE__) . "\..\pageDefaults\authLeftMenu.php")); ?>
    </nav>
    <div class="main-content">
        <section class="container_6 clearfix">
       
			<div class="grid_3">
				<div class="portlet">
					<header class="ui-widget-header ui-corner-top">
						<h2><strong><?php echo Yii::app()->user->name;?></strong> - 's Profile</h2>
					</header>
					<section>

						<form method="GET" class="form has-validation" action="<?php echo Yii::app()->createUrl('/userGroups/user/update'); ?>" >
						<div class="clear"><br/></div>
						<div class="clear"></div>
						<input type="hidden" name="id" value="<?php echo Yii::app()->user->id; ?>"/>
						<?php if(Yii::app()->user->hasFlash('user')):?>
					    <div class="info">
					        <?php echo Yii::app()->user->getFlash('user'); ?>
					    </div>
						<?php endif; ?>
						<?php if(Yii::app()->user->hasFlash('mail')):?>
					    <div class="info">
					        <?php echo Yii::app()->user->getFlash('mail'); ?>
					    </div>
						<?php endif; ?>
						<?php $this->renderPartial('/user/_view', array('data' => $model, 'profiles' => $profiles))?>

						<br/>
						<button class="button ui-button ui-widget ui-state-default ui-corner-all ui-button-text-icon-primary" type="submit" data-icon-primary="ui-icon-circle-check" role="button" aria-disabled="false">
							<span class="ui-button-text">Edit Profile</span>
						</button>
						<div class="clear"><br/></div>
						</form>
						<section>
				</div>
            </div>
            
        </section>
   </div>
</section>