<?php

/**
 * This is the model class for table "layouts".
 *
 * The followings are the available columns in table 'layouts':
 * @property integer $layout_id
 * @property string $name
 * @property string $type
 * @property integer $category
 * @property integer $is_default
 * @property integer $user_id
 * @property string $created_on
 * @property string $updated_on
 * @property integer $created_by
 */
class Layouts extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'layouts';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, type', 'required'),
			array('category, is_default, user_id, created_by', 'numerical', 'integerOnly'=>true),
			array('name, type', 'length', 'max'=>100),
			array('created_on, updated_on', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('layout_id, name, type, category, is_default, user_id, created_on, updated_on, created_by', 'safe', 'on'=>'search'),
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
			'layout_id' => 'Layout',
			'name' => 'Name',
			'type' => 'Type',
			'category' => 'Category',
			'is_default' => 'Is Default',
			'user_id' => 'User',
			'created_on' => 'Created On',
			'updated_on' => 'Updated On',
			'created_by' => 'Created By',
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

		$criteria->compare('layout_id',$this->layout_id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('category',$this->category);
		$criteria->compare('is_default',$this->is_default);
		$criteria->compare('user_id',$this->user_id);
		$criteria->compare('created_on',$this->created_on,true);
		$criteria->compare('updated_on',$this->updated_on,true);
		$criteria->compare('created_by',$this->created_by);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Layouts the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
