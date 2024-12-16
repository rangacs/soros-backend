<?php

/**
 * This is the model class for table "tag_std".
 *
 * The followings are the available columns in table 'tag_std':
 * @property string $tagID
 * @property string $rtaMasterID
 * @property string $status
 * @property string $hasMerge
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
 * @property double $SiO2_AR
 * @property double $Al2O3_AR
 * @property double $Fe2O3_AR
 * @property double $CaO_AR
 * @property double $MgO_AR
 * @property double $K2O_AR
 * @property double $Na2O_AR
 * @property double $TiO2_AR
 * @property double $Mn2O3_AR
 * @property double $P2O5_AR
 * @property double $SO3_AR
 * @property double $Cl_AR
 * @property double $C3S2
 * @property double $SiO2ST
 * @property double $CaOST
 * @property double $Fe2O3ST
 * @property double $Al2O3ST
 * @property double $MgOST
 * @property double $K2OST
 * @property double $SO3ST
 * @property double $Na2OST
 * @property double $TiO2ST
 * @property double $Shale
 * @property double $S
 * @property double $total
 * @property double $AlR
 * @property double $FlR
 * @property double $CaR
 * @property double $SiR
 * @property double $MgR
 * @property double $Ala
 * @property double $Fla
 * @property double $Caa
 * @property double $Sia
 * @property double $Mga
 * @property double $Alb
 * @property double $Flb
 * @property double $Cab
 * @property double $Sib
 * @property double $Mgb
 * @property double $SOa
 * @property double $K2Oa
 * @property double $NEWloi
 * @property double $g1
 * @property double $g2
 * @property double $g3
 * @property double $g4
 * @property double $g5
 * @property double $Flag1
 * @property double $Flag2
 * @property double $SuR
 * @property double $K2R
 * @property double $NaR
 * @property double $AlRes
 * @property double $FeRes
 * @property double $SiRes
 * @property double $CaRes
 * @property double $MgRes
 * @property double $NaRes
 * @property double $SORes
 * @property double $KRes
 * @property double $totalTonsRemoved
 */
class TagStd extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'tag_std';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('rtaMasterID, status, hasMerge, tagName, tagGroupID, goodDataSecondsWeight, massflowWeight', 'required'),
			array('goodDataSecs', 'numerical', 'integerOnly'=>true),
			array('avgMassFlowTph, totalTons, Ash, Sulfur, Moisture, BTU, Na2O, SO2, TPH, SiO2, Al2O3, Fe2O3, TEST, CAL_ID, MAFBTU, CaO, MgO, K2O, TiO2, Mn2O3, P2O5, SO3, Cl, LOI, LSF, LSF_STD, SM, AM, IM, C4AF, NAEQ, C3S, C3A, SourceDeployed, SourceStored, cps, K, V2O5, CdO, GCV, cps_det1, cps_det2, SiO2_AR, Al2O3_AR, Fe2O3_AR, CaO_AR, MgO_AR, K2O_AR, Na2O_AR, TiO2_AR, Mn2O3_AR, P2O5_AR, SO3_AR, Cl_AR, C3S2, SiO2ST, CaOST, Fe2O3ST, Al2O3ST, MgOST, K2OST, SO3ST, Na2OST, TiO2ST, Shale, S, total, AlR, FlR, CaR, SiR, MgR, Ala, Fla, Caa, Sia, Mga, Alb, Flb, Cab, Sib, Mgb, SOa, K2Oa, NEWloi, g1, g2, g3, g4, g5, Flag1, Flag2, SuR, K2R, NaR, AlRes, FeRes, SiRes, CaRes, MgRes, NaRes, SORes, KRes, totalTonsRemoved', 'numerical'),
			array('rtaMasterID, endTic, startTic', 'length', 'max'=>10),
			array('status', 'length', 'max'=>9),
			array('hasMerge', 'length', 'max'=>5),
			array('tagName', 'length', 'max'=>30),
			array('tagGroupID', 'length', 'max'=>4),
			array('goodDataSecondsWeight, massflowWeight', 'length', 'max'=>1),
			array('validTag', 'length', 'max'=>3),
			array('LocalstartTime, LocalendTime', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('tagID, rtaMasterID, status, hasMerge, tagName, tagGroupID, LocalstartTime, LocalendTime, goodDataSecondsWeight, massflowWeight, validTag, endTic, startTic, goodDataSecs, avgMassFlowTph, totalTons, Ash, Sulfur, Moisture, BTU, Na2O, SO2, TPH, SiO2, Al2O3, Fe2O3, TEST, CAL_ID, MAFBTU, CaO, MgO, K2O, TiO2, Mn2O3, P2O5, SO3, Cl, LOI, LSF, LSF_STD, SM, AM, IM, C4AF, NAEQ, C3S, C3A, SourceDeployed, SourceStored, cps, K, V2O5, CdO, GCV, cps_det1, cps_det2, SiO2_AR, Al2O3_AR, Fe2O3_AR, CaO_AR, MgO_AR, K2O_AR, Na2O_AR, TiO2_AR, Mn2O3_AR, P2O5_AR, SO3_AR, Cl_AR, C3S2, SiO2ST, CaOST, Fe2O3ST, Al2O3ST, MgOST, K2OST, SO3ST, Na2OST, TiO2ST, Shale, S, total, AlR, FlR, CaR, SiR, MgR, Ala, Fla, Caa, Sia, Mga, Alb, Flb, Cab, Sib, Mgb, SOa, K2Oa, NEWloi, g1, g2, g3, g4, g5, Flag1, Flag2, SuR, K2R, NaR, AlRes, FeRes, SiRes, CaRes, MgRes, NaRes, SORes, KRes, totalTonsRemoved', 'safe', 'on'=>'search'),
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
			'hasMerge' => 'Has Merge',
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
			'LSF_STD' => 'Lsf Std',
			'SM' => 'Sm',
			'AM' => 'Am',
			'IM' => 'Im',
			'C4AF' => 'C4 Af',
			'NAEQ' => 'Naeq',
			'C3S' => 'C3 S',
			'C3A' => 'C3 A',
			'SourceDeployed' => 'Source Deployed',
			'SourceStored' => 'Source Stored',
			'cps' => 'Cps',
			'K' => 'K',
			'V2O5' => 'V2 O5',
			'CdO' => 'Cd O',
			'GCV' => 'Gcv',
			'cps_det1' => 'Cps Det1',
			'cps_det2' => 'Cps Det2',
			'SiO2_AR' => 'Si O2 Ar',
			'Al2O3_AR' => 'Al2 O3 Ar',
			'Fe2O3_AR' => 'Fe2 O3 Ar',
			'CaO_AR' => 'Ca O Ar',
			'MgO_AR' => 'Mg O Ar',
			'K2O_AR' => 'K2 O Ar',
			'Na2O_AR' => 'Na2 O Ar',
			'TiO2_AR' => 'Ti O2 Ar',
			'Mn2O3_AR' => 'Mn2 O3 Ar',
			'P2O5_AR' => 'P2 O5 Ar',
			'SO3_AR' => 'So3 Ar',
			'Cl_AR' => 'Cl Ar',
			'C3S2' => 'C3 S2',
			'SiO2ST' => 'Si O2 St',
			'CaOST' => 'Ca Ost',
			'Fe2O3ST' => 'Fe2 O3 St',
			'Al2O3ST' => 'Al2 O3 St',
			'MgOST' => 'Mg Ost',
			'K2OST' => 'K2 Ost',
			'SO3ST' => 'So3 St',
			'Na2OST' => 'Na2 Ost',
			'TiO2ST' => 'Ti O2 St',
			'Shale' => 'Shale',
			'S' => 'S',
			'total' => 'Total',
			'AlR' => 'Al R',
			'FlR' => 'Fl R',
			'CaR' => 'Ca R',
			'SiR' => 'Si R',
			'MgR' => 'Mg R',
			'Ala' => 'Ala',
			'Fla' => 'Fla',
			'Caa' => 'Caa',
			'Sia' => 'Sia',
			'Mga' => 'Mga',
			'Alb' => 'Alb',
			'Flb' => 'Flb',
			'Cab' => 'Cab',
			'Sib' => 'Sib',
			'Mgb' => 'Mgb',
			'SOa' => 'Soa',
			'K2Oa' => 'K2 Oa',
			'NEWloi' => 'Newloi',
			'g1' => 'G1',
			'g2' => 'G2',
			'g3' => 'G3',
			'g4' => 'G4',
			'g5' => 'G5',
			'Flag1' => 'Flag1',
			'Flag2' => 'Flag2',
			'SuR' => 'Su R',
			'K2R' => 'K2 R',
			'NaR' => 'Na R',
			'AlRes' => 'Al Res',
			'FeRes' => 'Fe Res',
			'SiRes' => 'Si Res',
			'CaRes' => 'Ca Res',
			'MgRes' => 'Mg Res',
			'NaRes' => 'Na Res',
			'SORes' => 'Sores',
			'KRes' => 'Kres',
			'totalTonsRemoved' => 'Total Tons Removed',
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
		$criteria->compare('hasMerge',$this->hasMerge,true);
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
		$criteria->compare('SiO2_AR',$this->SiO2_AR);
		$criteria->compare('Al2O3_AR',$this->Al2O3_AR);
		$criteria->compare('Fe2O3_AR',$this->Fe2O3_AR);
		$criteria->compare('CaO_AR',$this->CaO_AR);
		$criteria->compare('MgO_AR',$this->MgO_AR);
		$criteria->compare('K2O_AR',$this->K2O_AR);
		$criteria->compare('Na2O_AR',$this->Na2O_AR);
		$criteria->compare('TiO2_AR',$this->TiO2_AR);
		$criteria->compare('Mn2O3_AR',$this->Mn2O3_AR);
		$criteria->compare('P2O5_AR',$this->P2O5_AR);
		$criteria->compare('SO3_AR',$this->SO3_AR);
		$criteria->compare('Cl_AR',$this->Cl_AR);
		$criteria->compare('C3S2',$this->C3S2);
		$criteria->compare('SiO2ST',$this->SiO2ST);
		$criteria->compare('CaOST',$this->CaOST);
		$criteria->compare('Fe2O3ST',$this->Fe2O3ST);
		$criteria->compare('Al2O3ST',$this->Al2O3ST);
		$criteria->compare('MgOST',$this->MgOST);
		$criteria->compare('K2OST',$this->K2OST);
		$criteria->compare('SO3ST',$this->SO3ST);
		$criteria->compare('Na2OST',$this->Na2OST);
		$criteria->compare('TiO2ST',$this->TiO2ST);
		$criteria->compare('Shale',$this->Shale);
		$criteria->compare('S',$this->S);
		$criteria->compare('total',$this->total);
		$criteria->compare('AlR',$this->AlR);
		$criteria->compare('FlR',$this->FlR);
		$criteria->compare('CaR',$this->CaR);
		$criteria->compare('SiR',$this->SiR);
		$criteria->compare('MgR',$this->MgR);
		$criteria->compare('Ala',$this->Ala);
		$criteria->compare('Fla',$this->Fla);
		$criteria->compare('Caa',$this->Caa);
		$criteria->compare('Sia',$this->Sia);
		$criteria->compare('Mga',$this->Mga);
		$criteria->compare('Alb',$this->Alb);
		$criteria->compare('Flb',$this->Flb);
		$criteria->compare('Cab',$this->Cab);
		$criteria->compare('Sib',$this->Sib);
		$criteria->compare('Mgb',$this->Mgb);
		$criteria->compare('SOa',$this->SOa);
		$criteria->compare('K2Oa',$this->K2Oa);
		$criteria->compare('NEWloi',$this->NEWloi);
		$criteria->compare('g1',$this->g1);
		$criteria->compare('g2',$this->g2);
		$criteria->compare('g3',$this->g3);
		$criteria->compare('g4',$this->g4);
		$criteria->compare('g5',$this->g5);
		$criteria->compare('Flag1',$this->Flag1);
		$criteria->compare('Flag2',$this->Flag2);
		$criteria->compare('SuR',$this->SuR);
		$criteria->compare('K2R',$this->K2R);
		$criteria->compare('NaR',$this->NaR);
		$criteria->compare('AlRes',$this->AlRes);
		$criteria->compare('FeRes',$this->FeRes);
		$criteria->compare('SiRes',$this->SiRes);
		$criteria->compare('CaRes',$this->CaRes);
		$criteria->compare('MgRes',$this->MgRes);
		$criteria->compare('NaRes',$this->NaRes);
		$criteria->compare('SORes',$this->SORes);
		$criteria->compare('KRes',$this->KRes);
		$criteria->compare('totalTonsRemoved',$this->totalTonsRemoved);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return TagStd the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
