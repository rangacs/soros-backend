<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of MonitorDashHelper
 *
 * @author veda
 */
define("TIME_CHECK", 0);

class AnalysisDataProvider {

    //put your code here
    private $elements;
    private $curDateTime;
    private $curTime;

    public function __construct() {
        
    }

    public function setElements($elements) {
        $this->elements = $elements;
    }

    public function geInterval() {

        return $this->interval;
    }

    public function getElements() {
        return $this->elements;
    }

    public function getData() {
        $dataProvider = array();


        foreach ($this->interval as $key => $interval) {

            if ($interval['type'] == 'time') {
                $intervalObject = new IntervalObject($interval['unit']);
            } else {
                $intervalObject = new TonsInterval($interval['unit']);

                $intervalObject->init();
            }

            $rowAvg = $this->getIntervalAvg($intervalObject);


            //IF result found skip row
            if (empty($rowAvg)) {
                $dataProvider[$key] = array();
            } else {

                $dataProvider[$key] = $rowAvg;
            }
        }

        return $dataProvider;
    }

    public function logTime($icntr) {
        $stTime = date("Y-m-d H:i:s");
        $sltime = time();

        $time_diff = round($sltime - $this->curTime, 2);

        if (TIME_CHECK)
            echo "Time $icntr:$stTime ($sltime) Diff: $time_diff  <br/>";

        $this->curDateTime = $stTime;
        $this->curTime = $sltime;
    }

    /**
     *
     * @param IntervalObject $intervalObject
     * @return array
     */
    public function getIntervalAvg($intervalObject) {

//        $this->logTime(21);
        $startDate = $intervalObject->getStartTime();
        $endTime = $intervalObject->getEndTime();


        $elements = HeliosUtility::getDisplayElements();

        $dElements = array();

        foreach ($elements as $ele) {
            $formula = Formulas::model()->find('name = :name', array(':name' => $ele));
            if ($formula) {
                $dElements[$ele] = "round(" . $formula->formula . " , 3) as $ele";
            } else {
                $dElements[$ele] = $ele;
            }
        }
        $dElements['LocalendTime'] = 'LocalendTime';
        $dElements['LocalstartTime'] = 'LocalstartTime';

        $select = implode(',', $dElements);

        $ansql = "select $select from analysis_A1_A2_Blend  where LocalendTime >= '" . $startDate . "'  AND LocalendTime <= '" . $endTime . "' AND totalTons != 0 ORDER BY LocalendTime DESC";
//        $elements = $select; // $this->getElements();

        $average = array();
        $dataResuts = Yii::app()->db->createCommand($ansql)->queryAll();

        $acQuery = 'select * from ac_settings ';
        $acResults = Yii::app()->db->createCommand($acQuery)->query();
        $acSettings = array();


        foreach ($acResults as $item) {
            $acSettings[$item['element_name']] = $item;
        }

        $query = "select * from rm_settings where varKey = 'ANALYZER_FILTER_BAD_RECORDS' ";
        $result = Yii::app()->db->createCommand($query)->queryRow();
        if ($result && $result['varValue']) {
            $setting_filter_bad_records = (int) $result['varValue'];
        }


        foreach ($dataResuts as $rawData) {

            //Filter Here
            if ($setting_filter_bad_records) {
                $row = DashHelper::validateAndSetAnalyzerRecordUsingRange($rawData, $acSettings);
            } else {
                $row = $rawData;
            }
            foreach (explode(',', $elements) as $ele) {

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

                $average[$ele][] = $row[$ele];
            }
        }


        $eleAvg = array();

        if (!empty($average)) {
            foreach ($average as $key => $tmpArray) {
                if ($key == 'totalTons') {
                    $sum = array_sum($tmpArray);
                    $eleAvg[$key] = round($sum, 2);

                    continue;
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
        $finagAvg = HeliosUtility::deriveAverage($row);
        return $finagAvg;
    }

    public static function queryAvg($elements, $LocalstartTime, $LocalendTime) {


        $startTime = date("Y-m-d H:i:s", strtotime($LocalstartTime));
        $endTime = $LocalendTime;

//        $ignoreColumns = ['dataId'];
        $query = "select varValue from rm_settings where varKey = 'MINIMUM_TPH'";
        $minTPH = (int) Yii::app()->db->createCommand($query)->queryScalar();

        foreach ($elements as $ele) {


            $formula = Formulas::model()->find('name = :name', array(':name' => $ele));
            if ($formula) {
                $avgCol[] = "round(avg(" . $formula->formula . ") , 2) as $ele";
                continue;
            }

            if ($ele == "totalTons") {
                $avgCol[] = "round(sum($ele) , 2) as $ele";
            } else if ($ele == "LocalendTime") {
                continue;
            } else {
                $avgCol[] = "round(avg($ele) , 2) as $ele";
            }
        }
        $colQuery = implode(' , ', $avgCol);

        $whereQuery = "where LocalendTime >= '{$startTime}' AND  LocalendTime <= '{$endTime}' AND totalTons > 0 AND TPH > $minTPH ";

        $sql = "select '$LocalstartTime' as LocalstartTime, '$LocalendTime' as LocalendTime, $colQuery from analysis_A1_A2_Blend " . $whereQuery;

        $results = Yii::app()->db->createCommand($sql)->queryRow();


        $finagAvg = HeliosUtility::deriveAverage($results);


        return $finagAvg;
    }

    public static function getDataByInterval($elements, $startTime, $endTime) {

        $query = "select varValue from rm_settings where varKey = 'MINIMUM_TPH'";
        $minTPH = (int) Yii::app()->db->createCommand($query)->queryScalar();

        foreach ($elements as $ele) {
            $formula = Formulas::model()->find('name = :name', array(':name' => $ele));
            if ($formula) {
                $dElements[$ele] = "round(" . $formula->formula . " , 3) as $ele";
            } else {
                $dElements[$ele] = $ele;
            }
        }
        $dElements['LocalendTime'] = 'LocalendTime';
        $dElements['LocalstartTime'] = 'LocalstartTime';

        $select = implode(',', $dElements);
        // echo $select ;
        $result = Yii::app()->db->createCommand()
                ->select($select)
                ->from('analysis_A1_A2_Blend')
                ->where("LocalendTime >= '$startTime'")
                ->andWhere("LocalendTime <= '$endTime'")
                ->queryAll();

        return $result;
    }

    public function getSubTagAvg($subTags) {

        $rmsquery = "select * from rm_settings where varKey = 'SOROS_DISPLAY_ELEMENTS' ";
        $result = Yii::app()->db->createCommand($rmsquery)->queryRow();
        if ($result && $result['varValue']) {
            $showCaseElements = $result['varValue'];
        }
        foreach ($subTags as $sTag) {

            $interval = new IntervalObject();

            $interval->setStartTime($sTag['LocalstartTime']);
            $interval->setEndTime($sTag['LocalendTime']);
            //setStartTime
            //setEndTime
            $analysisDataProvider = new AnalysisDataProvider();
            $analysisDataProvider->setElements($showCaseElements);

            $data[] = $analysisDataProvider->getIntervalAvg($interval);
        }
        $average = array();
        ;
        foreach ($data as $row) {
            foreach (explode(',', $showCaseElements) as $ele) {
                $average[$ele] [] = $row[$ele];
            }
        }

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

    public function getTagStd($tagObject) {


        $sql = "select * from rta_tag_index_sub_tag where tagID = '" . $tagObject->tagID . "'";
        $subTagList = Yii::app()->db->createCommand($sql)->query()->readAll();


        $rmsquery = "select * from rm_settings where varKey = 'SOROS_DISPLAY_ELEMENTS' ";
        $result = Yii::app()->db->createCommand($rmsquery)->queryRow();

        if ($result && $result['varValue']) {
            $showCaseElements = $result['varValue'];
        }

        $startTime = date("Y-m-d H:i:00", strtotime($tagObject->LocalstartTime));
        $endTime = $tagObject->LocalendTime;

//        $ignoreColumns = ['dataId'];
        $query = "select varValue from rm_settings where varKey = 'MINIMUM_TPH'";
        $minTPH = (int) Yii::app()->db->createCommand($query)->queryScalar();

        foreach (explode(",", $showCaseElements) as $ele) {


            $formula = Formulas::model()->find('name = :name', array(':name' => $ele));
            if ($formula) {
                $avgCol[] = "round(STDDEV(" . $formula->formula . ") , 2) as $ele";
                continue;
            }

            if ($ele == "totalTons") {
                $avgCol[] = "round(sum($ele) , 2) as $ele";
            } else if ($ele == "LocalendTime") {
                continue;
            } else {
                $avgCol[] = "round(STDDEV($ele) , 2) as $ele";
            }
        }
        $colQuery = implode(' , ', $avgCol);


        $dataQuery = array();

        if (count($subTagList) > 0) {


            foreach ($subTagList as $subTag) {


                $whereQuery = "where LocalendTime >= '" . $subTag['LocalstartTime'] . "' AND  LocalendTime <= '" . $subTag['LocalendTime'] . "' AND totalTons > 0 AND TPH > $minTPH ";

                $sql = "select * from analysis_A1_A2_Blend " . $whereQuery;
				
                $dataQuery[] = $sql;
            }

            $unionQuery = implode(" union ", $dataQuery);
        } else {

            $whereQuery = "where LocalendTime >= '" . $tagObject->LocalstartTime . "' AND  LocalendTime <= '" . $tagObject->LocalendTime . "' AND totalTons > 0 AND TPH > $minTPH ";

            $sql = "select * from analysis_A1_A2_Blend " . $whereQuery;
			
			
			

            $unionQuery = $sql;
        }
        $completeQuery = "select 'LocalstartTime' as LocalstartTime, 'LocalendTime' as LocalendTime, $colQuery from ( $unionQuery ) as s1";


        $results = Yii::app()->db->createCommand($completeQuery)->queryRow();

        $lsf_STD = $this->getLSFSTD($tagObject);

        $results['LSF_STD'] = $lsf_STD;

        return $results;
    }

    public function getTagAvg($tagObject) {


        $sql = "select * from rta_tag_index_sub_tag where tagID = '" . $tagObject->tagID . "'";
        $subTagList = Yii::app()->db->createCommand($sql)->query()->readAll();


        $rmsquery = "select * from rm_settings where varKey = 'SOROS_DISPLAY_ELEMENTS' ";
        $result = Yii::app()->db->createCommand($rmsquery)->queryRow();

        if ($result && $result['varValue']) {
            $showCaseElements = $result['varValue'];
        }

        $startTime = date("Y-m-d H:i:00", strtotime($tagObject->LocalstartTime));
        $endTime = $tagObject->LocalendTime;

//        $ignoreColumns = ['dataId'];
        $query = "select varValue from rm_settings where varKey = 'MINIMUM_TPH'";
        $minTPH = (int) Yii::app()->db->createCommand($query)->queryScalar();

        foreach (explode(",", $showCaseElements) as $ele) {


            $formula = Formulas::model()->find('name = :name', array(':name' => $ele));
            if ($formula) {
                $avgCol[] = "round(avg(" . $formula->formula . ") , 2) as $ele";
                continue;
            }

            if ($ele == "totalTons") {
                $avgCol[] = "round(sum($ele) , 2) as $ele";
            } else if ($ele == "LocalendTime") {
                continue;
            } else {
                $avgCol[] = "round(avg($ele) , 2) as $ele";
            }
        }
        $colQuery = implode(' , ', $avgCol);


        $dataQuery = array();

        if (count($subTagList) > 0) {


            foreach ($subTagList as $subTag) {



                $whereQuery = "where LocalendTime >= '" . $subTag['LocalstartTime'] . "' AND  LocalendTime <= '" . $subTag['LocalendTime'] . "' AND totalTons > 0 AND TPH > $minTPH ";

                $sql = "select * from analysis_A1_A2_Blend " . $whereQuery;
                $dataQuery[] = $sql;
            }

            $unionQuery = implode(" union ", $dataQuery);
        } else {

            $whereQuery = "where LocalendTime >= '" . $tagObject->LocalstartTime . "' AND  LocalendTime <= '" . $tagObject->LocalendTime . "' AND totalTons > 0 AND TPH > $minTPH ";

            $sql = "select * from analysis_A1_A2_Blend " . $whereQuery;

            $unionQuery = $sql;
        }
        $completeQuery = "select  'LocalstartTime' as LocalstartTime, 'LocalendTime' as LocalendTime, $colQuery from ( $unionQuery ) as s1";

        $results = Yii::app()->db->createCommand($completeQuery)->queryRow();

        $lsf_STD = $this->getLSFSTD($tagObject);

        $results['LSF_STD'] = $lsf_STD;

        $finagAvg = HeliosUtility::deriveAverage($results);
        return $finagAvg;
        
    }

    public function getTagData($tagObject, $page = 1, $pageSize = 50, $interval = 1 ) {


        $sql = "select * from rta_tag_index_sub_tag where tagID = '" . $tagObject->tagID . "'";
        $subTagList = Yii::app()->db->createCommand($sql)->query()->readAll();


        $rmsquery = "select * from rm_settings where varKey = 'SOROS_DISPLAY_ELEMENTS' ";
        $result = Yii::app()->db->createCommand($rmsquery)->queryRow();

        if ($result && $result['varValue']) {
            $showCaseElements = $result['varValue'];
        }

        $startTime = date("Y-m-d H:i:00", strtotime($tagObject->LocalstartTime));
        $endTime = $tagObject->LocalendTime;

//        $ignoreColumns = ['dataId'];
        $query = "select varValue from rm_settings where varKey = 'MINIMUM_TPH'";
        $minTPH = (int) Yii::app()->db->createCommand($query)->queryScalar();

        foreach (explode(",", "LocalendTime,".$showCaseElements) as $ele) {


            $formula = Formulas::model()->find('name = :name', array(':name' => $ele));
            if ($formula) {
                $avgCol[] = "round(avg(" . $formula->formula . ") , 2) as $ele";
                continue;
            }

            if ($ele == "totalTons") {
                $avgCol[] = "round(sum($ele) , 2) as $ele";
            } else if ($ele == "LocalendTime") {
                $avgCol[] = "date_format(LocalendTime,'%Y-%m-%d %H:%i:00') as LocalendTime";
                
            } else {
                $avgCol[] = "round(avg($ele) , 2) as $ele";
            }
        }
        
        $colQuery = implode(' , ', $avgCol);
        $dataQuery = array();
		
		
		$offset = ($pageSize  ) * ($page -1);

        $subInterval = $interval * 60;

        if (count($subTagList) > 0) {


            foreach ($subTagList as $subTag) {


                $whereQuery = "where LocalendTime >= '" . $subTag['LocalstartTime'] . "' AND  LocalendTime <= '" .
                 $subTag['LocalendTime'] . "' AND totalTons >= 0 AND TPH >= $minTPH GROUP BY
                UNIX_TIMESTAMP(LocalendTime) DIV $subInterval ";

                $sql = "select $colQuery from analysis_A1_A2_Blend " . $whereQuery;
                $dataQuery[] = $sql;
            }

            $unionQuery = implode(" union ", $dataQuery);
        } else {

            $whereQuery = "where LocalendTime >= '" . $tagObject->LocalstartTime . "' AND  LocalendTime <= '" . 
            $tagObject->LocalendTime . "' AND totalTons >= 0 AND TPH >= $minTPH GROUP BY
            UNIX_TIMESTAMP(LocalendTime) DIV $subInterval";

            $sql = "select $colQuery from analysis_A1_A2_Blend " . $whereQuery;

            $unionQuery = $sql;
        }
		
		
        $completeQuery = "select * from ( $unionQuery  order by LocalendTime desc  LIMIT $pageSize OFFSET $offset  ) as s1  order by LocalendTime desc ;";
	
        $countQuery = "select count(LocalendTime) as count from ( $unionQuery ) as s1 ;";


        $results = Yii::app()->db->createCommand($completeQuery)->queryAll();

        $curatedResult = array();

        foreach($results as $row){

            $dValue = array();
           $dValue = HeliosUtility::deriveAverage($row);
           $curatedResult[] = $dValue;
        }
        $totalRecords = Yii::app()->db->createCommand($countQuery)->queryScalar();

        return array('data' => $curatedResult , 'totalRecords' => $totalRecords);
    }

    public function getLSFSTD($tagObject) {


        $sql = "select * from rta_tag_index_sub_tag where tagID = '" . $tagObject->tagID . "'";
        $subTagList = Yii::app()->db->createCommand($sql)->query()->readAll();


        $startTime = date("Y-m-d H:i:00", strtotime($tagObject->LocalstartTime));
        $endTime = $tagObject->LocalendTime;

//        $ignoreColumns = ['dataId'];
        $query = "select varValue from rm_settings where varKey = 'MINIMUM_TPH'";
        $minTPH = (int) Yii::app()->db->createCommand($query)->queryScalar();

        foreach (array('LSF') as $ele) {


            $formula = Formulas::model()->find('name = :name', array(':name' => $ele));
            if ($formula) {
                $avgCol[] = "round(" . $formula->formula . " , 2) as $ele";
                continue;
            }
        }
        $colQuery = implode(' , ', $avgCol);

        $dataQuery = array();
		
		$lsf_result = array();
        if (count($subTagList) > 0) {


            foreach ($subTagList as $subTag) {

				
				$rest = $this->HourlyAverageLSFSTD($subTag['LocalstartTime'] , $subTag['LocalendTime']);
				$lsf_result[] = $rest;
			
            }

        } else {
			
			$rest = $this->HourlyAverageLSFSTD($tagObject->LocalstartTime, $tagObject->LocalendTime);
			$lsf_result[] = $rest;
		
            $unionQuery = $sql;
			
			
        }
		
		foreach($lsf_result  as $rdata){
			 foreach($rdata as $row){
				 //var_dump($row['LSF']);
				 if(!is_null($row['LSF'])){
					 $lsf_array[] = $row['LSF'];
				 }
				 
			 }
			
		}

		$std_new = LabUtility::stdDeviation($lsf_array);
	
        return round($std_new, 2);
    }
	
	
		public function HourlyAverageLSFSTD($startTime,$endTime) {
			
			
			$sql = "SELECT 
						DATE_FORMAT(LocalendTime, '%Y-%m-%d %H:00:00') AS hour,
						AVG( (CaO/(2.80*SiO2+1.18*Al2O3+0.65*Fe2O3)*100)	 ) AS avg_LSF,
						STDDEV(  (CaO/(2.80*SiO2+1.18*Al2O3+0.65*Fe2O3)*100)	) AS stddev_LSF
					FROM 
						analysis_A1_A2_Blend
					WHERE
						LocalendTime BETWEEN '$startTime' AND '$endTime'
					GROUP BY 
						DATE_FORMAT(LocalendTime, '%Y-%m-%d %H:00:00')
					ORDER BY 
						hour;";
						
						
			$results = Yii::app()->db->createCommand($sql)->queryAll();
			
			$lsf = LabUtility::getColumn($results, 'avg_LSF');
			
			$std = LabUtility::stdDeviation($lsf);
			
			return $std;
		
    }



}
