<?php

/**
 * This is the model class for table "rm_product_profile".
 *
 * The followings are the available columns in table 'rm_product_profile':
 * @property integer $product_id
 * @property integer $user_id
 * @property string $product_name
 * @property string $created_on
 * @property string $updated_on
 * @property double $target_flow
 * @property double $max_flow_deviation
 * @property integer $estimate_lsq_mins
 * @property double $sensitivity
 * @property integer $control_period_mins
 * @property double $actual_fpm
 * @property double $actual_tph
 * @property integer $default_profile
 * @property string $status
 * @property string $comment
 */
class ProductProfile extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'rm_product_profile';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('user_id, product_name, created_on, updated_on, target_flow, max_flow_deviation, estimate_lsq_mins, sensitivity, control_period_mins, actual_fpm, actual_tph, status, comment', 'required'),
            array('user_id, estimate_lsq_mins, control_period_mins, default_profile', 'numerical', 'integerOnly' => true),
            array('target_flow, max_flow_deviation, sensitivity, actual_fpm, actual_tph', 'numerical'),
            array('product_name, status, comment', 'length', 'max' => 100),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('product_id, user_id, product_name, created_on, updated_on, target_flow, max_flow_deviation, estimate_lsq_mins, sensitivity, control_period_mins, actual_fpm, actual_tph, default_profile, status, comment', 'safe', 'on' => 'search'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        // NOTE: you may need to adjust the relation name and the related
        // class name for the relations automatically generated below.
        return array(
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'product_id' => 'Product',
            'user_id' => 'User',
            'product_name' => 'Product Name',
            'created_on' => 'Created On',
            'updated_on' => 'Updated On',
            'target_flow' => 'Target Flow',
            'max_flow_deviation' => 'Max Flow Deviation',
            'estimate_lsq_mins' => 'Estimate Lsq Mins',
            'sensitivity' => 'Sensitivity',
            'control_period_mins' => 'Control Period Mins',
            'actual_fpm' => 'Actual Fpm',
            'actual_tph' => 'Actual Tph',
            'default_profile' => 'Default Profile',
            'status' => 'Status',
            'comment' => 'Comment',
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
    public function search() {
        // @todo Please modify the following code to remove attributes that should not be searched.

        $criteria = new CDbCriteria;

        $criteria->compare('product_id', $this->product_id);
        $criteria->compare('user_id', $this->user_id);
        $criteria->compare('product_name', $this->product_name, true);
        $criteria->compare('created_on', $this->created_on, true);
        $criteria->compare('updated_on', $this->updated_on, true);
        $criteria->compare('target_flow', $this->target_flow);
        $criteria->compare('max_flow_deviation', $this->max_flow_deviation);
        $criteria->compare('estimate_lsq_mins', $this->estimate_lsq_mins);
        $criteria->compare('sensitivity', $this->sensitivity);
        $criteria->compare('control_period_mins', $this->control_period_mins);
        $criteria->compare('actual_fpm', $this->actual_fpm);
        $criteria->compare('actual_tph', $this->actual_tph);
        $criteria->compare('default_profile', $this->default_profile);
        $criteria->compare('status', $this->status, true);
        $criteria->compare('comment', $this->comment, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    public static function getProgress($productid = NULL) {


        $progress = 0;

        if (isset($productid)) {



            $isProductExits = ProductProfile::model()->exists('product_id = :pid', array(':pid' => $productid));

            if ($isProductExits) {


                $progress += 25;

                $isSetPointExits = SetPoints::model()->exists('product_id = :pid', array(':pid' => $productid));
                if ($isSetPointExits)
                    $progress +=25;


                $isSourceExits = Source::model()->exists('product_id = :pid', array(':pid' => $productid));

                if ($isSourceExits) {
                    $progress += 25;
                    $sourceAll = Source::model()->findAll('product_id = :pid', array(':pid' => $productid));
                    $isElementExits = false;

                    if (!empty($sourceAll)) {

                        foreach ($sourceAll as $source) {

                            $isTmpElementExits = ElementComposition::model()->exists('source_id = :src_id', array(':src_id' => $source->src_id));

                            if ($isTmpElementExits)
                                $isElementExits = true;
                        }
                    }


                    if ($isElementExits)
                        $progress += 25;
                }
            }

        } else {
        }
        
        
        return $progress;
    }

    protected function beforeSave() {
        if (parent::beforeSave()) {
            if ($this->isNewRecord) {
                $this->created_on = $this->updated_on = new CDbExpression('NOW()');
                $this->user_id = Yii::app()->user->id;
                //$this->created_by=$this->updated_by=Yii::app()->user->id;
            } else {
                // $this->updated_by=Yii::app()->user->id;
                $this->updated_on = new CDbExpression('NOW()');
            }
            return true;
        } else
            return false;
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return ProductProfile the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

}
