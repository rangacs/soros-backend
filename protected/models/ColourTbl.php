<?php

/**
 * This is the model class for table "colour_tbl".
 *
 * The followings are the available columns in table 'colour_tbl':
 * @property integer $colour_id
 * @property string $colour
 * @property integer $position
 * @property integer $status
 * @property string $funnel_name
 */
class ColourTbl extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'colour_tbl';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('colour, position, status, funnel_name', 'required'),
			array('position, status', 'numerical', 'integerOnly'=>true),
			array('colour, funnel_name', 'length', 'max'=>255),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('colour_id, colour, position, status, funnel_name', 'safe', 'on'=>'search'),
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
			'colour_id' => 'Colour',
			'colour' => 'Colour',
			'position' => 'Position',
			'status' => 'Status',
			'funnel_name' => 'Funnel Name',
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

		$criteria->compare('colour_id',$this->colour_id);
		$criteria->compare('colour',$this->colour,true);
		$criteria->compare('position',$this->position);
		$criteria->compare('status',$this->status);
		$criteria->compare('funnel_name',$this->funnel_name,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ColourTbl the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
