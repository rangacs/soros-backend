<?php

/**
 * This is the model class for table "gadlay_column_preferences".
 *
 * The followings are the available columns in table 'gadlay_column_preferences':
 * @property integer $internal_id
 * @property string $user_id
 * @property string $table_name
 * @property string $allowed_columns_shown
 * @property integer $last_updated
 *
 * The followings are the available model relations:
 * @property UsergroupsUser $user
 */
class ColumnPreferences extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'gadlay_column_preferences';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('user_id, table_name', 'required'),
			array('last_updated', 'numerical', 'integerOnly'=>true),
			array('user_id', 'length', 'max'=>20),
			array('table_name', 'length', 'max'=>75),
			array('allowed_columns_shown', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('internal_id, user_id, table_name, allowed_columns_shown, last_updated', 'safe', 'on'=>'search'),
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
			'user' => array(self::BELONGS_TO, 'UsergroupsUser', 'user_id'),
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'internal_id' => 'Internal',
			'user_id' => 'User',
			'table_name' => 'Table Name',
			'allowed_columns_shown' => 'Allowed Columns Shown',
			'last_updated' => 'Last Updated',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 *
	 * Typical usecase:
	 * - Initialize the model fields with values from filter form.
	 * - Execute this method to get CActiveDataProvider instance which will filter
	 * models according to data in model fields.
	 * - Pass data provider to CGridView, CListView or any similar widget.
	 *
	 * @return CActiveDataProvider the data provider that can return the models
	 * based on the search/filter conditions.
	 */
	public function search()
	{
		// @todo Please modify the following code to remove attributes that should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('internal_id',$this->internal_id);
		$criteria->compare('user_id',$this->user_id,true);
		$criteria->compare('table_name',$this->table_name,true);
		$criteria->compare('allowed_columns_shown',$this->allowed_columns_shown,true);
		$criteria->compare('last_updated',$this->last_updated);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ColumnPreferences the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
