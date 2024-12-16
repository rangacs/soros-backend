<?php

/**
 * This is the model class for table "rta_tag_index_queued".
 *
 * The followings are the available columns in table 'rta_tag_index_queued':
 * @property string $tagID
 * @property string $rtaMasterID
 * @property string $status
 * @property string $tagName
 * @property string $tagGroupID
 * @property string $LocalstartTime
 * @property string $LocalendTime
 */
class TagQueued extends CActiveRecord {

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'rta_tag_index_queued';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('rtaMasterID, status, tagName, tagGroupID', 'required'),
            array('rtaMasterID', 'length', 'max' => 10),
            array('status', 'length', 'max' => 9),
            array('tagName', 'length', 'max' => 30),
            array('tagGroupID', 'length', 'max' => 10),
            array('LocalstartTime, LocalendTime, hasMerge', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('tagID, rtaMasterID, status, tagName, tagGroupID, LocalstartTime, LocalendTime', 'safe', 'on' => 'search'),
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

    public function beforeSave() {
        if ($this->isNewRecord) {
            $tConnection = Yii::app()->db;
            //Close all other open tags.
            $queuedTags = TagQueued::model()->findAll();
            foreach ($queuedTags as $qTag) {
                if (isset($qTag->tagID)) {

                    $tagUPDQ = "UPDATE rta_tag_index_queued SET LocalendTime=now() where tagID='" . $qTag->tagID . "'";
                    $tCommand = $tConnection->createCommand($tagUPDQ);
                    $tCommand->execute();
                }
            }//foreach
        }

        return parent::beforeSave();
    }

    public function loadQTModel($qid) {
        $tmodel = TagQueued::model()->findByPk($qid);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $tmodel;
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels() {
        return array(
            'tagID' => 'Tag',
            'rtaMasterID' => 'Rta Master',
            'status' => 'Status',
            'tagName' => 'Tag Name',
            'tagGroupID' => 'Tag Group',
            'LocalstartTime' => 'Localstart Time',
            'LocalendTime' => 'Localend Time',
            'hasMerge ' => "Has Merged Tag"
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

        $criteria->compare('tagID', $this->tagID, true);
        $criteria->compare('rtaMasterID', $this->rtaMasterID, true);
        $criteria->compare('status', $this->status, true);
        $criteria->compare('tagName', $this->tagName, true);
        $criteria->compare('tagGroupID', $this->tagGroupID, true);
        $criteria->compare('LocalstartTime', $this->LocalstartTime, true);
        $criteria->compare('LocalendTime', $this->LocalendTime, true);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return TagQueued the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * 
     * @return array
     */
    public function getTime() {

        $timeStamp = array('LocalendTime' => $this->LocalstartTime, 'LocalstartTime' => $this->LocalendTime);
        return $timeStamp;
    }

    public function getRecords() {

        $tagID = $this->tagID;
        $subTags = SubTag::model()->findAll("tagID=:tagid", array(":tagid" => $tagID));
        $data = array();

        //If tag has sub tags 
        if (count($subTags) > 0) {

            foreach ($subTags as $sTag) {

                $analysisDataProvidor = new AnalysisDataObject($sTag->LocalstartTime, $sTag->LocalendTime);
                $getRecords = $analysisDataProvidor->getRecords();
                $data[] = $getRecords;
            }
//        var_dump($data);
            $values = array();
            foreach ($data as $temp) {
                foreach ($temp as $row) {
                    $key = strtotime($row["LocalendTime"]);
                    $values[$key] = $row;
                }
            }
            $records = array_values($values);
        } else {
            $analysisDataProvidor = new AnalysisDataObject($this->LocalstartTime, $this->LocalendTime);
            $records = $analysisDataProvidor->getRecords();
        }
//        var_dump($values);
//        die();
        return $records;
    }

    public function getTagAvg() {

        $this->logTime(21);
        $startDate  = $this->LocalstartTime;
        $endTime    = $this->LocalendTime;

        $rmsquery = "select * from rm_settings where varKey = 'SOROS_DISPLAY_ELEMENTS' ";
        $result = Yii::app()->db->createCommand($rmsquery)->queryRow();
        if ($result && $result['varValue']) {
            $showCaseElements = $result['varValue'];
        }

        $tagID = $this->tagID;
        $subTags = SubTag::model()->findAll("tagID=:tagid", array(":tagid" => $tagID));
        $data = array();
        $elements = $this->getElements();

        foreach ($subTags as $sTag) {

            $ansql = "select $showCaseElements from analysis_A1_A2_Blend  where LocalendTime >= '" . $sTag->LocalstartTime . "'  AND LocalendTime <= '" . $sTag->LocalendTime . "' AND totalTons != 0 ORDER BY LocalendTime DESC";

            $average = array();
            $getRecords = Yii::app()->db->createCommand($ansql)->queryAll();

            $data[] = $getRecords;
        }
//        var_dump($data);
        $dataResuts = array();
        foreach ($data as $temp) {
            foreach ($temp as $row) {
                $dataResuts[] = $row;
            }
        }
        $average = array();

        $acQuery = 'select * from ac_settings ';
        $acResults = Yii::app()->db->createCommand($acQuery)->query();
        $acSettings = array();

        $this->logTime(22);

        foreach ($acResults as $item) {
            $acSettings[$item['element_name']] = $item;
        }

        $query = "select * from rm_settings where varKey = 'ANALYZER_FILTER_BAD_RECORDS' ";
        $result = Yii::app()->db->createCommand($query)->queryRow();
        if ($result && $result['varValue']) {
            $setting_filter_bad_records = (int) $result['varValue'];
        }

        $this->logTime(23);


        foreach ($dataResuts as $rawData) {

            //Filter Here
            if ($setting_filter_bad_records) {
                $row = DashHelper::validateAndSetAnalyzerRecordUsingRange($rawData, $acSettings);
            } else {
                $row = $rawData;
            }

            foreach ($elements as $ele) {


                $tAl2O3 = (float) $row['Al2O3'];

                $tSiO2 = (float) $row['SiO2'];

                $tFe2O3 = (float) $row['Fe2O3'];

                $tCaO = (float) $row['CaO'];


                //Calculate formulas
                if ($ele == 'LocalstartTime') {
                    $row[$ele] = $startDate;
                } elseif ($ele == 'LocalendTime') {

                    $row[$ele] = $endTime;
                }
                
                if ($row['totalTons'] > 0) {
                    $average[$ele][] = $row[$ele];
                }
            }
        }
        $this->logTime(24);

        $eleAvg = array();

        if (!empty($average)) {
            foreach ($average as $key => $tmpArray) {
                if ($key == 'totalTons') {
                    $sum = array_sum($tmpArray);
                    $eleAvg[$key] = round($sum, 2);

                    continue;
                }

                if ($key == 'LocalendTime') {
                    $eleAvg[$key] = ($tmpArray[0]);
                    continue;
                }
                if ($key == 'LocalstartTime') {
                    $eleAvg[$key] = array_pop($tmpArray);
                    continue;
                }
                $count = count($tmpArray);
                $sum = array_sum($tmpArray);
                $avg = $sum / $count;
                $eleAvg[$key] = round($avg, 2);
            }
        } else {


            foreach ($elements as $ele) {
                $eleAvg[$ele] = '-';
            }
        }

        return $eleAvg;
    }

    public function getAverage($elements) {


        $avgCol = array();


        $tagID = $this->tagID;
        $subTags = SubTag::model()->findAll("tagID=:tagid", array(":tagid" => $tagID));
        $avg_records = array();
        foreach ($subTags as $sTag) {

            foreach ($elements as $ele) {

                if ($ele == "totalTons") {
                    $avgCol[] = "round(sum($ele) , 3) as $ele";
                } else {
                    $avgCol[] = "round(avg($ele) , 3) as $ele";
                }
            }
            $colQuery = implode(' , ', $avgCol);
            $startTime = $sTag->LocalstartTime; //"2021-07-15 04:13:47"; 
            $endTime = $sTag->LocalendTime; //"2021-07-15 05:13:47";
            $whereQuery = "where LocalendTime >= '{$startTime}' AND  LocalendTime <= '{$endTime}'";

            $sql = "select min(LocalendTime) as LocalStartTime, max(LocalendTime) as LocalendTime, $colQuery from analysis_A1_A2_Blend " . $whereQuery;
            $results = Yii::app()->db->createCommand($sql)->queryAll();
            $avg_records[] = $results;
        }

        
        $simple_avg = $this->averageRecords($avg_records, $elements);
        
        $simple_avg["LocalendTime"] =  $this->LocalendTime;
        $simple_avg["LocalstartTime"] =  $this->LocalstartTime;
        return $simple_avg;
    }

    public function averageRecords($records, $elements) {

//        var_dump($records);

        $data = array();
        $avg = array();
        foreach ($records as $row) {

            foreach ($elements as $ele) {

                if($row[0]['totalTons'] > 0){
                    $data[$ele][] = $row[0][$ele];
                }
                 
            }
        }

        foreach ($elements as $ele) {

            if ($ele == "totalTons") {
                $sum = array_sum($eleData);
                $avg[$ele] = $sum / $count;
                continue;
            }
            $eleData = $data[$ele];
            $count = count($eleData);
            $sum = array_sum($eleData);
            $avg[$ele] = $sum / $count;
        }

        return $avg;
    }

}
