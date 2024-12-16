<?php

/**
 * This is the model class for table "usergroups_uisettings".
 *
 * The followings are the available columns in table 'usergroups_uisettings':
 * @property integer $id
 * @property integer $user_id
 * @property string $rule
 * @property string $value
 * @property string $options
 * @property string $description
 */
class UISettings extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return Uisettings the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'usergroups_uisettings';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('value, options', 'required'),
			array('user_id', 'numerical', 'integerOnly'=>true),
			array('rule, value, options', 'length', 'max'=>500),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, user_id, rule, value, options', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'user_id' => 'User',
			'rule' => 'Rule',
			'value' => 'Value',
			'options' => 'Options',
		);
	}

	public static function setConfig($u_id,$rule,$value,$options)
	{
		$connection = Yii::app()->db;

		if(isset($value) && isset($options))
		{
			$sql="UPDATE usergroups_uisettings set value=:value , options=:options WHERE user_id=:user_id AND rule=:rule";
			$command=$connection->createCommand($sql);

			$command->bindParam(":user_id",$u_id,PDO::PARAM_STR);
			$command->bindParam(":rule",$rule,PDO::PARAM_STR);
			$command->bindParam(":value",$value,PDO::PARAM_STR);
			$command->bindParam(":options",$options,PDO::PARAM_STR);


			$dataSetResult=$command->execute();

			if(isset($dataSetResult)) {
				return "true";
			}
			else {
				$commandInsert = $connection->createCommand("INSERT INTO usergroups_uisettings (id,user_id,rule,value,options) VALUES(:id,:user_id,:rule,:value,:options)");
				$commandInsert->bindParam(":user_id",$u_id,PDO::PARAM_STR);
				$commandInsert->bindParam(":id","NULL",PDO::PARAM_STR);
				$commandInsert->bindParam(":rule",$rule,PDO::PARAM_STR);
				$commandInsert->bindParam(":value",$value,PDO::PARAM_STR);
				$commandInsert->bindParam(":options",$options,PDO::PARAM_STR);
				$commandInsert->execute();

				$dataSetR=$commandInsert->execute();

				if(isset($dataSetR)) {
					return "true";
				}
				else
					return "false";
			}
		}
		return "false";
	}

	/*
	 * Getting configuration Information for the web settings
	 *
	*/
	public static function getConfig($u_id)
	{
		$connection = Yii::app()->db;

		$sql="select value,options,rule from usergroups_uisettings WHERE user_id=:user_id";
		$command=$connection->createCommand($sql);
		$command->bindParam(":user_id",$u_id,PDO::PARAM_STR);

		$dataReader=$command->query();

		// bind the 1st column (value) with the $value variable
		$dataReader->bindColumn(1,$value);
		// bind the 2nd column (options) with the $options variable
		$dataReader->bindColumn(2,$options);
		// bind the 2nd column (rules) with the $rules variable
		$dataReader->bindColumn(3,$rules);

		$mValsAr = array();
		if(isset($dataReader)) {
			while($dataReader->read()!==false)
			{
			    if(isset($value))
					$mValsAr["$rules"] =$options;
			}
			return 	$mValsAr;
		}
		else {
			return false;
		}
		return false;
	}

	/*
	 * Getting configuration Rules for the web settings
	 *
	*/
	public static function getRules($u_id)
	{
		$connection = Yii::app()->db;
		$sql="select rule from usergroups_uisettings WHERE user_id=:user_id";
		$command=$connection->createCommand($sql);
		$command->bindParam(":user_id",$u_id,PDO::PARAM_STR);

		$dataReader=$command->query();
		// bind the 1st column (rule) with the $rules variable
		$dataReader->bindColumn(1,$rules);

		$rulesArray = array();
		if(isset($dataReader)) {
			while($dataReader->read()!==false)
			{
			    array_push($rulesArray, $rules);
			}
			return $rulesArray;
		}
		else {
			return false;
		}
		return false;
	}


	public function behaviors()
	{
		//return array( 'LoggableBehavior'=> 'application.modules.auditTrail.behaviors.LoggableBehavior', );
		return array();
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('rule',$this->rule,true);
		$criteria->compare('value',$this->value,true);
		$criteria->compare('options',$this->options,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}