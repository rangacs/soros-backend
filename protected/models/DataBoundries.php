<?php

/**
 * This is the model class for table "data_boundries".
 *
 * The followings are the available columns in table 'data_boundries':
 * @property integer $ac_id
 * @property string $element_name
 * @property double $min
 * @property double $max
 * @property double $valid_datapoint_range
 * @property double $diff
 * @property double $min_real
 * @property double $max_real
 * @property double $acceptable_deviation
 * @property double $actionable_current_deviation
 * @property double $big_deviation
 * @property double $max_offset_change
 * @property double $correction_pct
 * @property double $correction_percentage_till_cron
 * @property string $last_updated
 */
class DataBoundries extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'data_boundries';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('element_name, min, max', 'required'),
			array('element_name', 'length', 'max'=>10),
			array('last_updated', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('ac_id, element_name, min, max, valid_datapoint_range, diff, min_real, max_real, acceptable_deviation, actionable_current_deviation, big_deviation, max_offset_change, correction_pct, correction_percentage_till_cron, last_updated', 'safe', 'on'=>'search'),
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
			'ac_id' => 'Ac',
			'element_name' => 'Element Name',
			'min' => 'Min',
			'max' => 'Max',
			'valid_datapoint_range' => 'Valid Datapoint Range',
			'diff' => 'Diff',
			'min_real' => 'Min Real',
			'max_real' => 'Max Real',
			'acceptable_deviation' => 'Acceptable Deviation',
			'actionable_current_deviation' => 'Actionable Current Deviation',
			'big_deviation' => 'Big Deviation',
			'max_offset_change' => 'Max Offset Change',
			'correction_pct' => 'Correction Pct',
			'correction_percentage_till_cron' => 'Correction Percentage Till Cron',
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

		$criteria->compare('ac_id',$this->ac_id);
		$criteria->compare('element_name',$this->element_name,true);
		$criteria->compare('min',$this->min);
		$criteria->compare('max',$this->max);
		$criteria->compare('valid_datapoint_range',$this->valid_datapoint_range);
		$criteria->compare('diff',$this->diff);
		$criteria->compare('min_real',$this->min_real);
		$criteria->compare('max_real',$this->max_real);
		$criteria->compare('acceptable_deviation',$this->acceptable_deviation);
		$criteria->compare('actionable_current_deviation',$this->actionable_current_deviation);
		$criteria->compare('big_deviation',$this->big_deviation);
		$criteria->compare('max_offset_change',$this->max_offset_change);
		$criteria->compare('correction_pct',$this->correction_pct);
		$criteria->compare('correction_percentage_till_cron',$this->correction_percentage_till_cron);
		$criteria->compare('last_updated',$this->last_updated,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return DataBoundries the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
