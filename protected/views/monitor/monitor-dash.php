<?php
/* @var $this DefaultController */
/**
 * @monitorObject MonitorObject Description
 */

define("TIME_CHECK",0);

$sstTime = date("Y-m-d H:i:s");
$ssltime = time();

if(TIME_CHECK)echo "Start:$stTime ($sltime) <br/>";

$monitorHelper = new MonitorHelper();

$elements      = $monitorObject->getElements();

$monitorHelper->setElements($elements);

$monitorHelper->setIntervals($intervalArray);

$stTime2 = date("Y-m-d H:i:s");
$sltime2 = time();

$time_diff1 = round($sltime2 - $ssltime,2);



if(TIME_CHECK)echo "Time 2:$stTime2 ($sltime) Diff: $time_diff1 <br/>";
?>

<section class="main-section grid_8" style="min-height:600px">
    <nav class="">
        <?php
        $baseUrl = Yii::app()->basePath;

        $menuFile = $baseUrl . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "themes" . DIRECTORY_SEPARATOR . "tutorialzine1" . DIRECTORY_SEPARATOR . "views" . DIRECTORY_SEPARATOR . "pageDefaults" . DIRECTORY_SEPARATOR . "authLeftMenu.php";
        include_once($menuFile);
        ?>


    </nav>
    <div class="main-content" style="min-height:600px">

        <section class="container_6 clearfix">

            <!-- Tabs inside Portlet -->
            <div class="grid_6 leading">
                <div class="grid_6" style="margin-left:0px;margin-right:22px;">
                    <header class="ui-widget-header ui-widget-header-blue ui-corner-top">
                        <h2> <?php echo Yii::t('app', 'Analysis Results') ?></h2>
                    </header>
                    <section class="ui-widget-content ui-corner-bottom">
                        <?php 
              
                        $monitorHelper->renderMonitorTable($monitorObject); 
                        $stTime3 = date("Y-m-d H:i:s");
                        $sltime3 = time();

                        $time_diff2 = round($sltime3 - $sltime,2);
                        $time_diff3 = round($sltime3 - $sltime2,2);

                        if(TIME_CHECK)echo "Time 3:$stTime3 ($sltime3) Diff: $time_diff2 , $time_diff3  <br/>";
                        
                        ?>
                    </section>
            	</div>
                <div class="grid_2" style="margin-left:24px;display: none">
                    <header class="ui-widget-header ui-widget-header-blue ui-corner-top">
                        <h2><?php echo Yii::t('app', 'Set Points') ?> </h2>
                    </header>
                    <section class="ui-widget-content ui-corner-bottom" style="min-height:210px">
                            <?php 
                                $monitorHelper->renderSpWidget(); 
                                $stTime4 = date("Y-m-d H:i:s");
                                $sltime4 = time();

                                $time_diff4 = round($sltime4 - $sltime,2);
                                $time_diff5 = round($sltime4 - $sltime3,2);

                                if(TIME_CHECK)echo "Time 4:$stTime4 ($sltime4) Diff: $time_diff4 , $time_diff5  <br/>";
              
                            ?>
                    </section>
                </div>
            </div>
			<div class="clearfix"> </div>
            <div class="grid_6 leading">
                        <header class="ui-widget-header ui-widget-header-blue ui-corner-top">
                            <h2> <?php echo Yii::t('app', 'Tags') ?></h2>
                        </header>
                        <section class="ui-widget-content ui-corner-bottom">
                            <div class="clearfix">
                                <?php 
                                    $monitorHelper->renderTagWidget(); 
                                    $stTime5 = date("Y-m-d H:i:s");
                                    $sltime5 = time();

                                    $time_diff5 = round($sltime5 - $sltime,2);
                                    $time_diff6 = round($sltime5 - $sltime4,2);

                                    if(TIME_CHECK)echo "Time 5:$stTime5 ($sltime5) Diff: $time_diff5 , $time_diff6  <br/>";                                    
                                ?>
                            </div>
                        </section>
             </div>
			<div class="clearfix"> </div>
            <div class="grid_6 leading">
                    <header class="ui-widget-header ui-widget-header-blue ui-corner-top">
                        <h2> <?php echo Yii::t('app', 'Latest Tags') ?></h2>
                    </header>
                    <section class="ui-widget-content ui-corner-bottom">
                        <div class="clearfix">
                            <?php 
                                $monitorHelper->renderTagPileWidget();
                                $stTime6 = date("Y-m-d H:i:s");
                                $sltime6 = time();

                                $time_diff7 = round($sltime6 - $sltime,2);
                                $time_diff8 = round($sltime6 - $sltime5,2);

                                if(TIME_CHECK)echo "Time 6:$stTime6 ($sltime6) Diff: $time_diff7 , $time_diff8  <br/>"; 
                            ?>                                
                        </div>
                    </section>
            </div>
			<div class="clearfix"> </div>
        </section>
    </div> <!-- End of Main content -->
</section>


<script type="text/javascript">
            window.onload = function () {
                
                $('.sp_edit').live('click', function () {

                    var rowSelector = $(this).attr('data-id');


                    document.getElementById(rowSelector + '_view').style.visibility = "collapse";


                    document.getElementById(rowSelector + '_form').style.visibility = "visible";
                    //   $("#"+rowSelector+'_view').prop('visiblility','collapse');

                });
                $('.sp_save').live('click', function () {


                    var rowSelector = $(this).attr('data-id');


                    document.getElementById(rowSelector + '_view').style.visibility = "visible";


                    document.getElementById(rowSelector + '_form').style.visibility = "collapse";

                    var spID = $("#" + rowSelector + '_sp_id').val();
                    var spValue = $("#" + rowSelector + '_sp_value_num').val();

                    var spTolerence = $("#" + rowSelector + '_sp_tolerance_ulevel').val();


                    $("#" + rowSelector + '_tolerance').html(spTolerence);

                    $("#" + rowSelector + '_value').html(spValue);
                    //  debugger;
                    $.ajax({
                        url: '<?php echo Yii::app()->createAbsoluteUrl('setPoints/update'); ?>',
                        //            type:"post",
                        data: {spID: spID, spTolerence: spTolerence, spValue: spValue},
                        success: function () {

                            alert('Values saved successfully')
                        },
                        error: function () {

                            alert("ERROR : Could save setpoints");
                        }
                    })//ajax end

                })
                
                
        $('.interval-range').live('change', function () {


            var name = $(this).attr('intervalName');
            var type = $(this).attr('intervalType');
            var value = $(this).val();


            $.ajax({
                url: '<?= Yii::app()->createAbsoluteUrl('monitor/SetInterval') ?>',
                data: {name: name, value: value},
                success: function () {

                    window.location = '';
                },

            })
        })
            }
        </script>
