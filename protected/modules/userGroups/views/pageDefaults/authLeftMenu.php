<a class="chevron" href="#">»</a>
<?php
                 $li_arr = array(
                
				               0 => array(
				                      'id'     => "leftMenuItemdash",
				                      'title'  => "",
				                      'class'  => "",
				                      'a_href' => array(
					     	                            'path'  => "/dash",
						                                'class' => "navicon-house"
						                                
						                  ),
						 
						                  'name'   => "Dashboard"
				               ),

				               1 => array(
				                      'id'     => "leftMenuItemsettings",
				                      'title'  => "",
				                      'class'  => "",
				                      'a_href' => array(
					     	                            'path'  => "/dash/create",
						                                'class' => "navicon-photos"
						                                
						                  ),
						 
						                  'name'   => "Layouts"
				               ),
                 			2 => array(
				                      'id'     => "leftMenuItemtheme",
				                      'title'  => "",
				                      'class'  => "",
				                      'a_href' => array(
					     	                            'path'  => "/dash/tagging",
						                                'class' => "navicon-cabinet"
						                                
						                  ),
						 
						                  'name'   => "Tag-Data"
				               ),
                 			3 => array(
				                      'id'     => "leftMenuItemtheme",
				                      'title'  => "",
				                      'class'  => "",
				                      'a_href' => array(
					     	                            'path'  => "/dash/theme",
						                                'class' => "navicon-location"
						                                
						                  ),
						 
						                  'name'   => "Settings"
				               ),

				               4 => array(
				                      'id'     => "",
				                      'title'  => "",
				                      'class'  => "",
				                      'a_href' => array(
						                                'path'  => "/site/logout",
						                                'class' => "navicon-id-card" 
						                  ),
						 
						                  'name'   => "Logout"
				               )
				  
	             );
	  

                 $tli_arr = array(
                
				               0 => array(
				                      'id'     => "leftMenuItemdash",
				                      'title'  => "",
				                      'class'  => "",
				                      'a_href' => array(
					     	                            'path'  => "/dash",
						                                'class' => "navicon-house"
						                                
						                  ),
						 
						                  'name'   => "Dashboard"
				               ),

				               1 => array(
				                      'id'     => "",
				                      'title'  => "",
				                      'class'  => "",
				                      'a_href' => array(
						                                'path'  => "/site/logout",
						                                'class' => "navicon-id-card" 
						                  ),
						 
						                  'name'   => "Logout"
				               )
				  
	             );
	
				   
                ?>
                
                
                <!-- Abhinandan. This is what renders the Left Side Bar Menu -->
                
                
                <ul id="bodyLeftMenu"> 
                 <?php 
                       //Abhinandan. Redirect to login screen if the user = guest..
                       if( Yii::app()->user->isGuest):
                        $this->redirect(Yii::app()->createUrl('/userGroups/user/login'));
                       endif;
                       
                       if( !isset($left_menu_arr) ):
                        $left_menu_arr = $li_arr;
                       endif;
                 ?>
                
                 <?php foreach($li_arr as $k1 => $v1): //foreach($left_menu_arr as $k1 => $v1): ?>    <!-- 12/27.. left_menu_arr -->
                  <?php if( $v1['a_href']['path'] != "#" ): ?>
                   <?php       $full_path = Yii::app()->baseUrl . $v1['a_href']['path']; ?>
                   <?php else: $full_path = $v1['a_href']['path']; ?>
                  <?php endif; ?>
                  <li id="<?php echo $v1['id']; ?>" title="<?php echo $v1['title']; ?>" class="<?php echo $v1['class']; ?>" >
                   <a href="<?php echo $full_path ?>" class="<?php echo $v1['a_href']['class']; ?>" >
                    <?php echo $v1['name']; ?>
                   </a>
                   <?php if( isset($v1['userIndex']) ): ?>
                   	<ul class="<?php echo $v1['userIndex']['ul_class']; ?>">
                   	 <?php foreach($v1['userIndex']['sub_li_arr'] as $k_1 => $v_1 ): ?>
                   	  <li>
                   	   <a href="<?php echo Yii::app()->baseUrl . $v_1['a_href']; ?>" class="<?php echo $v_1['class']; ?>" ><?php echo $v_1['subname']; ?></a>
                   	  </li> 
                   	 <?php endforeach; ?>	
                   	</ul>
                   <?php endif; ?>
                  </li>
                 <?php endforeach; ?>
                </ul>                 