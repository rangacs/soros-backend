<?php

/**
 * This is the model class for table "rta_average_groupids".
 *
 * The followings are the available columns in table 'rta_average_groupids':
 * @property string $recordID
 * @property string $rtaMasterID
 * @property string $groupAveragingID
 */
class RtaAverageGroupids extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'rta_average_groupIDs';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('rtaMasterID, groupAveragingID', 'required'),
			array('rtaMasterID, groupAveragingID', 'length', 'max'=>10),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('recordID, rtaMasterID, groupAveragingID', 'safe', 'on'=>'search'),
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
			'recordID' => 'Record',
			'rtaMasterID' => 'Rta Master',
			'groupAveragingID' => 'Group Averaging',
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

		$criteria->compare('recordID',$this->recordID,true);
		$criteria->compare('rtaMasterID',$this->rtaMasterID,true);
		$criteria->compare('groupAveragingID',$this->groupAveragingID,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return RtaAverageGroupids the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
