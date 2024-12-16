<?php

class ChartController extends BaseController {

    public function actionIndex() {
        $element = Yii::app()->request->getParam('elements', 'MgO');
        $startTimeString = date("Y-m-d H:i:s", time() - (8 * 60 * 60));
        $endTimeString = date("Y-m-d H:i:s");
        $rolling = Yii::app()->request->getParam('rolling', '0');
        $startTime = Yii::app()->request->getParam('start_time', $startTimeString);
        $endTime = Yii::app()->request->getParam('end_time', $endTimeString);
        $query = "select varValue from rm_settings where varKey = 'MINIMUM_TPH'";
        $minTPH = (int) Yii::app()->db->createCommand($query)->queryScalar();
//        echo "list";
        $formula = Formulas::model()->find('name = :name', array(':name' => $element));
        
            if ($formula) {
                $avgCol[] = "round((" . $formula->formula . ") , 3) as $element";
            }
            $startTime = date("Y-m-d H:i:s",strtotime($startTime));
            $endTime = date("Y-m-d H:i:s",strtotime($endTime));

            $colQuery = implode(' , ', $avgCol);
            if (!$formula) {
                $colQuery = "$element";
            }
        
        $whereQuery = "where LocalendTime >= '{$startTime}' AND  LocalendTime <= '{$endTime}' AND totalTons > 0 AND TPH > $minTPH ";

        $sql = "select  LocalendTime, $colQuery from analysis_A1_A2_Blend " . $whereQuery;
       
        $results = Yii::app()->db->createCommand($sql)->queryAll();
        
        $this->sendSuccessResponse(array('data' => $results,'sql' => $sql));
       
         //SELECT `LocalendTime`, `Al2O3` FROM `analysis_A1_A2_Blend` WHERE (LocalendTime >= '2022-05-11 02:54:18') AND (LocalendTime <= '2022-05-11 10:54:18')" string(19) "2022-05-11 02:54:18" string(19) "2022-05-11 10:54:18"
    }
    protected function makeRollingMinues($data, $elements) {

        $rollingData = array();
        $rollingMin = 15; // TODO , Place in proper location

        $index = 0;

        foreach ($data as $row) {

            $rollingCount = $rollingMin - 1;

            if ($index < $rollingCount) {

                $size = $index;
                $offset = 0;
            } else {

                $size = $rollingCount;

                $offset = $index - $rollingCount;
            }
            if ($size != 0) {
		$sliceAr = array_slice($data, $offset, $size, true);
		$avg = $this->roollingAvg($sliceAr, $elements, $row);
		$rollingData[] = $avg;
		// $index++;
            }else{
            	$rollingData[] = $data[$index];
            }
            $index++;
        }

        return $rollingData;
    }

    private function roollingAvg($array, $elements, $row) {

        $avg = array();
        foreach ($elements as $ele) {

            $arrayValues = LabUtility::getColumn($array, $ele);

            if ($ele === 'LocalendTime') {

                $avg[$ele] = $row[$ele]; //array_pop($arrayValues);
            } else if ($ele == 'totalTons') {

                $arraySum = array_sum($arrayValues) + $row [$ele];

                $avg[$ele] = round($arraySum, 3);
            } else {


                $arraySum = array_sum($arrayValues);
                if ($arraySum > 0) {

                    $tmpAvg = $arraySum / count($arrayValues);
                    $avg[$ele] = round($tmpAvg, 3);
                } else {

                    $avg[$ele] = 0;
                }
            }
        }

        return $avg;
    }

}
