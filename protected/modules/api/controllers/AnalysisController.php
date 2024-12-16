<?php

use yii\web\Response;
use yii\helpers\Json;

class AnalysisController extends BaseController {

    public function actionIndex() {
        
        $elements = HeliosUtility::getDisplayElements();
        
        $dElements = array();
        
        foreach($elements as  $ele){
            $formula = Formulas::model()->find('name = :name', array(':name' => $ele));
            if ($formula) {
                $dElements[$ele] = "round(".$formula->formula." , 3) as $ele";
            }else{
                $dElements[$ele] = " IF ( $ele >  0, $ele, 0) as $ele"; //$ele;
            }
        }
        $dElements['LocalendTime'] = 'LocalendTime';
        $dElements['LocalstartTime'] = 'LocalstartTime';
        
        $select =  implode(',',$dElements);
        $startTimeString = date("Y-m-d H:i:s", time() - (8 * 60 * 60));
        $endTimeString = date("Y-m-d H:i:s");
        $startTime = Yii::app()->request->getParam('start_time', $startTimeString);
        $endTime = Yii::app()->request->getParam('end_time', $endTimeString);
        $page = ((int) (Yii::app()->request->getParam('page', 0)));
        $pageSize = ((int) (Yii::app()->request->getParam('pageSize', 10)));

        $countResult = Yii::app()->db->createCommand()
                        ->select()
                        ->from('analysis_A1_A2_Blend')
                        ->where("LocalendTime >= '$startTime'")
                        ->andWhere("LocalendTime <= '$endTime'")->queryAll();

        $count = count($countResult);

        $result = Yii::app()->db->createCommand()
                ->select($select)
                ->from('analysis_A1_A2_Blend')
                ->where("LocalendTime >= '$startTime'")
                ->andWhere("LocalendTime <= '$endTime'")
                ->limit($pageSize)
                ->offset(($page -1) * $pageSize)
                ->order('LocalendTime DESC')
                ->queryAll();

        $this->sendSuccessResponse(array('data' => $result, 'totalRecords' => $count, 'page' => $page, 'pageSize' => $pageSize, $startTime, $endTime));
    }

    public function actionHourlyAverage() {

        $startTimeString = date("Y-m-d H:i:s", time() - (8 * 60 * 60));
        $endTimeString = date("Y-m-d H:i:s");
        $startTime = Yii::app()->request->getParam('start_time', $startTimeString);
        $endTime = Yii::app()->request->getParam('end_time', $endTimeString);
        $intervalStartTime = strtotime($startTime);
        $intervalEndTime = strtotime($startTime) + (60 * 60);
        $res = array();

	$pageSize = 10;
	$count = 0;
        while (($intervalEndTime) <= strtotime($endTime) ) {
            $intervalEndTimeTM = (date("Y-m-d H:i:s", $intervalEndTime));
            $intervalStartTimeTM = (date("Y-m-d H:i:s", $intervalStartTime));


            $sql = 'select COALESCE(Avg(MgO), 0 ) as MgO,LocalendTime,COALESCE(Avg(K2O), 0 ) as K2O
						from analysis_A1_A2_Blend
						where LocalendTime >= \'' . $intervalStartTimeTM . '\' and LocalendTime <= \'' . $intervalEndTimeTM . '\'';

            $result = Yii::app()->db->createCommand($sql)->queryAll();
            if ($result[0]['LocalendTime'] == NULL) {
                $result[0]['LocalendTime'] = $intervalStartTimeTM;
            }
            $res[] = $result;
            $intervalStartTime = ($intervalEndTime);
            $intervalEndTime = (($intervalEndTime) + (60 * 60));
	   // $count = $count + 1;
	    	
        }
        $this->sendSuccessResponse(array('data' => array_rever($res)));
    }
public function actionHourlyAverageElement() {

        $startTimeString = date("Y-m-d H:i:s", time() - (8 * 60 * 60 ));
        $endTimeString = date("Y-m-d H:i:s");
        $startTime = Yii::app()->request->getParam('start_time', $startTimeString);
        $endTime = Yii::app()->request->getParam('end_time', $endTimeString);
		
		
		
		$datetime1 = new DateTime(date('Y-m-d H:i:s',strtotime($startTime)));
		$datetime2 = new DateTime(date('Y-m-d H:i:s',strtotime($endTime)));

		$diffInSeconds = $datetime2->getTimestamp() - $datetime1->getTimestamp();
		$hours = round($diffInSeconds / 3600,0); // since there are 3600 seconds in an hour

		
		$datetime1 = strtotime(startTime);
		$datetime2 = strtotime($endTime);
		
		$diff = $datetime2 - $datetime1;
		
		

        $averageInterval = Yii::app()->request->getParam('averageInterval', 1);
        $temp = strtotime($startTime);
        
        $minutes = (int)date('i',(strtotime($startTime)));
        if($minutes > 30){
            $temp = $temp + ((60 - $minutes) * 60);
            
        }else{
            $temp = $temp - ($minutes * 60); 
        }
        $startTime = date("Y-m-d H:i:s", $temp);
        

        $elementArray = HeliosUtility::getDisplayElements();
        $intervalStartTime = strtotime($startTime);
        $intervalEndTime = strtotime($startTime) + (($averageInterval * 60 ) * 60 * 8);
        $res = array();
		
	$page = isset($_GET['page']) ? $_GET['page'] : 1; 

	$page = $page == 'undefined' ? 1 : $page;
		
	$count = 0;
	$pageSize = isset($_GET['rowsPerPage']) ? $_GET['rowsPerPage'] : 10;

	$pageSize = $pageSize  == 'undefined' ? 10 : $pageSize;
		
	$offset = ($page -1) * ( 10 );
		
	$offsetTime = ($offset *  $averageInterval) * (60 * 60);
		
	//Include offset
	$intervalStartTime =  $intervalStartTime +  $offsetTime;
	$intervalEndTime = $intervalStartTime  + (($averageInterval * 60) * 60 );
	$ist = $intervalStartTime;
	$iet = $intervalEndTime;

	
        while (($intervalStartTime ) <= strtotime($endTime) && ($count < $pageSize)) {

            $intervalEndTimeTM = (date("Y-m-d H:i:s", $intervalEndTime));
            $intervalStartTimeTM = (date("Y-m-d H:i:s", $intervalStartTime));
            $result = AnalysisDataProvider::queryAvg($elementArray, $intervalStartTimeTM, $intervalEndTimeTM);
            $res[] = $result;
            $intervalStartTime = ($intervalEndTime);
            $intervalEndTime = (($intervalEndTime) + ( ($averageInterval * 60) * 60));
            
           
            
             $rows = AnalysisDataProvider::getDataByInterval(array('LSF'),$intervalStartTimeTM,$intervalEndTimeTM);
           
            $lsf =  LabUtility::getColumn($rows,'LSF');
    
            $std =  LabUtility::stdDeviation($lsf);
            
            $res[$count]["LSF_STD"] =  $std;
            $count =  $count + 1;
            

			
        }
        
        $this->sendSuccessResponse(array(
		'ist'=> date("Y-m-d H:i:s", $ist),
		'iet'=> date("Y-m-d H:i:s", $iet),
		'end'=> $endTime,
		 'offsetTimer' => $offsetTime ,
		'data' =>($res) , 'totalCount' => $hours , 'pageSize' => $count));
    }

    public function actionAverage() {


        $startTimeString = date("Y-m-d H:i:s", time() - (8 * 60 * 60));
        $endTimeString = date("Y-m-d H:i:s");
        $startTime = Yii::app()->request->getParam('start_time', $startTimeString);
        $endTime = Yii::app()->request->getParam('end_time', $endTimeString);
        $elementArray = HeliosUtility::getDisplayElements();
        $avg = AnalysisDataProvider::queryAvg($elementArray, date("Y-m-d H:i:s", strtotime($startTime)), date("Y-m-d H:i:s", strtotime($endTime)));
        $rows = AnalysisDataProvider::getDataByInterval(array('LSF'),$startTime,$endTime);

        $lsf =  LabUtility::getColumn($rows,'LSF');
		$noFilterTons = LabUtility::getNoFilterTons($startTimeString ,$endTimeString);
        $avg['No_Filter_Tons'] = isset($noFilterTons) ? $noFilterTons : 0 ;

	if($avg['CaO'] > 0){
			$SiO2 = $avg['SiO2'];
	$Al2O3 = $avg['Al2O3'];
	$Fe2O3 = $avg['Fe2O3'];
	$CaO = $avg['CaO'];

	$sm = ($SiO2/($Al2O3+$Fe2O3));
	
	$avg['SM_OLD'] = $avg['SM'] ;// $sm;
	$avg['SM'] = $sm;



	}


        $std =  LabUtility::stdDeviation($lsf);
        
        $avg['LSF_STD'] =   $std;
        $this->sendSuccessResponse(array('data' => array($avg)));
    }

	public function actionAverageStd() {


        $startTimeString = date("Y-m-d H:i:s", time() - (8 * 60 * 60));
        $endTimeString = date("Y-m-d H:i:s");
        $defaultTimeInterval = 1;
        $startTime = Yii::app()->request->getParam('start_time', $startTimeString);
        $endTime = Yii::app()->request->getParam('end_time', $endTimeString);
        $interval = Yii::app()->request->getParam('interval', $defaultTimeInterval);
        $elementArray = HeliosUtility::getDisplayElements();
		
        $avg = AnalysisDataProvider::queryAvg($elementArray, date("Y-m-d H:i:s", strtotime($startTime)), date("Y-m-d H:i:s", strtotime($endTime)));
        $rows = AnalysisDataProvider::getDataByInterval(array('LSF'),$startTime,$endTime);

        $lsf =  LabUtility::getColumn($rows,'LSF');

        $std =  LabUtility::stdDeviation($lsf);
        
        $avg['LSF_STD'] =   $std;
        $subQury = array();
        $stdSelect = array();
        foreach($elementArray as $key){
            $formula = Formulas::model()->find('name = :name', array(':name' => $key));
            if ($formula) {
                $stdSelect[] = "round( AVG(".$formula->formula.") , 3) as $key";
            }else{
            $stdSelect[] = " round( AVG( $key ) , 3)  as $key";
            }
        }
         
        $subQury[] =   " (LocalendTime > '".$startTime."' AND LocalendTime < '".$endTime."' )";

        $timeInterval = " DATE_FORMAT(DATE_SUB(LocalendTime, INTERVAL MINUTE(LocalendTime) % $interval MINUTE), '%Y-%m-%d %H:%i:00') AS time_interval,";
        
        $selectsql = " select $timeInterval  ".implode(',', $stdSelect );
        $whereSql =  " where ".implode(' or ', $subQury);
        $groupBy = "GROUP BY 
        DATE_FORMAT(DATE_SUB(LocalendTime, INTERVAL MINUTE(LocalendTime) % $interval MINUTE), '%Y-%m-%d %H:%i:00')
        ORDER BY 
        time_interval";

        $average= Yii::app()->db->createCommand($selectsql ." from analysis_A1_A2_Blend ".$whereSql . " ".$groupBy)->queryAll();


        foreach($elementArray as $key){
            
			$eleArray = LabUtility::getColumn($average, $key);
			
			$eleStd = LabUtility::stdDeviation($eleArray);
            $standardDeviation[$key] = $eleStd;
        }
        
        $standardDeviation['LocalstartTime'] = 'Standard Deviation';
        $standardDeviation['LocalendTime'] = '-';
        // $standardDeviation['LSF_STD'] = "-";
        $standardDeviation['totalTons'] = "-";
        
        $this->sendSuccessResponse(array('data' => array($avg,$standardDeviation)));
    }


    public function actionIntervalAverage() {
        
        $interval = (int) Yii::app()->request->getParam('interval', 1);
        $startTimeString = date("Y-m-d H:i:s", time() - (($interval ) * 60));
        $endTimeString = date("Y-m-d H:i:s");
        $element = HeliosUtility::getDisplayElements();
       
        $avg = AnalysisDataProvider::queryAvg($element, $startTimeString, $endTimeString);
        
	$SAvg =  $avg;;
        $rows = AnalysisDataProvider::getDataByInterval(array('LSF'),$startTimeString,$endTimeString);
        
        $lsf =  LabUtility::getColumn($rows,'LSF');
	$noFilterTons = LabUtility::getNoFilterTons($startTimeString ,$endTimeString);
        $avg['No_Filter_Tons'] = $noFilterTons;

        foreach($avg as $key =>  $value){

	if($value < 0){
	$avg[$key] = 0;
	}

	}

		if($avg['CaO'] > 0){
			$SiO2 = $avg['SiO2'];
			$Al2O3 = $avg['Al2O3'];
			$Fe2O3 = $avg['Fe2O3'];
			$CaO = $avg['CaO'];

			$sm = ($SiO2/($Al2O3+$Fe2O3));
	
			$avg['SM_OLD'] = $avg['SM'] ;// $sm;
			$avg['SM'] = $sm;

	}

        $std =  LabUtility::stdDeviation($lsf);
        
        $avg['LSF_STD'] =   $std;
        $this->sendSuccessResponse(array('data' => $avg ));
    }

    public function actionTonsRange() {
        $sumTons = (int) Yii::app()->request->getParam('tons');

        $sql = "select max(dataID) from analysis_A1_A2_Blend";
        $maxId = Yii::app()->db->createCommand($sql)->queryScalar();
        $totalTonSum = 0;
        $count = 0;
        $rowsEle = array();
        $time_array = array();
        for ($i = $maxId; $i > 0; $i--) {
            if ($totalTonSum <= $sumTons) {
                $rowQuery = 'select LocalendTime,totalTons  from analysis_A1_A2_Blend where dataID = ' . $i . '';
                $rowElements = Yii::app()->db->createCommand($rowQuery)->queryRow();
                $time_array[] = $rowElements['LocalendTime'];
                $totalTons = $rowElements['totalTons'];
                $totalTonSum = $totalTonSum + $totalTons;
            } else {
                break;
            }
        }
        $endTime = $time_array[0];
        $startTime = array_pop($time_array);

        $sql = "select varValue from rm_settings where varKey = 'SOROS_ELEMENTS'";
        $res = Yii::app()->db->createCommand($sql)->queryScalar();
        $elements = explode(',', $res);

        $result = AnalysisDataProvider::queryAvg($elements, $startTime, $endTime);
        $rows = AnalysisDataProvider::getDataByInterval(array('LSF'),$startTime,$endTime);

        $lsf =  LabUtility::getColumn($rows,'LSF');

        $std =  LabUtility::stdDeviation($lsf);
        
        $result['LSF_STD'] =  $std;

        $this->sendSuccessResponse(array('data' => $result));
    }

}
