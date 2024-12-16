
	    <div class="login-box main-content">
	      <header>
	          <ul class="action-buttons clearfix">
				
	          </ul>
	          <h2><a href="<?php echo Yii::app()->baseUrl;?>" class="noDec"> Helios-v1.0</a></h2>
	      </header>
	    	<section>
	    		<div class="ui-widget message notice">
	                <div class="ui-state-highlight ui-corner-all">
	                    <p>
	                    <span class="ui-icon ui-icon-info"></span>
	                        Please Enter your Username &amp; Password
	                    </p>
						<?php if(isset(Yii::app()->request->cookies['success'])): ?>
	                    <p>
	                    <span class="ui-icon ui-icon-info"></span>
	                    	<?php echo Yii::app()->request->cookies['success']->value; ?>
							<?php unset(Yii::app()->request->cookies['success']);?>
	                    <p>
						<?php endif; ?>
						<?php if(Yii::app()->user->hasFlash('success')):?>
	                    <p>
	                    <span class="ui-icon ui-icon-info"></span>
					        <?php echo Yii::app()->user->getFlash('success'); ?>
	                    <p>
						<?php endif; ?>
						<?php if(Yii::app()->user->hasFlash('mail')):?>
	                    <p>
	                    <span class="ui-icon ui-icon-info"></span>
					        <?php echo Yii::app()->user->getFlash('mail'); ?>
	                    <p>
						<?php endif; ?>
	                    
	                </div>
	            </div>
	    		<form id="form" action="<?php echo Yii::app()->baseUrl .'/mportal'; ?>" method="post" class="clearfix">
	                <p>
	                    <input type="text" id="username"  class="large" value="" name="UserGroupsUser[username]" required="required" placeholder="Username" />
	                    <input type="password" id="password" class="large" value="" name="UserGroupsUser[password]" required="required" placeholder="Password" />
	                    <button class="large button-gray ui-corner-all fr" type="submit">Login</button>
	                </p>
	                <p class="clearfix">
	                    <span class="fl">
	                        <input type="checkbox" id="remember" class="" value="1" name="UserGroupsUser_rememberMe"/>
	                        <label class="choice" for="remember">Remember Me?</label>
	                    </span>
	                </p>
	            </form>
	    	</section>
	    </div>