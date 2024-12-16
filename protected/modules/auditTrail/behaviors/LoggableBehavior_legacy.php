<?php
class LoggableBehavior extends CActiveRecordBehavior
{
	private $_oldattributes = array();

	public function afterSave($event)
	{
   YiiBase::autoload("C:/wamp/www/phaeton_logging/protected/modules/auditTrail/models/AuditTrail");
		try {
			$username = Yii::app()->user->Name;
			$userid = Yii::app()->user->id;
		} catch(Exception $e) { //If we have no user object, this must be a command line program
			$username = "NO_USER";
			$userid = null;
		}
		
		if(empty($username)) {
			$username = "NO_USER";
		}
		
		if(empty($userid)) {
			$userid = null;
		}
	
   //By using $this->Owner, you can access the 
   // attached model's (ie gatorTbl) properties and methods..
		$newattributes = $this->Owner->getAttributes();            //getAttributes() returns all column attribute VALUES..
		
    
    $oldattributes = $this->getOldAttributes();          ////////////
		
		if (!$this->Owner->isNewRecord) {
			// compare old and new
			foreach ($newattributes as $name => $value) {
				if (!empty($oldattributes)) {
					$old = $oldattributes[$name];
				} else {
					$old = '';
				}

				if ($value != $old) {
					$log=new AuditTrail();
					$log->old_value = $old;
					$log->new_value = $value;
					$log->action = 'CHANGE';
					$log->model = get_class($this->Owner);
					$log->model_id = $this->Owner->getPrimaryKey();
					$log->field = $name;
					$log->stamp = date('Y-m-d H:i:s');
					$log->user_id = $userid;
					
					$log->save();
				}
			}
		} else {                        //else... this->Owner->isNewRecord returns TRUE... we ARE creating a new record..
    
			$log=new AuditTrail();        //Create a record as 'CREATE'...    
			$log->old_value = '';
			$log->new_value = '';
			$log->action=		'CREATE';
			$log->model=		get_class($this->Owner);
			$log->model_id=		 $this->Owner->getPrimaryKey();
			$log->field=		'N/A';
			$log->stamp= date('Y-m-d H:i:s');
			$log->user_id=		 $userid;
			
			$log->save();
			
			
			foreach ($newattributes as $name => $value) {   //Insert as many records as there are columns (in our Gator Table)..
				$log=new AuditTrail();
				$log->old_value = '';
				$log->new_value = $value;
				$log->action=		'SET';
				$log->model=		get_class($this->Owner);
				$log->model_id=		 $this->Owner->getPrimaryKey();
				$log->field=		$name;
				$log->stamp= date('Y-m-d H:i:s');
				$log->user_id=		 $userid;
				$log->save();
			}
			
			
			
		}
		return parent::afterSave($event);
	}

	public function afterDelete($event)
	{
	  //echo "LoggableBehavior::afterDelete() here!</br>";     //////////
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
		
		$log=new AuditTrail();
		$log->old_value = '';
		$log->new_value = '';
		$log->action=		'DELETE';
		$log->model=		get_class($this->Owner);
		$log->model_id=		 $this->Owner->getPrimaryKey();
		$log->field=		'N/A';
		$log->stamp= date('Y-m-d H:i:s');
		$log->user_id=		 $userid;
		$log->save();
		return parent::afterDelete($event);
	}

	public function afterFind($event)
	{
		// Save old values
		$this->setOldAttributes($this->Owner->getAttributes());
		
		return parent::afterFind($event);
	}

	public function getOldAttributes()
	{
		return $this->_oldattributes;
	}

	public function setOldAttributes($value)
	{
		$this->_oldattributes=$value;
	}
}
?>