<?php

/**
 * This is the model class for table "calibration_log".
 *
 * The followings are the available columns in table 'calibration_log':
 * @property integer $cal_id
 * @property double $SiO2_gain
 * @property double $SiO2_offset
 * @property double $Fe2O3_gain
 * @property double $Fe2O3_offset
 * @property double $Al2O3_gain
 * @property double $Al2O3_offset
 * @property double $CaO_gain
 * @property double $CaO_offset
 * @property double $MgO_offset
 * @property double $MgO_gain
 * @property string $run_type
 * @property integer $updated_by
 * @property string $updated
 */
class CalibrationLog extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'calibration_log';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('SiO2_offset, Fe2O3_offset, Al2O3_offset, CaO_offset, MgO_offset, MgO_gain, run_type, updated_by, updated', 'required'),
            array('updated_by', 'numerical', 'integerOnly'=>true),
            array('SiO2_gain, SiO2_offset, Fe2O3_gain, Fe2O3_offset, Al2O3_gain, Al2O3_offset, CaO_gain, CaO_offset, MgO_offset, MgO_gain', 'numerical'),
            array('run_type', 'length', 'max'=>10),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('cal_id, SiO2_gain, SiO2_offset, Fe2O3_gain, Fe2O3_offset, Al2O3_gain, Al2O3_offset, CaO_gain, CaO_offset, MgO_offset, MgO_gain, run_type, updated_by, updated', 'safe', 'on'=>'search'),
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
            'cal_id' => 'Cal',
            'SiO2_gain' => 'SiO2 Gain',
            'SiO2_offset' => 'SiO2 Offset',
            'Fe2O3_gain' => 'Fe2O3 Gain',
            'Fe2O3_offset' => 'Fe2O3 Offset',
            'Al2O3_gain' => 'Al2O3 Gain',
            'Al2O3_offset' => 'Al2O3 Offset',
            'CaO_gain' => 'CaO Gain',
            'CaO_offset' => 'CaO Offset',
            'MgO_offset' => 'MgO Offset',
            'MgO_gain' => 'MgO Gain',
            'run_type' => 'Run Type',
            'base_record' => 'Base Record',
            'updated_by' => 'Updated By',
            'updated' => 'Updated',
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

        $criteria->compare('cal_id',$this->cal_id);
        $criteria->compare('SiO2_gain',$this->SiO2_gain);
        $criteria->compare('SiO2_offset',$this->SiO2_offset);
        $criteria->compare('Fe2O3_gain',$this->Fe2O3_gain);
        $criteria->compare('Fe2O3_offset',$this->Fe2O3_offset);
        $criteria->compare('Al2O3_gain',$this->Al2O3_gain);
        $criteria->compare('Al2O3_offset',$this->Al2O3_offset);
        $criteria->compare('CaO_gain',$this->CaO_gain);
        $criteria->compare('CaO_offset',$this->CaO_offset);
        $criteria->compare('MgO_offset',$this->MgO_offset);
        $criteria->compare('MgO_gain',$this->MgO_gain);
        $criteria->compare('run_type',$this->run_type,true);
        $criteria->compare('base_record',$this->base_record,true);
        $criteria->compare('updated_by',$this->updated_by);
        $criteria->compare('updated',$this->updated,true);

        return new CActiveDataProvider($this, array(
            'criteria'=>$criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return CalibrationLog the static model class
     */
    public static function model($className=__CLASS__)
    {
        return parent::model($className);
    }
}