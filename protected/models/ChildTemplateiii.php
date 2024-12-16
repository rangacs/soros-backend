<?php

/**
 * This is the model class for table "tbl_audit_trail_child_template_iii".
 *
 * The followings are the available columns in table 'tbl_audit_trail_child_template_iii':
 * @property string $id
 * @property string $child_key_
 * @property string $action
 * @property string $controller
 * @property string $value
 *
 * The followings are the available model relations:
 * @property TblAuditTrailMasterTemplateIii $childKey
 */
class ChildTemplateiii extends CActiveRecord
{
	/**
	 * Returns the static model of the specified AR class.
	 * @param string $className active record class name.
	 * @return ChildTemplateiii the static model class
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
		return 'tbl_audit_trail_child_template_iii';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('child_key_, action, controller, value', 'required'),
			array('child_key_, action, controller, value', 'length', 'max'=>120),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, child_key_, action, controller, value', 'safe', 'on'=>'search'),
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
			'childKey' => array(self::BELONGS_TO, 'TblAuditTrailMasterTemplateIii', 'child_key_'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'child_key_' => 'Child Key',
			'action' => 'Action',
			'controller' => 'Controller',
			'value' => 'Value',
		);
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

		$criteria->compare('id',$this->id,true);
		$criteria->compare('child_key_',$this->child_key_,true);
		$criteria->compare('action',$this->action,true);
		$criteria->compare('controller',$this->controller,true);
		$criteria->compare('value',$this->value,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}
}