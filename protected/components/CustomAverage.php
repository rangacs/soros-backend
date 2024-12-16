<?php



/**
 * Description of CustomAverage
 *
 * @author webtatva
 */
class CustomAverage {

    public $table;
    public $currentDateTime;
  
    public function __construct() {
        ;
    }
    
    
    public static  function getAverage($eleAvgArray,$spArray){
        
	
        $formulaAvg= array();
		$formVal =  0;
		if(count($eleAvgArray) == 1)
			return $formulaAvg;
        foreach ($spArray as $element_name){
            
             if ($element_name) {
                    if ($element_name == "LSF" && isset($eleAvgArray["CaO"])) {
                        $formVal = round((($eleAvgArray["CaO"]) /
                                (2.8 * $eleAvgArray["SiO2"] +
                                1.18 * $eleAvgArray["Al2O3"] +
                                0.65 * $eleAvgArray["Fe2O3"]) * 100), 2);
                    } else if ($element_name == "C3S" && isset($eleAvgArray["CaO"])) {
                        $formVal = round(((( 4.07 * $eleAvgArray["CaO"]) -
                                (7.6 * $eleAvgArray["SiO2"] +
                                6.72 * $eleAvgArray["Al2O3"] +
                                1.43 * $eleAvgArray["Fe2O3"] +
                                2.852 * $eleAvgArray["SO3"]))), 3);
                    } else if ($element_name == "SM" && isset($eleAvgArray["SiO2"])) {
                        $formVal = round((( $eleAvgArray["SiO2"]) /
                                ($eleAvgArray["Al2O3"] +
                                $eleAvgArray["Fe2O3"])), 3);
                    } else if ($element_name == "IM" && isset($eleAvgArray["SiO2"])) {
                        $formVal = round(( $eleAvgArray["Al2O3"] /
                                $eleAvgArray["Fe2O3"]), 3);
                    } else if ($element_name == "AM" && isset($eleAvgArray["SiO2"])) {
                        $formVal = round(( $eleAvgArray["Al2O3"] /
                                $eleAvgArray["Fe2O3"]), 3);
                    } else if ($element_name == "TPH") {
                        $formVal = round($results[0]['A_' . $value], 3);
                    }
					else if ($element_name == "KH" && isset($eleAvgArray["SiO2"])) {		
                       // $formVal = 0;//round($results[0]['A_' . $value], 3);
						
						$formVal = (( $eleAvgArray["CaO"] - 1.65 *  $eleAvgArray["Al2O3"] - 0.35 *  $eleAvgArray["Fe2O3"]) / (2.80 *  $eleAvgArray["SiO2"]));
                    }
                }
                
                $formulaAvg[$element_name] = $formVal;
        }
		
	
             return $formulaAvg; 
    }
    public function getIntervalAvg($startDate,$endTime)
    {
//        $startDate = $intervalObject->getStartTime();
//        $endTime = $intervalObject->getEndTime();
    $sql = "select * from analysis_A1_A2_Blend  where LocalendTime >= '" . $startDate . "'  AND LocalendTime <= '" . $endTime . "' ORDER BY LocalendTime DESC";
    //echo $sql . "<br/>";
        $elements =  $this->getElements();

    $average = array();
        $dataResuts = Yii::app()->db->createCommand($sql)->queryAll();

    $acQuery = 'select * from ac_settings ';
    $acResults = Yii::app()->db->createCommand($acQuery)->query();
    $acSettings = array();

    foreach ($acResults as $item) {
        $acSettings[$item['element_name']] = $item;
    }

        foreach ($dataResuts as $rawData) {
        $query = "select * from rm_settings where varKey = 'ANALYZER_FILTER_BAD_RECORDS' ";
        $result = Yii::app()->db->createCommand($query)->queryRow();
            if ($result && $result['varValue']) {
            $setting_filter_bad_records = (int) $result['varValue'];
            }
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
                if($ele == 'LocalstartTime'){
                     $row[$ele] = $startDate;
                    
                }
                elseif ($ele == 'LocalendTime'  ) {
                  
                $row[$ele] = $endTime;
                } elseif ($ele == 'KH') {
                    if ($tSiO2 > 0) {
                    $formulaVal = (($tCaO - 1.65 * $tAl2O3 - 0.35 * $tFe2O3) / (2.80 * $tSiO2));
                    } else {
                    $formulaVal = 0.0;
                    }
                $row[$ele] = $formulaVal;
                } elseif ($ele == "SM") {
                    if (($tAl2O3 + $tFe2O3) > 0) {
                        $formulaVal = round((($tSiO2) / ($tAl2O3 + $tFe2O3)), 2);
                    } else {
                    $formulaVal = 0.0;
                    }
                $row[$ele] = $formulaVal;
                } elseif ($ele == "AM") {
                    if ($tFe2O3 > 0) {
                        $formulaVal = round(($tAl2O3 / $tFe2O3), 2);
                    } else {
                        $formulaVal = 0.0;
                    }
                    $row[$ele] = $formulaVal;
                } elseif ($ele == "IM") {
                    if ($tFe2O3 > 0) {
                        $formulaVal = round(($tAl2O3 / $tFe2O3), 2);
                    } else {
                    $formulaVal = 0.0;
                    }
                $row[$ele] = $formulaVal;
            }


            //
                if ($row['totalTons'] !== 0) {
            $average[$ele][] = $row[$ele];
        }
    }
        }

    $eleAvg = array();
        
        if(!empty($average)){
    foreach ($average as $key => $tmpArray) {
        if ($key == 'totalTons') {
            $sum = array_sum($tmpArray);
                $eleAvg[$key] = round($sum, 2);

            continue;
        }



            if ($key == 'LocalendTime' ) {
            $eleAvg[$key] = ($tmpArray[0]);
            continue;
        }
            if ($key == 'LocalstartTime' ) {
                $eleAvg[$key] = array_pop($tmpArray);
                continue;
            }
        $count = count($tmpArray);
        $sum = array_sum($tmpArray);
        $avg = $sum / $count;
            $eleAvg[$key] = round($avg, 2);
    }

            
        }else{
            
            
            foreach($elements as $ele){
                $eleAvg[$ele] = '-';
            }
        }
     
    return $eleAvg;
    }
}
