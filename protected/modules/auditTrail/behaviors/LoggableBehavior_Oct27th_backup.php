<?php

 /*
 *  --  Abhinandan.
 *   
 *    The target model (which we wish to have logged) , calls 
 *   its public 'behaviors()' method, thus bringing us here.
 *  
 *   Method: afterSave() 
 *    i.) The user clicked 'create'   OR
 *    ii.) The user clicked 'update'
 *     
 *   Method: afterDelete()
 *    i.) The user clicked 'delete'  
 *  
 *  --
 */   
class LoggableBehavior extends CActiveRecordBehavior
{
 //Let public static $primary_command go here, once the concatenation issue is fixed.. (placeholders go here also, substitution takes place inside the actual method..)
 public static $fallback_command = "SELECT c.id, c.child_key_, m._key_, c.value, m.action, m.controller FROM phaeton.tbl_audit_trail_child_template_ii AS c INNER JOIN tbl_audit_trail_master_template_ii AS m ON c.child_key_ = m._key_  AND m.controller = 'phaeton' AND m.action = 'actionPhaeton' ";

  /*
  *  
  *  afterSave() method
  *   Significance: 
  *     i.)  This method is called upon after a record has been saved (insert || update) to the model wrapper class' DB table.. 
  *     ii.) The parent {@link afterSave} event handler "wrapper" is called at the end.  
  *      * (The wrapper is used in order to call the event handler, which-in-turn raises the appropriate event) 
  *
  */      
	public function afterSave($event)
	{
   YiiBase::autoload("C:/wamp/www/phaeton_logging/protected/modules/auditTrail/models/AuditTrail");         
		try {
			$username = Yii::app()->user->Name;
			$userid = Yii::app()->user->id;
		} catch(Exception $e) {                     //If we have no user object, this must be a command line program
			$username = "NO_USER";
			$userid = null;
		}
		
		if(empty($username)) {
			$username = "NO_USER";
		}
		
		if(empty($userid)) {
			$userid = null;
		}
	
        
		$current = date('Y-m-d H:i:s');
    $controller_name = Yii::app()->controller->id;
    
    $action_name_uc = ucfirst(Yii::app()->controller->action->id);      
    $action_precursor = 'action';                                         //NOTE: Oct25th: having trouble concatenating these two variables.. then placing inside the 'select' statement..
    $action_name_uc = $action_precursor.$action_name_uc;
    
    $model_name = get_class($this->Owner);                                                    // $this->Owner refers to the model having the 'behaviors()' method (ie GatorTbl model class)..
    
		if (!$this->Owner->isNewRecord) {             //Updating..
		 $command = Yii::app()->db->createCommand("SELECT 
                                                c.id,
                                                c.child_key_,
                                                m._key_,
                                                c.value,
                                                m.action,
                                                m.controller
                                               FROM phaeton.tbl_audit_trail_child_template_ii AS c
                                               INNER JOIN tbl_audit_trail_master_template_ii AS m
                                               ON c.child_key_ = m._key_ 
                                               AND m.controller = '$controller_name' AND m.action = 'actionUpdate' ");      // Hardcoding actionCreate, having trouble with the concatenator...
                                               
     $rs = $command->query();
     
     if( count($rs) > 0 )   //If query returns true..
     {
      $short_descrip = self::buildDynamicDescrip($rs, $username);
                                                                                         //If we are creating...
		  $log=new AuditTrail();              
		  $log->message_type = 'ignorehere';           
      $log->short_descrip = $short_descrip;
      $log->long_descrip = 'A log was entered at the time of '.$current.' in which '.$username.' was interfacing with the '.$controller_name.' controller, while using the '.$action_name_uc.' action. The involved table model is: '.$model_name.'.';
      $log->controller_name = $controller_name;  
		  $log->user_id = $username;                    
      $log->user_group = 'developer';
      $log->category_id = 3;
      $log->model_name = $model_name;  
      $log->priority = 10;
      $log->category = 'ignorehere';
		  $log->timestamp = $current;	
      	
		  $log->save();                              //$log->save()..  Owner model performs this last step..
      
     }elseif( count($rs) < 1 ){    //If query returns false.. use fall-back query
       $command = Yii::app()->db->createCommand(self::$fallback_command);
       $rs = $command->query();
       
       $short_descrip = self::buildDynamicDescrip($rs, $username);
                                                                                         //If we are creating...
		   $log=new AuditTrail();              
		   $log->message_type = 'ignorehere';           
       $log->short_descrip = $short_descrip;
       $log->long_descrip = 'A log was entered at the time of '.$current.' in which '.$username.' was interfacing with the '.$controller_name.' controller, while using the '.$action_name_uc.' action. The involved table model is: '.$model_name.'.';
       $log->controller_name = $controller_name;  
		   $log->user_id = $username;                    
       $log->user_group = 'developer';
       $log->category_id = 3;
       $log->model_name = $model_name;  
       $log->priority = 10;
       $log->category = 'ignorehere';
		   $log->timestamp = $current;
       
		   $log->save();                       //$log->save()..
       
     }
     
		}else {                                   //Inserting..
     $command = Yii::app()->db->createCommand("SELECT 
                                                c.id,
                                                c.child_key_,
                                                m._key_,
                                                c.value,
                                                m.action,
                                                m.controller
                                               FROM phaeton.tbl_audit_trail_child_template_ii AS c
                                               INNER JOIN tbl_audit_trail_master_template_ii AS m
                                               ON c.child_key_ = m._key_ 
                                               AND m.controller = '$controller_name' AND m.action = 'actionCreate' ");      // Hardcoding actionCreate, having trouble with the concatenator...
                                               
     $rs = $command->query();
     
     if( count($rs) > 0 )   //If our result set has 1 or greater records..
     {
      $short_descrip = self::buildDynamicDescrip($rs, $username);
                                                                                         //If we are creating...
		  $log=new AuditTrail();              
		  $log->message_type = 'ignorehere';           
      $log->short_descrip = $short_descrip;
      $log->long_descrip = 'A log was entered at the time of '.$current.' in which '.$username.' was interfacing with the '.$controller_name.' controller, while using the '.$action_name_uc.' action. The involved table model is: '.$model_name.'.';
      $log->controller_name = $controller_name;  
		  $log->user_id = $username;                    
      $log->user_group = 'developer';
      $log->category_id = 3;
      $log->model_name = $model_name;  
      $log->priority = 10;
      $log->category = 'ignorehere';
		  $log->timestamp = $current;	
      	
		  $log->save();                              //$log->save()..  Owner model performs this last step..
      
     }elseif( count($rs) < 1 ){    //If our result set has 0 records.. use fall-back query
       $command = Yii::app()->db->createCommand(self::$fallback_command);
       $rs = $command->query();
       
       $short_descrip = self::buildDynamicDescrip($rs, $username);
                                                                                         //If we are creating...
		   $log=new AuditTrail();              
		   $log->message_type = 'ignorehere';           
       $log->short_descrip = $short_descrip;
       $log->long_descrip = 'A log was entered at the time of '.$current.' in which '.$username.' was interfacing with the '.$controller_name.' controller, while using the '.$action_name_uc.' action. The involved table model is: '.$model_name.'.';
       $log->controller_name = $controller_name;  
		   $log->user_id = $username;                    
       $log->user_group = 'developer';
       $log->category_id = 3;
       $log->model_name = $model_name;  
       $log->priority = 10;
       $log->category = 'ignorehere';
		   $log->timestamp = $current;
       
		   $log->save();                       //$log->save()..
       
     }
     		
		} //end Inserting..
		return parent::afterSave($event);          
	}//end public afterSave()..
  
  
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
     
   $short_descrip = $username." triggered a log with the following details: ";
   foreach($template_arr as $key1 => $val1){
    $short_descrip .= $key1." => ".$val1."\n"; 
   }
   return $short_descrip;
  } //end buildDynamicDescrip()..
  
  
     
	public function afterDelete($event)          //NEED to clean this method up..like the rest above...
	{
   YiiBase::autoload("C:/wamp/www/phaeton_logging/protected/modules/auditTrail/models/AuditTrail");
		try {
			$username = Yii::app()->user->Name;
			$userid = Yii::app()->user->id;
		} catch(Exception $e) {
			$username = "NO_USER";
			$userid = null;
		}

		if(empty($username)) {
			$username = "NO_USER";
		}
		
		if(empty($userid)) {
			$userid = null;
		}
		
    $current = date('Y-m-d H:i:s');
    $controller_name = Yii::app()->controller->id;
    $action_name_uc = ucfirst(Yii::app()->controller->action->id);
    //$action_name_uc_pre = 'action'.$action_name_uc;
    $model_name = get_class($this->Owner);                                                    // $this->Owner refers to the model having the 'behaviors()' method (ie GatorTbl model class)..
    
    
		$log=new AuditTrail();              
		$log->message_type = 'generic';         
		$log->short_descrip = $username." performed a ".$action_name_uc." action.";   
    $log->long_descrip = 'A log was entered at the time of '.$current.' in which '.$username.' was interfacing with the '.$controller_name.' controller, while using the '.$action_name_uc.' action. The involved table model is: '.$model_name.'.';
    $log->controller_name = $controller_name;  
		$log->user_id = $username;                    
    $log->user_group = 'developer';
    $log->category_id = 3;
    $log->model_name = $model_name;  
    $log->priority = 10;
    $log->category = 'somecategory';
		$log->timestamp = $current;
		 			
		$log->save();                                         //Last step the Owner Model( ie tblGator model instance ) performs on its db table
		
    return parent::afterDelete($event);
	}

}
?>