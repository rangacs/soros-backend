<?php

/**
 * This is the model class for table "rta_derived_config".
 *
 * The followings are the available columns in table 'rta_derived_config':
 * @property string $rta_ID_derived
 * @property string $rtaMasterID
 * @property string $data_source_rtaMasterID
 * @property string $filter_type
 * @property string $moving_avg_filter_sample_timespan
 * @property string $kalman_filter_sample_timespan
 * @property string $massflowWeight_derivedCfg
 * @property string $goodDataSecondsWeight_derivedCfg
 * @property string $percentageGoodDataRequired
 * @property double $kalman_gain_Q
 * @property double $kalman_gain_R
 * @property string $source_decay_comp
 * @property string $source_decay_ref_date
 */
class RtaDerivedConfig extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'rta_derived_config';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('rtaMasterID, data_source_rtaMasterID', 'required'),
			array('kalman_gain_Q, kalman_gain_R', 'numerical'),
			array('rtaMasterID, data_source_rtaMasterID, moving_avg_filter_sample_timespan, kalman_filter_sample_timespan', 'length', 'max'=>10),
			array('massflowWeight_derivedCfg, goodDataSecondsWeight_derivedCfg, source_decay_comp', 'length', 'max'=>1),
			array('percentageGoodDataRequired', 'length', 'max'=>3),
			array('filter_type, source_decay_ref_date', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('rta_ID_derived, rtaMasterID, data_source_rtaMasterID, filter_type, moving_avg_filter_sample_timespan, kalman_filter_sample_timespan, massflowWeight_derivedCfg, goodDataSecondsWeight_derivedCfg, percentageGoodDataRequired, kalman_gain_Q, kalman_gain_R, source_decay_comp, source_decay_ref_date', 'safe', 'on'=>'search'),
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
			'rta_ID_derived' => 'Rta Id Derived',
			'rtaMasterID' => 'Rta Master',
			'data_source_rtaMasterID' => 'Data Source Rta Master',
			'filter_type' => 'Filter Type',
			'moving_avg_filter_sample_timespan' => 'Moving Avg Filter Sample Timespan',
			'kalman_filter_sample_timespan' => 'Kalman Filter Sample Timespan',
			'massflowWeight_derivedCfg' => 'Massflow Weight Derived Cfg',
			'goodDataSecondsWeight_derivedCfg' => 'Good Data Seconds Weight Derived Cfg',
			'percentageGoodDataRequired' => 'Percentage Good Data Required',
			'kalman_gain_Q' => 'Kalman Gain Q',
			'kalman_gain_R' => 'Kalman Gain R',
			'source_decay_comp' => 'Source Decay Comp',
			'source_decay_ref_date' => 'Source Decay Ref Date',
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

		$criteria->compare('rta_ID_derived',$this->rta_ID_derived,true);
		$criteria->compare('rtaMasterID',$this->rtaMasterID,true);
		$criteria->compare('data_source_rtaMasterID',$this->data_source_rtaMasterID,true);
		$criteria->compare('filter_type',$this->filter_type,true);
		$criteria->compare('moving_avg_filter_sample_timespan',$this->moving_avg_filter_sample_timespan,true);
		$criteria->compare('kalman_filter_sample_timespan',$this->kalman_filter_sample_timespan,true);
		$criteria->compare('massflowWeight_derivedCfg',$this->massflowWeight_derivedCfg,true);
		$criteria->compare('goodDataSecondsWeight_derivedCfg',$this->goodDataSecondsWeight_derivedCfg,true);
		$criteria->compare('percentageGoodDataRequired',$this->percentageGoodDataRequired,true);
		$criteria->compare('kalman_gain_Q',$this->kalman_gain_Q);
		$criteria->compare('kalman_gain_R',$this->kalman_gain_R);
		$criteria->compare('source_decay_comp',$this->source_decay_comp,true);
		$criteria->compare('source_decay_ref_date',$this->source_decay_ref_date,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return RtaDerivedConfig the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
