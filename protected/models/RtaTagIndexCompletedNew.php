<?php

/**
 * This is the model class for table "rta_tag_index_completed".
 *
 * The followings are the available columns in table 'rta_tag_index_completed':
 * @property string $tagID
 * @property string $rtaMasterID
 * @property string $status
 * @property string $tagName
 * @property string $tagGroupID
 * @property string $LocalstartTime
 * @property string $LocalendTime
 * @property string $goodDataSecondsWeight
 * @property string $massflowWeight
 * @property string $validTag
 * @property string $endTic
 * @property string $startTic
 * @property integer $goodDataSecs
 * @property double $avgMassFlowTph
 * @property double $totalTons
 * @property double $Ash
 * @property double $Sulfur
 * @property double $Moisture
 * @property double $BTU
 * @property double $Na2O
 * @property double $SO2
 * @property double $TPH
 * @property double $SiO2
 * @property double $Al2O3
 * @property double $Fe2O3
 * @property double $MAFBTU
 * @property double $CaO
 * @property double $K
 */
class RtaTagIndexCompletedNew extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'rta_tag_index_completed';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('rtaMasterID, status, tagName, tagGroupID, goodDataSecondsWeight, massflowWeight', 'required'),
			array('goodDataSecs', 'numerical', 'integerOnly'=>true),
			array('avgMassFlowTph, totalTons, Ash, Sulfur, Moisture, BTU, Na2O, SO2, TPH, SiO2, Al2O3, Fe2O3, MAFBTU, CaO, K', 'numerical'),
			array('rtaMasterID, endTic, startTic', 'length', 'max'=>10),
			array('status', 'length', 'max'=>9),
			array('tagName', 'length', 'max'=>30),
			array('tagGroupID', 'length', 'max'=>4),
			array('goodDataSecondsWeight, massflowWeight', 'length', 'max'=>1),
			array('validTag', 'length', 'max'=>3),
			array('LocalstartTime, LocalendTime', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('tagID, rtaMasterID, status, tagName, tagGroupID, LocalstartTime, LocalendTime, goodDataSecondsWeight, massflowWeight, validTag, endTic, startTic, goodDataSecs, avgMassFlowTph, totalTons, Ash, Sulfur, Moisture, BTU, Na2O, SO2, TPH, SiO2, Al2O3, Fe2O3, MAFBTU, CaO, K', 'safe', 'on'=>'search'),
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
			'tagID' => 'Tag',
			'rtaMasterID' => 'Rta Master',
			'status' => 'Status',
			'tagName' => 'Tag Name',
			'tagGroupID' => 'Tag Group',
			'LocalstartTime' => 'Localstart Time',
			'LocalendTime' => 'Localend Time',
			'goodDataSecondsWeight' => 'Good Data Seconds Weight',
			'massflowWeight' => 'Massflow Weight',
			'validTag' => 'Valid Tag',
			'endTic' => 'End Tic',
			'startTic' => 'Start Tic',
			'goodDataSecs' => 'Good Data Secs',
			'avgMassFlowTph' => 'Avg Mass Flow Tph',
			'totalTons' => 'Total Tons',
			'Ash' => 'Ash',
			'Sulfur' => 'Sulfur',
			'Moisture' => 'Moisture',
			'BTU' => 'Btu',
			'Na2O' => 'Na2 O',
			'SO2' => 'So2',
			'TPH' => 'Tph',
			'SiO2' => 'Si O2',
			'Al2O3' => 'Al2 O3',
			'Fe2O3' => 'Fe2 O3',
			'MAFBTU' => 'Mafbtu',
			'CaO' => 'Ca O',
			'K' => 'K',
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

		$criteria->compare('tagID',$this->tagID,true);
		$criteria->compare('rtaMasterID',$this->rtaMasterID,true);
		$criteria->compare('status',$this->status,true);
		$criteria->compare('tagName',$this->tagName,true);
		$criteria->compare('tagGroupID',$this->tagGroupID,true);
		$criteria->compare('LocalstartTime',$this->LocalstartTime,true);
		$criteria->compare('LocalendTime',$this->LocalendTime,true);
		$criteria->compare('goodDataSecondsWeight',$this->goodDataSecondsWeight,true);
		$criteria->compare('massflowWeight',$this->massflowWeight,true);
		$criteria->compare('validTag',$this->validTag,true);
		$criteria->compare('endTic',$this->endTic,true);
		$criteria->compare('startTic',$this->startTic,true);
		$criteria->compare('goodDataSecs',$this->goodDataSecs);
		$criteria->compare('avgMassFlowTph',$this->avgMassFlowTph);
		$criteria->compare('totalTons',$this->totalTons);
		$criteria->compare('Ash',$this->Ash);
		$criteria->compare('Sulfur',$this->Sulfur);
		$criteria->compare('Moisture',$this->Moisture);
		$criteria->compare('BTU',$this->BTU);
		$criteria->compare('Na2O',$this->Na2O);
		$criteria->compare('SO2',$this->SO2);
		$criteria->compare('TPH',$this->TPH);
		$criteria->compare('SiO2',$this->SiO2);
		$criteria->compare('Al2O3',$this->Al2O3);
		$criteria->compare('Fe2O3',$this->Fe2O3);
		$criteria->compare('MAFBTU',$this->MAFBTU);
		$criteria->compare('CaO',$this->CaO);
		$criteria->compare('K',$this->K);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return RtaTagIndexCompletedNew the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
