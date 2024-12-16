<?php

 /*
 *  --  Abhinandan.
 *   
 *    The target model (which we wish to have logged) , calls 
 *   its public 'behaviors()' method, thus bringing us here.
 *  
 *  I.
 *   a.)  
 *    Yii's default implementation is to call the appropriate "wrapper" event handler upon event invocation.
 *    If we wish to force the model wrapper class to use a specific 'customized' "wrapper" event handler,
 *     we could do so at the expense of possibly setting an option for each model wrapper class (affected by the logging module).. 
 *    
 *   b.)
 *    In the meantime, choosing to use Yii's default implementation of "wrapper" event handler invocation, upon event action.
 *   
 *   c.) Oct28th: The newest 'master' and 'child' table are more robust, in that they will afford the ability to look up an action ie 'actionCreate' for
 *     a particular model wrapper.. this is turn could be used to "pipe" the 'actionCreate' value to the appropriate custom "wrapper" event handler..
 *     
 *    
 *   NOTE: The 'autoLoad' functionality has been placed into the '../config/main.php' ...  
 *  
 *  --
 */   
class LoggableBehavior extends CActiveRecordBehavior
{
 //Let public static $primary_command go here, once the concatenation issue is fixed.. (placeholders go here also, substitution takes place inside the actual method..)
 public static $fallback_command = "SELECT c.id, c.child_key_, m._key_, c.value, c.action, c.controller FROM sabia_helios_v2_db.tbl_audit_trail_child_template_iii AS c INNER JOIN tbl_audit_trail_master_template_iii AS m ON c.child_key_ = m._key_  AND c.controller = 'PhaetonController' AND c.action = 'actionPhaeton' ";

  /*
  *  
  *  afterSave() method   (DEFAULT yii "wrapper" implementation)
  *   Significance: 
  *     i.)  This method is called upon after a record has been saved (insert || update) to the model wrapper class' DB table.. 
  *      a.) Useful for post-processing logic ie 'logging' behavior.
  *          
  *     ii.) The parent {@link afterSave} event handler "wrapper" is called at the end.  
  *      * (The wrapper is used in order to call the event handler, which-in-turn raises the appropriate event) 
  *
  *     iii.) $this->Owner refers to the model having the 'behaviors()' method (ie GatorTbl model class could be an Owner)..  
  */      
	public function afterSave($event)
	{        
    /*
    *   -- Variables made available to the current "wrapper" of the event handler. --
    *    @var  $username
    *    @var  $userid 
    *    @var  $current
    *    @var  $controller_name
    *    @var  $action_name_uc
    *    @var  $action_precursor
    *    @var  $action_name_full 
    *    @var  $model_name                               
    */        
    try{
			$username = Yii::app()->user->Name;
			$userid = Yii::app()->user->id;
		}catch(Exception $e){                     //If we have no user object, this must be a command line program
			$username = "NO_USER";
			$userid = null;
		}
		
		if( empty($username) ){
			$username = "NO_USER";
		}
		if( empty($userid) ){
			$userid = null;
		}
	            
		$current = date('Y-m-d H:i:s');                                  // 2012-10-27 19:55:26
    $controller_name = Yii::app()->controller->id;                   // gator
    $action_name_uc = ucfirst(Yii::app()->controller->action->id);   // Create   
    $action_precursor = 'action';                                    // action
    $action_name_full = $action_precursor.$action_name_uc;           // actionCreate  ... actionUpdate
    $model_name = get_class($this->Owner);                          // GatorTbl                                     ////////*******////////
    
    if(  ($this->Owner->isNewRecord) || (!$this->Owner->isNewRecord)  ){      //Inserting or updating....    

     /*
     *   We do NOT want 'UserController' have access to our 'afterSave()' "wrapper" method..
     *    Reason: usergroups will trigger this method and cause multiple records to be inserted into logging table..
     *    
     *    Solution: We are forcing usergroups to ONLY be logged when it triggers the 'afterConstruct()' "wrapper" method.. (see below)...              
     */     
     if( $controller_name !== 'user' )
     {
      $message_type    = 'ignorehere';                                                                                   
      $long_descrip    = 'longdescriphere';                                                                               
      $controller_name = $controller_name;
      $username        = $username;
      $user_group      = 'developerRR'; 
      $category_id     = 3;
      $model_name      = $model_name;
      $priority        = 10;
      $category        = 'ignorehere';
      $current         = $current;
     
      $command = Yii::app()->db->createCommand("SELECT 
                                                c.id,
                                                c.child_key_,
                                                m._key_,
                                                c.value,
                                                c.action,
                                                c.controller
                                               FROM sabia_helios_v2_db.tbl_audit_trail_child_template_iii AS c
                                               INNER JOIN tbl_audit_trail_master_template_iii AS m
                                               ON c.child_key_ = m._key_ 
                                               AND c.controller = '$controller_name' AND c.action = '$action_name_full' ");      // Oct27th...
                                               
      $rs = $command->query();
     
      if( count($rs) > 0 )   //If our result set has 1 or greater records..
      {
       $short_descrip   = self::buildDynamicDescrip($rs, $username);
       self::threadAuditTrail($message_type, $short_descrip, $long_descrip, $controller_name, $username, $user_group, $category_id, $model_name, $priority, $category, $current);
    
      }elseif( count($rs) < 1 ){    //There are no records in childTemplate and/or masterTemplate table(s).. use self::$fallback_command..      /* Dave, you could probably find a better location for this code block.. */
       $command = Yii::app()->db->createCommand(self::$fallback_command);
       $rs = $command->query();
       
       $short_descrip   = self::buildDynamicDescrip($rs, $username);
       self::threadAuditTrail($message_type, $short_descrip, $long_descrip, $controller_name, $username, $user_group, $category_id, $model_name, $priority, $category, $current);
     
      }
     		
		 } //ie if any controller OTHER THAN 'UserController.php' .. ie GatorController, etc...
		 return parent::afterSave($event);
    } //end if Insert || Update..          
	}//end public afterSave() "wrapper" event handler..
  
  
  
  /*
  *   Method afterDelete() "wrapper" event handler..
  *
  */    
  public function afterDelete($event)          
	{        
    /*
    *   -- Variables made available to the current "wrapper" of the event handler. --
    *    @var  $username
    *    @var  $userid 
    *    @var  $current
    *    @var  $controller_name
    *    @var  $action_name_uc
    *    @var  $action_precursor
    *    @var  $action_name_full 
    *    @var  $model_name                               
    */        
    try{
			$username = Yii::app()->user->Name;
			$userid = Yii::app()->user->id;
		}catch(Exception $e){                     //If we have no user object, this must be a command line program
			$username = "NO_USER";
			$userid = null;
		}
		
		if( empty($username) ){
			$username = "NO_USER";
		}
		if( empty($userid) ){
			$userid = null;
		}
	            
		$current = date('Y-m-d H:i:s');                                  // 2012-10-27 19:55:26
    $controller_name = Yii::app()->controller->id;                   // gator
    $action_name_uc = ucfirst(Yii::app()->controller->action->id);   // Create   
    $action_precursor = 'action';                                    // action
    $action_name_full = $action_precursor.$action_name_uc;           // actionDelete
    $model_name = get_class($this->Owner);                          // GatorTbl                                      
    
		$message_type    = 'ignorehere';
    $long_descrip    = 'longdescriphere';
    $controller_name = $controller_name;
    $username        = $username;
    $user_group      = 'developerR'; 
    $category_id     = 3;
    $model_name      = $model_name;
    $priority        = 10;
    $category        = 'ignorehere';
    $current         = $current;
     
    $command = Yii::app()->db->createCommand("SELECT 
                                                c.id,
                                                c.child_key_,
                                                m._key_,
                                                c.value,
                                                c.action,
                                                c.controller
                                               FROM sabia_helios_v2_db.tbl_audit_trail_child_template_iii AS c
                                               INNER JOIN tbl_audit_trail_master_template_iii AS m
                                               ON c.child_key_ = m._key_ 
                                               AND c.controller = '$controller_name' AND c.action = '$action_name_full' ");      // Oct27th...
                                               
    $rs = $command->query();
     
    if( count($rs) > 0 )   //If our result set has 1 or greater records..
    {
     $short_descrip   = self::buildDynamicDescrip($rs, $username);
     self::threadAuditTrail($message_type, $short_descrip, $long_descrip, $controller_name, $username, $user_group, $category_id, $model_name, $priority, $category, $current);
    
    }elseif( count($rs) < 1 ){    //There are no records in childTemplate and/or masterTemplate table(s).. use self::$fallback_command..      /* Dave, you could probably find a better location for this code block.. */
      $command = Yii::app()->db->createCommand(self::$fallback_command);
      $rs = $command->query();
       
      $short_descrip   = self::buildDynamicDescrip($rs, $username);
      self::threadAuditTrail($message_type, $short_descrip, $long_descrip, $controller_name, $username, $user_group, $category_id, $model_name, $priority, $category, $current);
     
    }
    return parent::afterDelete($event);
	}//end afterDelete() "wrapper" event handler..
  
  
  
  /*
  *  Abhinandan.
  *    
  *  This is an alternate way of handling actions in which the model wrapper class has
  *   no way of interacting with the db table..
  *   
  *   a.) Create a new instance of the target model wrapper .. this is done inside the controller..
  *   b.) Place behaviors() method inside the actual target model wrapper ..     
  */      
  public function afterConstruct($event)
  {
   /*
    *   -- Variables made available to the current "wrapper" of the event handler. --
    *    @var  $username
    *    @var  $userid 
    *    @var  $current
    *    @var  $controller_name
    *    @var  $action_name_uc
    *    @var  $action_precursor
    *    @var  $action_name_full 
    *    @var  $model_name                               
    */ 
    
           
    try{
			$username = Yii::app()->user->Name;
			$userid = Yii::app()->user->id;
		}catch(Exception $e){                     //If we have no user object, this must be a command line program
			$username = "NO_USER";
			$userid = null;
		}
		
		if( empty($username) ){
			$username = "NO_USER";
		}
		if( empty($userid) ){
			$userid = null;
		}
    
     
     $controller_name = Yii::app()->controller->id;                   // gator
     $action_name_uc = ucfirst(Yii::app()->controller->action->id);   // Admin
     //if( ($controller_name == 'user') && ($action_name_uc == 'Index')  )            //Handles login logging..
     //{
      $current = date('Y-m-d H:i:s');                                  // 2012-10-27 19:55:26   
      $action_precursor = 'action';                                    // action
      $action_name_full = $action_precursor.$action_name_uc;           // actionAdmin.. actionIndex.. etc.
      $model_name = get_class($this->Owner);                          // GatorTbl
                                          
		  $message_type    = 'ignorehere';
      //$long_descrip    = 'The calling controller is: '.$controller_name.', the calling action is: '.$action_name_full.'';
      $long_descrip    = $action_name_full;
      
      $controller_name = $controller_name;
      
      if($action_name_full == 'actionLogout')
      {
       $username       = $username."_loggedout";
      }else{
       $username       = $username;
      }
    
      $user_group      = 'developerU'; 
      $category_id     = 3;
      $model_name      = $model_name;
      $priority        = 10;
      $category        = 'ignorehere';
      $current         = $current;
     
      $command = Yii::app()->db->createCommand("SELECT 
                                                c.id,
                                                c.child_key_,
                                                m._key_,
                                                c.value,
                                                c.action,
                                                c.controller
                                               FROM sabia_helios_v2_db.tbl_audit_trail_child_template_iii AS c
                                               INNER JOIN tbl_audit_trail_master_template_iii AS m
                                               ON c.child_key_ = m._key_ 
                                               AND c.controller = '$controller_name' AND c.action = '$action_name_full' ");      // Oct27th...
                                               
      $rs = $command->query();
     
      if( count($rs) > 0 )   //If our result set has 1 or greater records..
      {
       $short_descrip   = self::buildDynamicDescrip($rs, $username);
       self::threadAuditTrail($message_type, $short_descrip, $long_descrip, $controller_name, $username, $user_group, $category_id, $model_name, $priority, $category, $current);
    
      }elseif( count($rs) < 1 ){  //If the Controller's actionMethod is not found.. then rely on $fallback_command (up top)..    
       $command = Yii::app()->db->createCommand(self::$fallback_command);
       $rs = $command->query();
       
       $short_descrip   = self::buildDynamicDescrip($rs, $username);
       self::threadAuditTrail($message_type, $short_descrip, $long_descrip, $controller_name, $username, $user_group, $category_id, $model_name, $priority, $category, $current);
     
      }
    // }//end if $controller_name != 'user'..
  
    return parent::afterConstruct($event);
    
  }//end afterConstruct() "wrapper" event handler method.. 
  
  
  
  /*
  *  Method: buildDynamicDescrip()
  *   @params  CDbDataReader           $rs            The result object  
  *   @params  Yii::app()->user->Name  $username      The current user
  *   @return  string                  $short_descrip Our short description
  *   
  *   Explain: The short_description is used by the logging table model wrapper..    
  */
      
  public static function buildDynamicDescrip($rs, $username){      
   $template_arr = array();
   foreach($rs as $k1 => $v1){
    if( is_array($v1) )
    {
     $template_arr = unserialize( $v1['value'] );
    }
   }
     
   //$short_descrip = $username." triggered a log with the following details: ";
   $short_descrip = "";
   foreach($template_arr as $key1 => $val1){
    //$short_descrip .= $key1." => ".$val1."\n"; 
    $short_descrip .= $key1." : ".$val1.";";
   }
   return $short_descrip;
  } //end buildDynamicDescrip()..
  
  /*
  *  Method: threadAuditTrail()
  *   @params:
  *            $message_type          
  *            $short_descrip
  *            $long_descrip
  *            $controller_name
  *            $username
  *            $user_group
  *            $category_id
  *            $model_name  
  *            $priority  
  *            $category  
  *            $current 
  *   
  *   @return  Success   return TRUE
  *   @return  Fail      throw  new Exception       
  */      
  public static function threadAuditTrail($message_type, $short_descrip, $long_descrip, $controller_name, $username, $user_group, $category_id, $model_name, $priority, $category, $current){                                                                                  
	 try{
    if( ($log=new AuditTrail())!==FALSE )
    {
     $log->message_type = $message_type;           
     $log->short_descrip = $short_descrip; 
     $log->long_descrip = $long_descrip;
     $log->controller_name = $controller_name; 
	   $log->user_id = $username;                   
     $log->user_group = $user_group;          
     $log->category_id = $category_id;                   
     $log->model_name = $model_name;           
     $log->priority = $priority;                     
     $log->category = $category;            
	   $log->timestamp = $current;	                	
	   $log->save();                     //Owner model performs this last step.. 
     return;
    }
    elseif( ($log=new AuditTrail())===FALSE )
    {
     throw new Exception('LoggableBehavior.php says: Could not create new AuditTrail thread.');
    }              
   }catch(Exception $e){
    var_dump($e->getMessage());
   }
  } //end threadAuditTrail()..
  
}
?>