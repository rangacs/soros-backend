<?php

/**
 * UserGroupsIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 *
 * @author Nicola Puddu
 * @package userGroups
 */
 
 /*
 *   -- Abhinandan. --
 *   Note that the parent constructor is automatically invoked by Yii, 
 *    although is is not explicitly invoked within this class.
 *    
 *    i.) The parent constructor sets:
 *         $this->username = $username;    //Original username
 *         $this->password = $password;    //Original password (NON-hashed)   
 *
 */  
class UserGroupsIdentity extends CUserIdentity
{
	/**
	 * @var int $id the user id
	 */
	private $id;
	/**
	 * @var string $name the username
	 */
	private $name;
	/**
	 * @var string $group the group id of the user
	 */
	private $group;
	/**
	 * @var string $groupName the group name of the user
	 */
	private $groupName;
	/**
	 * @var array $access contains the user access restrictions
	 */
	private $accessRules;
	/**
	 * @var string $home contains the home of the user
	 */
	private $home;
	/**
	 * @var int $level level of the user group
	 */
	private $level;
	/**
	 * @var bool $recovery states if the user is logged in recovery mode
	 */
	private $recovery;
	/**
	 * @var array contains the profile extensions attributes stored in session
	 */
	private $profile;
	/**
	 * these constants rappresent new possible errors
	 * @var int
	 */
	const ERROR_USER_BANNED = 3;
	const ERROR_USER_INACTIVE = 4;
	const ERROR_USER_APPROVAL = 5;
	const ERROR_PASSWORD_REQUESTED = 6;
	const ERROR_USER_ACTIVE = 7;
	const ERROR_ACTIVATION_CODE = 8;

	/**
	 * Authenticates a user.
	 * @return boolean whether authentication succeeds.
	 * 
	 * Abhinandan - This is how you get your md5'ed password.. 
	 *  IMPORTANT: The usergroups_user Table MUST have the 'creation_date'
	 *             field set to a legitimate value (ie copy other users)..
	 *              else the $model->getSalt() will not work.      
	 */
	public function authenticate()
	{
    //Controller::fb("authenticate() here!");   //DEBUGGING from UserGroupsUser::login()..
    
		$model=UserGroupsUser::model()->findByAttributes(array('username' => $this->username));
     
		if(!count($model)){
     //Controller::fb("a");
     $this->errorCode=self::ERROR_USERNAME_INVALID;
    }	
		else if((int)$model->status === UserGroupsUser::WAITING_ACTIVATION){
     //Controller::fb("b");
     $this->errorCode=self::ERROR_USER_INACTIVE;
    }
    else if($model->password!==md5($this->password . $model->getSalt())){
     //Controller::fb("c");   //DEBUGGING from UserGroupsUser::login()..
     //Controller::fb("model->password should be encrypted, it is " . $model->password);
     //Controller::fb("this->password is " . $this->password);
     //Controller::fb("model->getSalt returned " . $model->getSalt());
     //Controller::fb("The md5'ed this->password PLUS model->getSalt() is " . md5($this->password . $model->getSalt()) );
     $this->errorCode=self::ERROR_PASSWORD_INVALID;
    }
			
		else if((int)$model->status === UserGroupsUser::WAITING_APPROVAL){
     //Controller::fb("d");
     $this->errorCode=self::ERROR_USER_APPROVAL;
    }	
		else if((int)$model->status === UserGroupsUser::BANNED){
     //Controller::fb("e");
     $this->errorCode=self::ERROR_USER_BANNED;
    }	
		else if((int)$model->status === UserGroupsUser::PASSWORD_CHANGE_REQUEST){
     //Controller::fb("f");
     $this->errorCode=self::ERROR_PASSWORD_REQUESTED;
    }	
		else{
     //Controller::fb("g");
			$this->errorCode=self::ERROR_NONE;
			$this->id = $model->id;
			$this->name = $model->username;
			$this->group = $model->group_id;
			$this->groupName = $model->relUserGroupsGroup->groupname;
			$this->level = $model->relUserGroupsGroup->level;
			$this->accessRules = $this->accessRulesComputation($model);
			$this->home = $model->home ? $model->home : $model->relUserGroupsGroup->home;
			$this->recovery = false;
			// load profile extension's data
			$this->profileLoad($model);
			// update the last login time
			$model->last_login = date('Y-m-d H:i:s');
			// run the cronjobs
			if (UserGroupsConfiguration::findRule('server_executed_crons') === false) {
				UGCron::init();
				UGCron::add(new UGCJGarbageCollection);
				UGCron::add(new UGCJUnban);
				foreach (Yii::app()->controller->module->crons as $c) {
					UGCron::add(new $c);
				}
				UGCron::run();
			}
			$model->save();
		}
		return !$this->errorCode;
	}

	/**
	 * login in recovery mode
	 * @return boolean wheter is possible to login in recovery mode
	 */
	public function recovery()
	{
		$model=UserGroupsUser::model()->findByAttributes(array('username' => $this->username));

		if(!count($model))
			$this->errorCode=self::ERROR_USERNAME_INVALID;
		else if((int)$model->status === UserGroupsUser::BANNED)
			$this->errorCode=self::ERROR_USER_BANNED;
		else if((int)$model->status === UserGroupsUser::ACTIVE)
			$this->errorCode=self::ERROR_USER_ACTIVE;
		else if((int)$model->status === UserGroupsUser::WAITING_APPROVAL)
			$this->errorCode=self::ERROR_USER_APPROVAL;
		else if($model->activation_code !== $this->password)
			$this->errorCode=self::ERROR_ACTIVATION_CODE;
		else {
			$this->errorCode=self::ERROR_NONE;
			$this->id = $model->id;
			$this->name = Yii::t('userGroupsModule.general','Recovery Mode');
			$this->group = $model->group_id;
			$this->groupName = $model->relUserGroupsGroup->groupname;
			$this->level = $model->relUserGroupsGroup->level;
			$this->accessRules = $this->accessRulesComputation($model);
			$this->home = $model->home;
			$this->recovery = true;
			// load profile extension's data
			$this->profileLoad($model);
			// update the last login time
			$model->last_login = date('Y-m-d H:i:s');
			$model->save();
		}
		return !$this->errorCode;
	}

	/**
	 * computates the user access rules
	 * @param CActiveData $model
	 * @return mixed
	 */
	private function accessRulesComputation($model)
	{
		if (is_array($model->access))
			return array_merge_recursive($model->relUserGroupsGroup->access, $model->access);
		else
			return $model->access;
	}

	/**
	 * get profile extensions attribute values that are
	 * supposed to be stored in session
	 * @param CActiveRecord $model
	 * @since 1.7
	 */
	private function profileLoad($model)
	{
		$array = array();
		foreach (Yii::app()->controller->module->profile as $p) {
			$class = new ReflectionClass($p);
			if ($class->hasMethod('profileSessionData')) {
				// TODO when stop supporting php 5.2 just initialize the model with variables
				$class = new $p;
				$relation = 'rel'.$p;
				foreach ($class->profileSessionData() as $sessionAttribute) {
					$array[$p][$sessionAttribute] = $model->$relation === NULL ? NULL : $model->$relation->$sessionAttribute;
				}
			}
		}

		// memory cleanup
		unset($class);
		unset($relation);
		$this->profile = $array;
	}

	/**
	 * returns the user id
	 * @return int
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * return the username
	 * @return string
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * returns the group id
	 * @return int
	 */
	public function getGroup()
	{
		return $this->group;
	}

	/**
	 * returns the group name
	 * @return string
	 */
	public function getGroupName()
	{
		return $this->groupName;
	}

	/**
	 * returns the user group level
	 * @return int
	 */
	public function getLevel()
	{
		return $this->level;
	}

	/**
	 * returns the accessRules value
	 * @return mixed
	 */
	public function getAccessRules()
	{
		return $this->accessRules;
	}

	/**
	 * returns the user home
	 * @return string
	 */
	public function getHome()
	{
		return $this->home;
	}

	/**
	 * returns the value of recovery
	 * @return bool
	 */
	public function getRecovery()
	{
		return $this->recovery;
	}

	/**
	 * returns the value of profile
	 * @return array
	 * @since 1.7
	 */
	public function getProfile()
	{
		return $this->profile;
	}
}