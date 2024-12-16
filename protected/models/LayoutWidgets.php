<?php

/**
 * This is the model class for table "layout_widgets".
 *
 * The followings are the available columns in table 'layout_widgets':
 * @property integer $widget_id
 * @property string $title
 * @property string $type
 * @property integer $layout_id
 * @property integer $position
 * @property string $settings
 * @property string $created_on
 * @property integer $created_by
 * @property string $updated_on
 * @property integer $updated_by
 */
class LayoutWidgets extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'layout_widgets';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title, type, layout_id, position, settings, created_by, updated_by', 'required'),
			array('layout_id, position, created_by, updated_by', 'numerical', 'integerOnly'=>true),
			array('title, type', 'length', 'max'=>100),
			array('settings', 'length', 'max'=>1000),
			array('created_on, updated_on', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('widget_id, title, type, layout_id, position, settings, created_on, created_by, updated_on, updated_by', 'safe', 'on'=>'search'),
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
			'widget_id' => 'Widget',
			'title' => 'Title',
			'type' => 'Type',
			'layout_id' => 'Layout',
			'position' => 'Position',
			'settings' => 'Settings',
			'created_on' => 'Created On',
			'created_by' => 'Created By',
			'updated_on' => 'Updated On',
			'updated_by' => 'Updated By',
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

		$criteria->compare('widget_id',$this->widget_id);
		$criteria->compare('title',$this->title,true);
		$criteria->compare('type',$this->type,true);
		$criteria->compare('layout_id',$this->layout_id);
		$criteria->compare('position',$this->position);
		$criteria->compare('settings',$this->settings,true);
		$criteria->compare('created_on',$this->created_on,true);
		$criteria->compare('created_by',$this->created_by);
		$criteria->compare('updated_on',$this->updated_on,true);
		$criteria->compare('updated_by',$this->updated_by);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return LayoutWidgets the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
