<?php

/**
 * This is the model class for table "wcl_rfid_cal_tol_settings".
 *
 * The followings are the available columns in table 'wcl_rfid_cal_tol_settings':
 * @property integer $wcl_cal_tol_id
 * @property string $wcl_cal_tol_item_code
 * @property double $Moisture
 * @property double $Moisture_Tol
 * @property double $Ash
 * @property double $Ash_Tol
 * @property double $Sulfur
 * @property double $Sulfur_Tol
 * @property double $GCV
 * @property double $GCV_Tol
 * @property double $BTU
 * @property double $BTU_Tol
 * @property string $updated_on
 */
class RfidCalTolSettings extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'wcl_rfid_cal_tol_settings';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('wcl_cal_tol_item_code, Moisture, Moisture_Tol, Ash, Ash_Tol, Sulfur, Sulfur_Tol, GCV, GCV_Tol, BTU, BTU_Tol, updated_on', 'required'),
			array('Moisture, Moisture_Tol, Ash, Ash_Tol, Sulfur, Sulfur_Tol, GCV, GCV_Tol, BTU, BTU_Tol', 'numerical'),
			array('wcl_cal_tol_item_code, updated_on', 'length', 'max'=>100),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('wcl_cal_tol_id, wcl_cal_tol_item_code, Moisture, Moisture_Tol, Ash, Ash_Tol, Sulfur, Sulfur_Tol, GCV, GCV_Tol, BTU, BTU_Tol, updated_on', 'safe', 'on'=>'search'),
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
			'wcl_cal_tol_id' => 'Wcl Cal Tol',
			'wcl_cal_tol_item_code' => 'Item Code',
			'Moisture' => 'Moisture',
			'Moisture_Tol' => 'Moisture Tol',
			'Ash' => 'Ash',
			'Ash_Tol' => 'Ash Tol',
			'Sulfur' => 'Sulfur',
			'Sulfur_Tol' => 'Sulfur Tol',
			'GCV' => 'Gcv',
			'GCV_Tol' => 'Gcv Tol',
			'BTU' => 'Btu',
			'BTU_Tol' => 'Btu Tol',
			'updated_on' => 'Updated On',
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

		$criteria->compare('wcl_cal_tol_id',$this->wcl_cal_tol_id);
		$criteria->compare('wcl_cal_tol_item_code',$this->wcl_cal_tol_item_code,true);
		$criteria->compare('Moisture',$this->Moisture);
		$criteria->compare('Moisture_Tol',$this->Moisture_Tol);
		$criteria->compare('Ash',$this->Ash);
		$criteria->compare('Ash_Tol',$this->Ash_Tol);
		$criteria->compare('Sulfur',$this->Sulfur);
		$criteria->compare('Sulfur_Tol',$this->Sulfur_Tol);
		$criteria->compare('GCV',$this->GCV);
		$criteria->compare('GCV_Tol',$this->GCV_Tol);
		$criteria->compare('BTU',$this->BTU);
		$criteria->compare('BTU_Tol',$this->BTU_Tol);
		$criteria->compare('updated_on',$this->updated_on,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return RfidCalTolSettings the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
