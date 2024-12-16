<?php

/**
 * This is the model class for table "lab_data_history".
 *
 * The followings are the available columns in table 'lab_data_history':
 * @property integer $lab_hist_id
 * @property string $temp_name
 * @property string $sample_type
 * @property string $data
 * @property string $raw_data
 * @property integer $template_id
 * @property string $start_time
 * @property string $end_time
 * @property string $upload_time
 * @property integer $uploaded_by
 */
class LabDataHistory extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'lab_data_history';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('temp_name, template_id, upload_time, uploaded_by', 'required'),
			array('template_id, uploaded_by', 'numerical', 'integerOnly'=>true),
			array('temp_name', 'length', 'max'=>200),
			array('sample_type', 'length', 'max'=>100),
			array('data, raw_data, start_time, end_time', 'safe'),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('lab_hist_id, temp_name, sample_type, data, raw_data, template_id, start_time, end_time, upload_time, uploaded_by', 'safe', 'on'=>'search'),
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
			'lab_hist_id' => 'Lab Hist',
			'temp_name' => 'Temp Name',
			'sample_type' => 'Sample Type',
			'data' => 'Data',
			'raw_data' => 'Raw Data',
			'template_id' => 'Template',
			'start_time' => 'Start Time',
			'end_time' => 'End Time',
			'upload_time' => 'Upload Time',
			'uploaded_by' => 'Uploaded By',
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

		$criteria->compare('lab_hist_id',$this->lab_hist_id);
		$criteria->compare('temp_name',$this->temp_name,true);
		$criteria->compare('sample_type',$this->sample_type,true);
		$criteria->compare('data',$this->data,true);
		$criteria->compare('raw_data',$this->raw_data,true);
		$criteria->compare('template_id',$this->template_id);
		$criteria->compare('start_time',$this->start_time,true);
		$criteria->compare('end_time',$this->end_time,true);
		$criteria->compare('upload_time',$this->upload_time,true);
		$criteria->compare('uploaded_by',$this->uploaded_by);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return LabDataHistory the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
