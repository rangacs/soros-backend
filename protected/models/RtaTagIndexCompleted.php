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
 * @property double $TEST
 * @property double $CAL_ID
 * @property double $MAFBTU
 * @property double $CaO
 * @property double $MgO
 * @property double $K2O
 * @property double $TiO2
 * @property double $Mn2O3
 * @property double $P2O5
 * @property double $SO3
 * @property double $Cl
 * @property double $LOI
 * @property double $LSF
 * @property double $LSF_STD
 * @property double $SM
 * @property double $AM
 * @property double $IM
 * @property double $C4AF
 * @property double $NAEQ
 * @property double $C3S
 * @property double $C3A
 * @property double $SourceDeployed
 * @property double $SourceStored
 * @property double $cps
 * @property double $K
 * @property double $V2O5
 * @property double $CdO
 * @property double $GCV
 * @property double $cps_det1
 * @property double $cps_det2
 */
class RtaTagIndexCompleted extends CActiveRecord
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
			array('avgMassFlowTph, totalTons, totalTonsRemoved ,Ash, Sulfur, Moisture, BTU, Na2O, SO2, TPH, SiO2, Al2O3, Fe2O3, TEST, CAL_ID, MAFBTU, CaO, MgO, K2O, TiO2, Mn2O3, P2O5, SO3, Cl, LOI, C4AF, NAEQ, C3S, C3A, SourceDeployed, SourceStored, cps, K, V2O5, CdO, GCV, cps_det1, cps_det2', 'numerical'),
			array('rtaMasterID, endTic, startTic', 'length', 'max'=>10),
			array('status', 'length', 'max'=>9),
			array('tagName', 'length', 'max'=>30),
			array('tagGroupID', 'length', 'max'=>4),
			array('goodDataSecondsWeight, massflowWeight', 'length', 'max'=>1),
			array('validTag', 'length', 'max'=>3),
			array('LocalstartTime, LocalendTime', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('tagID, rtaMasterID, status, tagName, tagGroupID, LocalstartTime, LocalendTime, goodDataSecondsWeight, massflowWeight, validTag, endTic, startTic, goodDataSecs, avgMassFlowTph, totalTons, totalTonsRemoved,Ash, Sulfur, Moisture, BTU, Na2O, SO2, TPH, SiO2, Al2O3, Fe2O3, TEST, CAL_ID, MAFBTU, CaO, MgO, K2O, TiO2, Mn2O3, P2O5, SO3, Cl, LOI, LSF,LSF_STD, SM, AM, IM, C4AF, NAEQ, C3S, C3A, SourceDeployed, SourceStored, cps, K, V2O5, CdO, GCV, cps_det1, cps_det2', 'safe', 'on'=>'search'),
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
			'TEST' => 'Test',
			'CAL_ID' => 'Cal',
			'MAFBTU' => 'Mafbtu',
			'CaO' => 'Ca O',
			'MgO' => 'Mg O',
			'K2O' => 'K2 O',
			'TiO2' => 'Ti O2',
			'Mn2O3' => 'Mn2 O3',
			'P2O5' => 'P2 O5',
			'SO3' => 'So3',
			'Cl' => 'Cl',
			'LOI' => 'Loi',
			'LSF' => 'Lsf',
			'LSF_STD' => 'LSF STD',
			'SM' => 'Sm',
			'AM' => 'Am',
			'IM' => 'Im',
			'C4AF' => 'C4 Af',
			'NAEQ' => 'Naeq',
			'C3S' => 'C3 S',
			'C3A' => 'C3 A',
			'SourceDeployed' => 'Source Deployed',
			'SourceStored' => 'Source Stored',
			'cps' => 'cps',
			'K' => 'K',
			'V2O5' => 'V2 O5',
			'CdO' => 'Cd O',
			'GCV' => 'Gcv',
			'cps_det1' => 'cps Det1',
			'cps_det2' => 'cps Det2',
			'totalTonsRemoved' => 'totalTonsRemoved',
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
		$criteria->compare('TEST',$this->TEST);
		$criteria->compare('CAL_ID',$this->CAL_ID);
		$criteria->compare('MAFBTU',$this->MAFBTU);
		$criteria->compare('CaO',$this->CaO);
		$criteria->compare('MgO',$this->MgO);
		$criteria->compare('K2O',$this->K2O);
		$criteria->compare('TiO2',$this->TiO2);
		$criteria->compare('Mn2O3',$this->Mn2O3);
		$criteria->compare('P2O5',$this->P2O5);
		$criteria->compare('SO3',$this->SO3);
		$criteria->compare('Cl',$this->Cl);
		$criteria->compare('LOI',$this->LOI);
		$criteria->compare('LSF',$this->LSF);
		$criteria->compare('LSF_STD',$this->LSF_STD);
		$criteria->compare('SM',$this->SM);
		$criteria->compare('AM',$this->AM);
		$criteria->compare('IM',$this->IM);
		$criteria->compare('C4AF',$this->C4AF);
		$criteria->compare('NAEQ',$this->NAEQ);
		$criteria->compare('C3S',$this->C3S);
		$criteria->compare('C3A',$this->C3A);
		$criteria->compare('SourceDeployed',$this->SourceDeployed);
		$criteria->compare('SourceStored',$this->SourceStored);
		$criteria->compare('cps',$this->cps);
		$criteria->compare('K',$this->K);
		$criteria->compare('V2O5',$this->V2O5);
		$criteria->compare('CdO',$this->CdO);
		$criteria->compare('GCV',$this->GCV);
		$criteria->compare('cps_det1',$this->cps_det1);
		$criteria->compare('cps_det2',$this->cps_det2);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return RtaTagIndexCompleted the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
