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
                Users List
            </h2>
        </header>
        <section class="container_6 clearfix" >
            <div class="grid_6">
                <div class="accordion clearfix selectorAc">
                    <header class="current"><a href="#">First Steps</a></header>
                    <section style="display:block" >
                    	<?php echo $this->renderPartial('documentation/first_steps', null, true); ?>
                    </section>

                    <header><a href="#">Setup the new accessControlFilter</a></header>
                    <section>
                    	<?php echo $this->renderPartial('documentation/access_rules', null, true); ?>
                    </section>

                    <header><a href="#">Using the new Access Rules</a></header>
                    <section>
                    	<?php echo $this->renderPartial('documentation/access_rules_2', null, true); ?>
                    </section>
                                        
                    <header><a href="#">Root Tools</a></header>
                    <section>
                    	<?php echo $this->renderPartial('documentation/root_tools', null, true); ?>
                    </section>
                                        
                    <header><a href="#">Cron Jobs</a></header>
                    <section>
                    	<?php echo $this->renderPartial('documentation/cron_jobs', null, true); ?>
                    </section>
                                        
                    <header><a href="#">Profile Extensions</a></header>
                    <section>
                    	<?php echo $this->renderPartial('documentation/profile', null, true); ?>
                    </section>
                                                                                
                    <header><a href="#">Email Customization</a></header>
                    <section>
                    	<?php echo $this->renderPartial('documentation/email', null, true); ?>
                    </section>
                                        
                    <header><a href="#">Localization</a></header>
                    <section>
                    	<?php echo $this->renderPartial('documentation/localization', null, true); ?>
                    </section>                    
            </div>
        </section>
   </div>
</section>