<?php

/**
 * This is the model class for table "sources".
 *
 * The followings are the available columns in table 'sources':
 * @property integer $source_id
 * @property integer $profile_id
 * @property string $name
 * @property string $cost
 * @property string $distance_from_analyser
 * @property string $feeder_rate
 * @property integer $delay_from_analyser
 */
class Sources extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'sources';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('source_id, profile_id, name, cost, distance_from_analyser, feeder_rate, delay_from_analyser', 'required'),
			array('source_id, profile_id, delay_from_analyser', 'numerical', 'integerOnly'=>true),
			array('name, cost, distance_from_analyser, feeder_rate', 'length', 'max'=>100),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('source_id, profile_id, name, cost, distance_from_analyser, feeder_rate, delay_from_analyser,measured_feedrate', 'safe', 'on'=>'search'),
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
			'source_id' => 'Source',
			'profile_id' => 'Profile',
			'name' => 'Name',
			'cost' => 'Cost',
			'distance_from_analyser' => 'Distance From Analyser',
			'feeder_rate' => 'Feeder Rate',
			'delay_from_analyser' => 'Delay From Analyser',
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

		$criteria->compare('source_id',$this->source_id);
		$criteria->compare('profile_id',$this->profile_id);
		$criteria->compare('name',$this->name,true);
		$criteria->compare('cost',$this->cost,true);
		$criteria->compare('distance_from_analyser',$this->distance_from_analyser,true);
		$criteria->compare('feeder_rate',$this->feeder_rate,true);
		$criteria->compare('delay_from_analyser',$this->delay_from_analyser);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return Sources the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
