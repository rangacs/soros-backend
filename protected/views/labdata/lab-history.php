<?php


$analysisTime = array();



$isAdmin = Yii::app()->user->id === 1 ? true : false; // is admin

$isManager = Yii::app()->user->id === 45 ? true : false; // is manager
//$superAdmin = Yii::app()->user->hasRole('superadmin');


$canedit = $isManager || $isAdmin ? true : true; //TODO

if (null == $dataProvider) {
    $labLink = CHtml::link('<i class="icon-settings"></i> Lab Data', array('labdata/index', 'lab_hist_id' => $record['lab_hist_id'], 'rid' => $record['lab_hist_id']), array('class' => 'profile-link btn red lg'));

    echo "<div class='note note-danger'>Problem loading lab data. Click here for $labLink </div>";
} else {

    $data = $dataProvider->getData();
    foreach ($data as $key => $value) {
        if (empty($value))
            continue;
        $analysisTimeArray[] = date('Y-m-d H:i:s', strtotime($value['EndTime']));
    }

    $elemnts = array();


    $table =getSetting('rm_settings', 'LAB_COMPARE_TABLE');

    //moved from hours to minutes
    $samplingResult =getSetting('rm_settings', 'LAB_HISTORY_SAMPLING_TIME');
    $samplingTime = (int)$samplingResult*60;

    $fallBackResult =getSetting('rm_settings', 'LAB_HISTORY_FALLBACK_TIME');
    $fallbackTime = (int)$fallBackResult*60;

  //  var_dump($samplingTime.'sampling time');
    
   // var_dump($fallbackTime.'fallbackTime');



    $coalEleList = array('Moisture', 'So2', 'Ash', 'Sulfur', 'BTU', 'MAFBTU');
    $cementEleList = array('Al2O3', 'SiO2', 'Fe2O3', 'CaO',  'SM', 'AM');


    $avgQuery = array();
    foreach ($columnReader as $col) {

        $avgQuery[] = "avg($col)  as AN_$col";
    }
    $selCol = implode(' , ', $avgQuery);
    $avgSql = " select $selCol from lab_history_data";
	
    //var_dump($avgSql);
//$ignoreColums   = array('dataID', 'rtaMasterID', 'startTic', 'endTic', 'LocalstartTime', 'LocalendTime', 'GMTendTime', 'goodDataSecs', 'avgMassFlowTph', 'totalTons');
//$ignoreColums   = array();

    $labAvg = Yii::app()->db->createCommand($avgSql)->queryRow();
	$elemts = $columnReader;

    $eleCount = count($elemts);
    $analysisObject = new AnalysisHistoryFactory($analysisTimeArray, $table);
    $analysisObject->getRecords();
    
    $result =getSetting('lab_data_settings', 'COMPARISION_ELEMENTS');
    $existingEleList = array();

    $labSamplingTime =getSetting('rm_settings', 'LAB_HISTORY_SAMPLING_TIME');

    $labFbTime =getSetting('rm_settings', 'LAB_HISTORY_FALLBACK_TIME');

    $avgDataVector = array();

    foreach ($analysisObject->analysisAvg as $key => $row) {
        $count = 0;
        foreach ($row as $col => $colvalue) {

            $avgDataVector[$col][] = $colvalue;
        }
    }

	//var_dump($avgDataVector);
//	die();
    $database = Yii::app()->db->createCommand("SELECT DATABASE()")->queryScalar();

    $colquery = "SELECT COUNT(*)  FROM INFORMATION_SCHEMA.COLUMNS
                                                WHERE table_schema = '" . $database . "'
                                                  AND table_name = 'lab_history_data'";

    $colInfo = Yii::app()->db->createCommand("SELECT COLUMN_NAME  FROM INFORMATION_SCHEMA.COLUMNS
                                                WHERE table_schema = '" . $database . "'
                                                  AND table_name = 'lab_history_data'")->query()->readAll();


	foreach($colInfo as $col){

		$labTempColumns[] =  $col['COLUMN_NAME'];
	}


   
//remove unwanted column 
	unset($labTempColumns[0]);
        unset($labTempColumns[1]);



                                    //Al2O3,SiO2,Fe2O3,CaO,LSF, C3S, KH , SM, IM, AM
    $existingEleList = explode(',', 'SiO2,Al2O3,Fe2O3,CaO,KH,SM,IM'); //$labTempColumns;


    $totalEle = count($existingEleList);
    $labDataquery = "select * from lab_history_data";
    $labDatacommand = Yii::app()->db->createCommand($labDataquery);
    $labData = $labDatacommand->query()->readAll();
    ?>




    <div id="settings" class="portlet box blue">
        <div class="portlet-title">
            <div class="caption">
                <i class="fa fa fa-flask"></i><?php echo Yii::t('app', 'Settings') ?>        
    <!--            Sampling time <span class="text-red"><?php echo $labSamplingTime ?> </span>     ---  Fallback Time <span class="text-red"><?php echo $labFbTime; ?> </span>
                -->
            </div>
            <div class="tools grey">

            </div>
        </div>
        <div class="portlet-body " >
            <form action="" method="post" class="form-inline">

                <div class="form-group">

                    <label class="form-label" for="form-name"><?php echo Yii::t('app', 'Sampling Duration') ?> </label>

                    <input type="text" name="sampling_time" id="sampling_time" value="<?php echo $labSamplingTime ?>" class="input form-control timepick" size="5" maxlength="5" />


                </div>


                <div class="form-group">

                    <label class="form-label" for="form-name"><?php echo Yii::t('app', 'Processing Duration') ?> </label>
                    <input type="text" name="fallback_time" id="fallback_time" value="<?php echo $labFbTime ?>" class="input form-control timepick" size="5" maxlength="5" />



                </div>


 


                <div class="form-group">
                    <button aria-disabled="false" role="button" class="btn blue" type="button" data-icon-primary="ui-icon-circle-check" id="" onclick="setTimeVariable()"><span class="ui-button-icon-primary ui-icon ui-icon-circle-check"></span><span class="ui-button-text"><?php echo Yii::t('dash', 'SUBMIT'); ?></span></button>  

                                        <!--<a href="" class="btn blue btn-outline"> Refresh <i class="fa fa-refresh"></i></a>-->
                </div>   

            </form>

        </div>
    </div>





    <div class="portlet light waiting" >
        <div class="portlet-title tabbable-line">
            <div class="caption">
                <i class="icon-graphp-o font-blue"></i>
                <span class="caption-subject bold font-blue uppercase"> <?php echo Yii::t('app', 'Lab VS Analyzer Data Comparision'); ?></span>

            </div>
            <div class="pull-right">
                
            </div>
            <div class="clearfix"></div>
        </div>
        <div class="portlet-body ">


                <?php //$scaleLink = Url::to(['lab-calib/scale', 'lab_hist_id' => Yii::app()->request->get('lab_hist_id')]);  ?>

                <?php $scaleLink = Yii::app()->createAbsoluteUrl('labdata/getScale',  array('lab_hist_id' => $_REQUEST['lab_hist_id'])); ?>

				
				<form id='lab_settings' action='<?php echo $scaleLink?>' method="POST">
                <?php
				
				
                //$form = yii\bootstrap\ActiveForm::begin(['id' => 'lab_settings', 'action' => $scaleLink, 'method' => 'POST']);
                if ($canedit):
                    ?>
            <button type="submit"  style="margin-left: 5px;"class="btn blue pull-right scale-btn " href="javascript:;" id="scale-"><?php echo Yii::t('app', 'Scale') ?>  <i class="fa fa-wrench"></i> </button>
                    <!--<button type="button" class="btn blue pull-right margin-left-5 scale-btn-disable disabled " href="javascript:;"> Scale <i class="fa fa-wrench"></i> </button>-->
                <?php endif; ?>
                     <a href="#graphs-section" class="btn default  margin-left-5 pull-right"> <?php echo Yii::t('app', 'Graph') ?> <i class="fa fa-line-chart"></i></a>
               
                    
                <table id="lab_history_compare" class="table table-bordered table-striped table-blue comparePage" >
                    <thead>
                        <tr>
                            <th class="ui-state-default" rowspan="2"><?php echo Yii::t('app', 'End Time') ?></th>
                            <th colspan="<?php echo $totalEle ?>" class="ui-state-default">
                                <span style="font-weight:bold;text-align:center;"><?php echo Yii::t('app', 'Lab Result') ?></span>
                            </th>
                            <th colspan="<?php echo $totalEle * 1 ?>" class="ui-state-default">
                                <span style="font-weight:bold;text-align:center;"><?php echo Yii::t('app', 'Analysis Result') ?></span></th>

								  <th colspan="<?php echo $totalEle * 1 ?>" class="ui-state-default">
                                <span style="font-weight:bold;text-align:center;"><?php echo Yii::t('app', 'Diff') ?></span></th>

                            <?php if ($canedit): ?>
                                <th rowspan="2"> 
                                    <p id="selected_count" class="font-red"> (0) </p>
                                    <input type="checkbox" name="select-lab-data" value="max-selection"/> 

                                </th>
                            <?php endif; ?>

                        </tr>
                        <tr style="font-size:14px !important;">

                            <?php
                            //Lab data cell blocks header 
                            foreach ($existingEleList as $ele):
                                echo ' <th class="ui-state-default sorting_disabled">' . $ele . '</th>';
                            endforeach;
                            //Analysis cell blocks header
                            foreach ($existingEleList as $ele):
                                echo ' <th class="ui-state-highlight sorting_disable">' . $ele . '</th>';
                              //  echo ' <th class="ui-state-highlight sorting_disable">Diff</th>';
                            endforeach;
							  foreach ($existingEleList as $ele):
                                echo ' <th class="ui-state-highlight sorting_disable">' . $ele . '</th>';
                               // echo ' <th class="ui-state-highlight sorting_disable">Diff</th>';
                            endforeach;
                            ?>

                        </tr>
                    </thead>
                    <tbody>
                        <!--Comparison row--> 

                        <?php
			

						$this->widget('zii.widgets.CListView', array(
									'dataProvider'=>$dataProvider,
									'template' => '{items}',
									'viewData' => array( 'displayElement' => $existingEleList,
														'analysisAvg' => $analysisObject,
														'samplingTime' => $samplingTime,
														'fallbackTime' => $fallbackTime,
														'lookUp' => $lookup
												  ),
									'itemView'=>'_lab_data_history_view',  
 
									)); 
								
									        $splForms = array( "LSF", "SM", "IM", "AM", "KH", "TPH");

						
                        ?>
                    </tbody>

                    <tfoot class=" grey-footer">

                        <!--Average Row-->
                        <tr style="font-size:14px !important;">
                            <td style="background-color:#f7dfd4;font-weight:bold;"> <?php echo Yii::t('app', 'Averages') ?> </td>
                            <?php
							
							//var_dump($labAvg);
                            //Lab data cell blocks header 
                            foreach ($existingEleList as $ele):
                                echo ' <td class="ui-state-default sorting_disabled"  style="background-color:#f7dfd4;">' . round($labAvg['AN_' . $ele], 2) . '</td>';
                            endforeach;
								
						
                            //Analysis cell blocks header
                            foreach ($existingEleList as $ele):
							
								if(isset($avgDataVector[$ele]) || in_array($ele,$splForms)){
									$eleAvg = round(LabUtility::getAvg($avgDataVector, $ele), 2);
									
									
									$labaverage = round($labAvg['AN_' . $ele], 2);

									if ($labaverage != 0 && $eleAvg != 0) {
										$diffAvg = $labaverage - $eleAvg;
									} else {

										$diffAvg = '-'; //$labaverage == 0 ? $labaverage : $eleAvg ;
									}
									echo ' <td class=""  style="background-color:#f7dfd4;">' . $eleAvg . '</td>';
									
                               
//                                    echo '<th></th>';

								}else{
									
									echo '<td class="font-red  warning">-</td>';
								}

                            endforeach;
							
							
                            //Analysis Diff cell blocks header
                            foreach ($existingEleList as $ele):
							
								if(isset($avgDataVector[$ele]) || in_array($ele,$splForms)){
									
									
									$eleAvg = round(LabUtility::getAvg($avgDataVector, $ele), 2);
							
									
									$labaverage = round($labAvg['AN_' . $ele], 2);

									if ($labaverage != 0 && $eleAvg != 0) {
										$diffAvg = $labaverage - $eleAvg;
									} else {

										$diffAvg = '-'; //$labaverage == 0 ? $labaverage : $eleAvg ;
									}
									
									if($diffAvg == 0){
										  echo ' <td class="font-red  warning">' . round($diffAvg, 2) . '</td>';
									}else{
										
										 echo ' <td class="font-red  warning">' . round($diffAvg, 2) . '</td>';
									}
                               
//                                    echo '<th></th>';

								}else{
									
									echo '<td class="font-red  warning">-</td>';
								}

                            endforeach;
							
                            ?>

                            <td></td>
                        </tr>
                        
                        
                                   <!--Standard Deviation Row-->
                        <tr style="font-size:14px !important;">
                            <td style="background-color:#f7dfd4;font-weight:bold !important;"> <?php echo Yii::t('app', 'Standard Deviation ') ?></td>
                            <?php
                            //Lab data cell blocks header 
								foreach ($existingEleList as $ele):

								$labEleAr = LabUtility::getColumn($data, $ele);
								$labStdDev = round(LabUtility::stdDeviation($labEleAr), 2);
								echo ' <td class="ui-state-default sorting_disabled"  style="background-color:#f7dfd4;">' .$labStdDev . '</td>';
								endforeach;
								//Standard Deviation  cell blocks header


								foreach ($existingEleList as $ele):

								if(isset( $avgDataVector[$ele]) || in_array($ele,$splForms)){

                                                                        //PAWANH1 - ranga to check
                                                                        $stdDev = 0.0;
                                                                        if(isset($avgDataVector[$ele])){
                                                                            $tmpAr = $avgDataVector[$ele];

								if($ele == 'KH'){

								$stdDev = round(LabUtility::spStdDeviation($avgDataVector,'KH'), 2);
								}else if($ele == 'AM'){

								$stdDev = round(LabUtility::spStdDeviation($avgDataVector,'AM'), 2);
								}else if($ele == 'SM'){

								$stdDev = round(LabUtility::spStdDeviation($avgDataVector,'SM'), 2);
								} else{

								$stdDev = round(LabUtility::stdDeviation($tmpAr), 2);
								}
                                                                        }
								$labaverage = round($labAvg['AN_' . $ele], 2);

								if ($labaverage != 0 && $stdDev != 0) {


								$diffAvg = $labaverage - $stdDev;
								} else {

								$diffAvg = '-'; //$labaverage == 0 ? $labaverage : $eleAvg ;
								}
								echo ' <td class=""  style="background-color:#f7dfd4;">' . $stdDev . '</td>';



								}else{

								echo '<td class="font-red  warning"> -</td>';
								}


								endforeach;



								foreach ($existingEleList as $ele):

								if(isset( $avgDataVector[$ele]) || in_array($ele,$splForms)){
                                                                //PAWANH1 - ranga to check
                                                                        $stdDev = 0.0;
                                                                        if(isset($avgDataVector[$ele])){
                                                                            $tmpAr = $avgDataVector[$ele];


								if($ele == 'KH'){

								$stdDev = round(LabUtility::spStdDeviation($avgDataVector,'KH'), 2);
								}else if($ele == 'AM'){

								$stdDev = round(LabUtility::spStdDeviation($avgDataVector,'AM'), 2);
								}else if($ele == 'SM'){

								$stdDev = round(LabUtility::spStdDeviation($avgDataVector,'SM'), 2);
								} else{

								$stdDev = round(LabUtility::stdDeviation($tmpAr), 2);
								}
                                                                        }

								$labaverage = round($labAvg['AN_' . $ele], 2);

								if ($labaverage != 0 && $stdDev != 0) {


								$diffAvg = $labaverage - $stdDev;
								} else {

								$diffAvg = '-'; //$labaverage == 0 ? $labaverage : $eleAvg ;
								}

								if($diffAvg == 0){
								echo ' <td class="font-red  warning">' . round($diffAvg, 2) . '</td>';
								}else{

								echo ' <td class="font-red  warning">' . round($diffAvg, 2) . '</td>';
								}




								}else{

								echo '<td class="font-red  warning"> -</td>';
								}


								endforeach;
                            ?>

                            <td></td>
                        </tr>
                        
                        
                        
                             
                                   <!--Standard Error Row-->
                        <tr style="font-size:14px !important;">
                            <td style="background-color:#f7dfd4;font-weight:bold;"> <?php echo Yii::t('app', 'Standard Error') ?> </td>
                            <?php
                            //Lab data cell blocks header 
                            foreach ($existingEleList as $ele):
                                
                                
                                $labEleAr = LabUtility::getColumn($data, $ele);
                                $labStdErr = round(LabUtility::stdError($labEleAr), 2);
                                
                                echo ' <td class="ui-state-default sorting_disabled"  style="background-color:#f7dfd4;">' . $labStdErr. '</td>';
                            endforeach;
						//Analysis cell blocks header
						foreach ($existingEleList as $ele):


								if(isset( $avgDataVector[$ele]) || in_array($ele,$splForms)){		
								//if(isset($avgDataVector[$ele])){
                                                                    //PAWANH1 - ranga to check
                                                                    $stdError = 0.0;
                                                                    if(isset($avgDataVector[$ele])){
								$tmpAr = $avgDataVector[$ele];

								if($ele == 'KH'){

								$stdError = round(LabUtility::spStdError($avgDataVector,'KH'), 2);
								}else if($ele == 'AM'){

								$stdError = round(LabUtility::spStdError($avgDataVector,'AM'), 2);
								}else if($ele == 'SM'){

								$stdError = round(LabUtility::spStdError($avgDataVector,'SM'), 2);
								} else{

								$stdError = round(LabUtility::stdError($tmpAr), 2);
								}
                                                                    }
								$labaverage = round($labAvg['AN_' . $ele], 2);

								if ($labaverage != 0 && $stdError != 0) {


								$diffAvg = $labaverage - $stdError;
								} else {

								$diffAvg = '-'; //$labaverage == 0 ? $labaverage : $eleAvg ;
								}
								echo ' <td class="" style="background-color:#f7dfd4;">' . $stdError . '</td>';


								//                                    echo '<th></th>';

								}else{

								echo '<td class="font-red  warning">-</td>';
								}

						endforeach;
						
						//Analysis cell blocks header
						foreach ($existingEleList as $ele):


								if(isset( $avgDataVector[$ele]) || in_array($ele,$splForms)){		
								//if(isset($avgDataVector[$ele])){
                                                                    //PAWANH1 - ranga to check
                                                                    $stdError = 0.0;
                                                                    if(isset($avgDataVector[$ele])){                                                                    
								$tmpAr = $avgDataVector[$ele];

								if($ele == 'KH'){

								$stdError = round(LabUtility::spStdError($avgDataVector,'KH'), 2);
								}else if($ele == 'AM'){

								$stdError = round(LabUtility::spStdError($avgDataVector,'AM'), 2);
								}else if($ele == 'SM'){

								$stdError = round(LabUtility::spStdError($avgDataVector,'SM'), 2);
								} else{

								$stdError = round(LabUtility::stdError($tmpAr), 2);
								}
                                                                    }
								$labaverage = round($labAvg['AN_' . $ele], 2);

								if ($labaverage != 0 && $stdError != 0) {


								$diffAvg = $labaverage - $stdError;
								} else {

								$diffAvg = '-'; //$labaverage == 0 ? $labaverage : $eleAvg ;
								}
								if($diffAvg == 0){
								echo ' <td class="font-red  warning">' . round($diffAvg, 2) . '</td>';
								}else{

								echo ' <td class="font-red  warning">' . round($diffAvg, 2) . '</td>';
								}

								//                                    echo '<th></th>';

								}else{

								echo '<td class="font-red  warning">-</td>';
								}

						endforeach;
                            ?>

                            <td></td>
                        </tr>
                      <tr style="font-size:14px !important;">
                            <th><?php echo Yii::t('app', 'End Time') ?></th>
                            <?php
                            //Lab data cell blocks header 
                            foreach ($existingEleList as $ele):
                                echo ' <th class="ui-state-default sorting_disabled">' . $ele . '</th>';
                            endforeach;
                            //Analysis cell blocks header
                            foreach ($existingEleList as $ele):
                                echo ' <th class="ui-state-highlight sorting_disable">' . $ele . '</th>';
                                echo ' <th class="ui-state-highlight sorting_disable">Diff</th>';
                            endforeach;
							
							
                            ?>
                            <th></th>
                        </tr>
                    </tfoot>
                </table>
				
				</form>
           

        </div>
    </div>
<div class="portlet light waiting" id="graphs-section" >
        <div class="portlet-title tabbable-line">
            <div class="caption">
                <i class="icon-graphp-o font-blue"></i>
                <span class="caption-subject bold font-blue uppercase"> <?php echo Yii::t('app', 'Lab VS Analyzer Trend Plot'); ?></span>

            </div>
            <div class="tools">
            </div>
        </div>
        <div class="portlet-body ">

                    <div class="row">
                     
    <?php
	
							
    $graphData = array();
    $labData = Yii::app()->db->createCommand('select * from lab_history_data order by EndTime ASC')->query()->readAll();
    
//    array_reverse($labData);
    $spList = array('LSF',  'KH','SM', 'IM', 'AM');

    foreach ($labData as $key => $model) {

        $endDate = date('Y-m-d H:i:s', strtotime($model['EndTime']) - $fallbackTime);
        $startDate = date('Y-m-d H:i:s', strtotime($endDate) - $samplingTime);
        $labTime = date("Y-m-d H:i:s", strtotime($model['EndTime']));
        
        $avg = $analysisObject->analysisAvg;
        $analysisRow = isset($analysisObject->analysisAvg[$labTime]) ? $analysisObject->analysisAvg[$labTime] :  array();

        $formAvg = CustomAverage::getAverage($analysisRow, $spList);
		
        unset($analysisRow['LSF']);
       // unset($analysisRow['C3S']);
        unset($analysisRow['SM']);
        unset($analysisRow['AM']);
        unset($analysisRow['IM']);
	unset($analysisRow['KH']);
        $anAverage = array_merge($formAvg, $analysisRow);
        //var_dump($model['EndTime']);
        foreach ($existingEleList as $i => $j ) {
			
			if (in_array($j, array('EndTime', 'lab_data_id')))
                continue;

            $timeZoneAdjust = Yii::app()->params["timezoneDiff"];
            $timeStamp = (strtotime($model['EndTime']) + ($timeZoneAdjust)) * 1000;
            $val       = (float)$model[$j];
            $aval      = isset($anAverage[$j])? $anAverage[$j] : 0; 
            $graphData[$j]['LAB_'.$j][] = array($timeStamp,$val);
            $graphData[$j]['AN_'.$j][] = array($timeStamp,$aval);
			
	   }
    }
  
    $series = array();
        
        foreach ($graphData as $key => $item) {
            
            // Get the series name
            
            $openingTag = '
                        <!-- BEGIN Portlet PORTLET-->
                        <div class="portlet light" style="min-height:380px;border-top: 2px rgba(0,0,0,.03) solid;">
                        <div class="portlet-body">';
        $closeTag = '</div></div>';
            
        echo  "<div class ='col-md-6'>$openingTag<div  id='comp_graph_" . $key . "'>$closeTag</div></div>";
            $localKeyVal = $key;
            
            
            $anData = array_values($item['AN_'.$key]);
            $labData = array_values($item['LAB_'.$key]);
            $series[$key][0] = array(
                'type' => 'spline',
                'name' => 'AN_'.$key,
                'data' => $anData,
                'title' => array( 
                    'text' => ''
                ),
                'marker' => array(
                    'lineWidth' => 2,
                    'lineColor' => '#7EFC7A',
                    'fillColor' => 'white'
                )
                
            );
            
            $series[$key][1] = array(
                'type' => 'spline',
                'name' => 'LAB_'.$key,
                'data' => $labData,
                'title' => array(
                    'text' => ''
                ),
                'marker' => array(
                    'lineWidth' => 2,
                    'lineColor' => '#7EFC7A',
                    'fillColor' => 'white'
                )
            );
        }
        

$cs = Yii::app()->clientScript;


$cs->registerScript('appConfig',"var CompGraph = ".json_encode($series).";", CClientScript::POS_BEGIN);     

//exit();   
        
    ?>


    <?php
}//if data exists
?>
<div class="clearfix"></div>
   
                    
	</div>
        </div>
    </div>


<script type="text/javascript" charset="utf-8">

    var selectCount = 0, maxSelection;
    $(document).ready(function () {


        var table = $('#lab_history_compare').DataTable({
            "ordering": false,
            dom: 'Btp',
            pageLength: 20,
            buttons: [
                {
                    extend: 'copyHtml5',
                    className: 'btn default',
                    title: 'Pass rate '
                }, {
                    extend: 'excelHtml5',
                    title: 'Pass rate ',
                    className: 'btn default'
                }

            ],
            initComplete: function () {

                $('.buttons-excel').addClass('btn btn-default');
                $('.buttons-excel').html('Export <i class="fa fa-file-excel-o"> </i>')

            }
        });

    maxSelection = getMaxSelectionNum();
    $('#lab_settings').on('submit', submitScale);
    $('input:checkbox[name="select-lab-data"]').on('click', toggleCheckbox)


            $(".lab-time").on('click', function () {


    var sCount = $("input:checked.lab-time").length;
    if (10 === sCount) {

    $("#selected_count").text('(' + sCount + ')').removeClass('font-red').addClass('font-white');
    } else {

    $("#selected_count").text('(' + sCount + ')').addClass('font-red');
    }

    if (sCount === 0) {


    $('input:checkbox[name="select-lab-data"]').prop('checked', false)
    }


    }
    );
    
    initCompGraph();
  
    });
    function toggleCheckbox(event) {


    var count = 0;
    var checkedCount = 0;
    $("input.lab-time").attr('checked', false)

            $("input.lab-time").each(function () {

    var checked = $('input:checkbox[name="select-lab-data"]').attr('checked')

            count++


//            if (count <= getMaxSelectionNum()) {

    $(this).prop('checked', $('input:checkbox[name="select-lab-data"]').prop('checked'));
    if ($('input:checkbox[name="select-lab-data"]').prop('checked')) {
    checkedCount++
    }
//    }
    }); //each 



    var sCount = $("input:checked.lab-time").length

            if (10 === sCount) {

    $("#selected_count").text('(' + sCount + ')').removeClass('font-red').addClass('font-white');
    } else {

    $("#selected_count").text('(' + sCount + ')').addClass('font-red');
    }

    }
    function  submitScale(event) {



//            debugger;
    var selectedCount = getSelectedCount();
    //AUTO_CALIB_SCALE_PARAMS_MIN_SELECTED, if not present make 3
    setting_totalCompareitems = parseInt('<?php echo getSetting('rm_settings','AUTO_CALIB_SCALE_PARAMS_MIN_SELECTED')?>');
    if (selectedCount >= setting_totalCompareitems) {


    } else {


    event.preventDefault();
    
    var text1a = '<?php echo Yii::t('app','Please select atleast');?>';
    var text1b =' ' + setting_totalCompareitems + ' ' 
    var text1c = '<?php echo Yii::t('app','rows before scaling. You have selected');?>';
    var text1 = text1a + text1b + text1c ;
    var text2 = '<?php echo Yii::t('app','Attention');?>';
    swal(text2, text1 +' ('+selectedCount+')', 'warning');
    }


    }
    function getMaxSelectionNum() {

    var count = $("input.lab-time").length;
   if (count < 3) {


    return  false;
    }else{
        return count;
        }



//
    }


    function initCompGraph(){

     var subtitle = 'Lab vs Analysis Results';
    
        for (x in CompGraph) {
//            console.log("G ID", graphList[x]['id'], x)

//debugger;

                var temp = CompGraph[x]
                
                
                console.log(temp,'series');
                
                // Create the chart
                

                Highcharts.stockChart("comp_graph_" + x, {
                        rangeSelector: {
                            inputEnabled : false,
                            selected : 4,
                            inputPosition: {
                                align: 'left',
                                x: 0,
                                y: 0
                            },
                            buttonPosition: {

                                align: 'left',
                                x: 0,
                                y: 0,
                            },
                            buttons: [
                                    
                                    {
                                    type: 'hour',
                                    count: 1,
                                    text: '1H',

                                }, {
                                    type: 'hour',
                                    count: 2,
                                    text: '2H'
                                }, 
                                 {
                                    type: 'hour',
                                    count: 4,
                                    text: '4H'
                                },
                                
                                {
                                    type: 'all',
                                    text: 'All'
                                }
                            ]
                        },
                        title: {
                            text: x
                        },
                        subtitle: {
                            text: subtitle
                        },

                        series:      CompGraph[x],//CompList.data,
                        credits: false,
                        exporting: {

                            enabled: false,
                            menuItemDefinitions: {
                                // Custom definition
                                label: {
                                    onclick: function () {
                                        this.renderer.label(
                                                'You just clicked a custom menu item',
                                                100,
                                                100
                                                )
                                                .attr({
                                                    fill: '#a4edba',
                                                    r: 5,
                                                    padding: 10,
                                                    zIndex: 10
                                                })
                                                .css({
                                                    fontSize: '1.5em'
                                                })
                                                .add();
                                    },
                                    text: 'Show label'
                                }
                            },
                            buttons: {
                                contextButton: {
                                    menuItems: []
                                }
                            }
                        }


                    });
           

        }
    
    }

    function getSelectedCount() {

    var checkedCount = 0;
    $("input.lab-time").each(function () {

//            console.log();
    var checked = $(this).prop('checked');
    if (checked) {
    checkedCount++
            console.log(checkedCount);
    }

    })


            return checkedCount;
    }

    function setTimeVariable() {

    var sampling_time = $('#sampling_time').val();
    var fallback_time = $('#fallback_time').val();
    var scaling_accurecy = $("#scaling_acc    uracy").val()


            $.ajax({
            type: "POST",
                    url: '<?php echo Yii::app()->createAbsoluteUrl('labdata/setLabTime'); ?>',
                    data: {
                    history_sampling_time: sampling_time,
                            history_fallback_time: fallback_time,
                            scaling_accurecy: scaling_accurecy,
                    },
                    success: function (msg) {

                    swal('Success', 'Values saved successfully', 'success')

                            window.location = '';
                    }
            });
    }
</script>



<?php 



function getSetting($table, $key){
         $query = "select * from $table where varName = '$key' ";
        
        $result = Yii::app()->db->createCommand($query)->queryRow();

        return $result['varValue'];
}

?>