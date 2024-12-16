<?php

/**
 * This is the model class for table "lab_template".
 *
 * The followings are the available columns in table 'lab_template':
 * @property integer $template_id
 * @property string $file_name
 * @property string $skip_row
 * @property string $skip_col
 * @property string $columns
 * @property integer $uploaded_by
 * @property string $uploaded_at
 * @property integer $status
 * @property string $content
 */
class LabTemplate extends CActiveRecord
{
	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'lab_template';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('file_name, skip_row, skip_col, columns, uploaded_by, uploaded_at, status, content', 'required'),
			array('uploaded_by, status', 'numerical', 'integerOnly'=>true),
			array('file_name, skip_row, skip_col', 'length', 'max'=>200),
			// The following rule is used by search().
			// @todo Please remove those attributes that should not be searched.
			array('template_id, file_name, skip_row, skip_col, columns, uploaded_by, uploaded_at, status, content', 'safe', 'on'=>'search'),
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
			'template_id' => 'Template',
			'file_name' => 'File Name',
			'skip_row' => 'Skip Row',
			'skip_col' => 'Skip Col',
			'columns' => 'Columns',
			'uploaded_by' => 'Uploaded By',
			'uploaded_at' => 'Uploaded At',
			'status' => 'Status',
			'content' => 'Content',
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

		$criteria->compare('template_id',$this->template_id);
		$criteria->compare('file_name',$this->file_name,true);
		$criteria->compare('skip_row',$this->skip_row,true);
		$criteria->compare('skip_col',$this->skip_col,true);
		$criteria->compare('columns',$this->columns,true);
		$criteria->compare('uploaded_by',$this->uploaded_by);
		$criteria->compare('uploaded_at',$this->uploaded_at,true);
		$criteria->compare('status',$this->status);
		$criteria->compare('content',$this->content,true);

		return new CActiveDataProvider($this, array(
			'criteria'=>$criteria,
		));
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return LabTemplate the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
}
