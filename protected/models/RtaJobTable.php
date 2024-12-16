<?php

/**
 * This is the model class for table "rta_job_table".
 *
 * The followings are the available columns in table 'rta_job_table':
 * @property integer $jobID
 * @property string $jobStatus
 * @property integer $linuxPID
 * @property string $start_time
 * @property string $end_time
 * @property string $backupTable
 * @property string $tempTable
 * @property string $regenTable
 * @property string $originalTable
 * @property integer $originalTableID
 * @property integer $loopsFinished
 * @property integer $recordsRemaining
 * @property integer $recordsTotal
 * @property integer $maxID
 * @property string $dateAdded
 * @property string $dateModified
 * @property string $dateCompleted
 * @property string $userAdded
 * @property string $userModified
 */
class RtaJobTable extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'rta_job_table';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('linuxPID, start_time, end_time, backupTable, tempTable, regenTable, originalTable, originalTableID, userModified', 'required'),
			array('linuxPID, originalTableID, loopsFinished, recordsRemaining, recordsTotal, maxID', 'numerical', 'integerOnly'=>true),
			array('jobStatus', 'length', 'max'=>11),
			array('backupTable, tempTable, regenTable, originalTable', 'length', 'max'=>40),
			array('userAdded, userModified', 'length', 'max'=>20),
			array('dateAdded, dateModified, dateCompleted', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('jobID, jobStatus, linuxPID, start_time, end_time, backupTable, tempTable, regenTable, originalTable, originalTableID, loopsFinished, recordsRemaining, recordsTotal, maxID, dateAdded, dateModified, dateCompleted, userAdded, userModified', 'safe', 'on'=>'search'),
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
			'jobID' => 'Job',
			'jobStatus' => 'Job Status',
			'linuxPID' => 'Linux Pid',
			'start_time' => 'Start Time',
			'end_time' => 'End Time',
			'backupTable' => 'Backup Table',
			'tempTable' => 'Temp Table',
			'regenTable' => 'Regen Table',
			'originalTable' => 'Original Table',
			'originalTableID' => 'Original Table',
			'loopsFinished' => 'Loops Finished',
			'recordsRemaining' => 'Records Remaining',
			'recordsTotal' => 'Records Total',
			'maxID' => 'Max',
			'dateAdded' => 'Date Added',
			'dateModified' => 'Date Modified',
			'dateCompleted' => 'Date Completed',
			'userAdded' => 'User Added',
			'userModified' => 'User Modified',
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

		$criteria->compare('jobID',$this->jobID);
		$criteria->compare('jobStatus',$this->jobStatus,true);
		$criteria->compare('linuxPID',$this->linuxPID);
		$criteria->compare('start_time',$this->start_time,true);
		$criteria->compare('end_time',$this->end_time,true);
		$criteria->compare('backupTable',$this->backupTable,true);
		$criteria->compare('tempTable',$this->tempTable,true);
		$criteria->compare('regenTable',$this->regenTable,true);
		$criteria->compare('originalTable',$this->originalTable,true);
		$criteria->compare('originalTableID',$this->originalTableID);
		$criteria->compare('loopsFinished',$this->loopsFinished);
		$criteria->compare('recordsRemaining',$this->recordsRemaining);
		$criteria->compare('recordsTotal',$this->recordsTotal);
		$criteria->compare('maxID',$this->maxID);
		$criteria->compare('dateAdded',$this->dateAdded,true);
		$criteria->compare('dateModified',$this->dateModified,true);
		$criteria->compare('dateCompleted',$this->dateCompleted,true);
		$criteria->compare('userAdded',$this->userAdded,true);
		$criteria->compare('userModified',$this->userModified,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return RtaJobTable the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
