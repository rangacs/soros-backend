<?php

/**
 * This is the model class for table "lab_data".
 *
 * The followings are the available columns in table 'lab_data':
 * @property integer $lab_data_id
 * @property string $EndTime
 * @property string $Al2O3
 * @property string $CaO
 * @property string $Fe2O3
 * @property string $MgO
 * @property string $SiO2
 * @property string $KH
 * @property string $SM
 * @property string $AM
 */
class LabDataForm extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'lab_data';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('Al2O3, CaO, Fe2O3, MgO, SiO2, KH, SM, AM', 'length', 'max'=>10),
			array('EndTime', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('lab_data_id, EndTime, Al2O3, CaO, Fe2O3, MgO, SiO2, KH, SM, AM', 'safe', 'on'=>'search'),
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
			'lab_data_id' => 'Lab Data',
			'EndTime' => 'End Time',
			'Al2O3' => 'Al2 O3',
			'CaO' => 'Ca O',
			'Fe2O3' => 'Fe2 O3',
			'MgO' => 'Mg O',
			'SiO2' => 'Si O2',
			'KH' => 'Kh',
			'SM' => 'Sm',
			'AM' => 'Am',
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

		$criteria->compare('lab_data_id',$this->lab_data_id);
		$criteria->compare('EndTime',$this->EndTime,true);
		$criteria->compare('Al2O3',$this->Al2O3,true);
		$criteria->compare('CaO',$this->CaO,true);
		$criteria->compare('Fe2O3',$this->Fe2O3,true);
		$criteria->compare('MgO',$this->MgO,true);
		$criteria->compare('SiO2',$this->SiO2,true);
		$criteria->compare('KH',$this->KH,true);
		$criteria->compare('SM',$this->SM,true);
		$criteria->compare('AM',$this->AM,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return LabDataForm the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
