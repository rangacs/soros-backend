<?php
/** @intervalHelper IntervalHelper Description
 * @rollingRecord RollingHelper Description
 */

$interValStart = date('Y-m-d H:i:s'); 
?>

<section class="main-section grid_8" style="min-height:600px">
    <nav class="">
        <?php
        
        $baseUrl = Yii::app()->basePath;

        $menuFile = $baseUrl . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "themes" . DIRECTORY_SEPARATOR . "tutorialzine1" . DIRECTORY_SEPARATOR . "views" . DIRECTORY_SEPARATOR . "pageDefaults" . DIRECTORY_SEPARATOR . "authLeftMenu.php";
        include_once($menuFile);

        //$elements = array('SiO2', 'Al2O3','Fe2O3','CaO','KH','SM',"IM");
        $elements = HeliosUtility::getDisplayElements();
        ;

        $intervalRecord = $intervalHelper->getData($elements);
        $cumulativeRecord = $cumulativeHelper->getData($elements);
        $rollingRecord = $rollingHelper->getData($elements);
        
        ?>


    </nav>
    <div class="main-content" style="min-height:600px">
        <section class="container_6 clearfix">

            <!-- Tabs inside Portlet -->
            <div class="grid_6 leading">
                <div class="grid_6" style="height: 30%;padding-right: 5px">
                    <header class="ui-widget-header ui-widget-header-blue ui-corner-top">
                        <h2> <?php echo Yii::t('app', 'Monitor') ?></h2>
                    </header>
                    <section class="ui-widget-content ui-corner-bottom">

                        <table class="full list-table">

                            <tr>
                                <th></th> 
                                <th> 
                                    <button class="button ui-button ui-widget ui-state-highlight ui-corner-all ui-button-text-only" onclick="openIntervalForm()">
                                        <span class="ui-button-text" style="font-weight:bold"><?php echo Yii::t('app', 'Interval'); ?></span>
                                    </button>
                                </th> 
                                <th>  <button class="button ui-button ui-widget ui-state-highlight ui-corner-all ui-button-text-only" onclick="openCumulativeForm()">
                                        <span class="ui-button-text" style="font-weight:bold"><?php echo Yii::t('app', 'Cumulative'); ?></span>
                                    </button>
                                </th> 
                                <th>  <button class="button ui-button ui-widget ui-state-highlight ui-corner-all ui-button-text-only" onclick="openRollingForm()">
                                        <span class="ui-button-text" style="font-weight:bold"><?php echo Yii::t('app', 'Rolling'); ?></span>
                                    </button>
                                </th>	                        
                            </tr>

                            <?php
                            echo "<tr>";
                            echo "<td style='font-weight:bold;border-right: 1px solid #121212 !important;'>" . Yii::t('app', 'Start Time') . "</td>";
                            echo "<td style='border-right: 1px solid #121212 !important;'> " . $intervalHelper->startTime . "</td>";
                            echo "<td style='border-right: 1px solid #121212 !important;'> " . $cumulativeHelper->startTime . "</td>";
                            echo "<td style='border-right: 1px solid #121212 !important;'> " . $rollingHelper->startTime . "</td>";

                            echo "</tr>";



                            echo "<tr>";
                            echo "<td style='font-weight:bold;border-right: 1px solid #121212 !important;'>" . Yii::t('app', 'End Time') . "</td>";
                            echo "<td style='border-right: 1px solid #121212 !important;'> " . $intervalHelper->endTime . "</td>";
                            echo "<td style='border-right: 1px solid #121212 !important;'> " . $cumulativeHelper->endTime . "</td>";
                            echo "<td style='border-right: 1px solid #121212 !important;'> " . $rollingHelper->endTime . "</td>";

                            echo "</tr>";

                            foreach ($elements as $ele) {

                                echo "<tr>";
                                echo "<td style='font-weight:bold;border-right: 1px solid #121212 !important;'> " . Yii::t('app', $ele) . "</td>";
                                echo "<td style='border-right: 1px solid #121212 !important;' > " . $intervalRecord[$ele] . "</td>";
                                echo "<td style='border-right: 1px solid #121212 !important;' > " . $cumulativeRecord[$ele] . "</td>";
                                echo "<td style='border-right: 1px solid #121212 !important;' > " . $rollingRecord[$ele] . "</td>";

                                echo "</tr>";
                            }
                            ?>
                        </table>

                    </section>
                </div>
            </div>
        </section>

    </div> 
</section>


<div id="cumulative-dialog">
    <section class="form">

        <form id="cumulative-form">
            <div class="clearfix">

               
            
            
            <div class="clearfix">
                <label class="form-label"style="width:100px" for="form-name"><?php echo Yii::t('dash', 'Start Time'); ?> <em>*</em></label>
                <div class="form-input" style="margin-left:120px">
                    
                    <input type="text" required="required" class="datepicker" name="timeRangeStart" id="cumulativeStartTime-datestring" autocomplete="off" value="<?php echo date('Y-m-d', strtotime($_SESSION['cumulative_start_time'])) ?>">
                    <span><input type="text" style="width:140px" name="timeRangeStart_time" id="cumulativeStartTime-timestring" class="timepick" size="5" maxlength="5" value="<?php echo date('H:i', strtotime($_SESSION['cumulative_start_time'])) ?>"/></span></div>
            </div> 
        </form>
    </section>
</div>

<div id="interval-dialog">
    <section class="form">

        <form id="interval-form">


           
            <div class="clearfix">
                <label class="form-label"style="width:100px" for="form-name"><?php echo Yii::t('dash', 'Start Time'); ?> <em>*</em></label>
                <div class="form-input" style="margin-left:120px">
                    
                    <input type="text" required="required" name="timeRangeStart" id="interval-startTime-datestring" autocomplete="off" value="<?php echo date('Y-m-d', strtotime($_SESSION['interval_start_time'])) ?>">
                    <span><input type="text" style="width:140px" name="timeRangeStart_time" id="interval-startTime-timestring" class="timepick" size="5" maxlength="5" value="<?php echo date('H:i', strtotime($_SESSION['interval_start_time'])) ?>"/></span></div>
            </div> 
             <div class="clearfix">
                <label class="form-label"style="width:100px" for="form-name"><?php echo Yii::t('dash', 'Start Time'); ?> <em>*</em></label>
                <div class="form-input" style="margin-left:120px">
                    
                    <input type="text" required="required" name="timeRangeStart" id="interval-endTime-datestring" autocomplete="off" value="<?php echo date('Y-m-d', strtotime($_SESSION['interval_end_time'])) ?>">
                    <span>
                        <input type="text" style="width:140px" name="timeRangeStart_time" id="interval-endTime-timestring" class="timepick" size="5" maxlength="5" value="<?php echo date('H:i', strtotime($_SESSION['interval_end_time'])) ?>"/></span></div>
            </div> 
            

                <input  type="hidden" class="datepicker" value="" id="interval-startTime"/>
                <input  type="hidden" class="datepicker" value="" id="interval-endTime"/>

        </form>
    </section>
</div>

<div id="rolling-dialog">
    <section class="form">

        <form id="rolling-form">
            <div class="clearfix">

                <label class="form-label" for="form-name"><?php echo Yii::t('app', "Rolling Minutes") ?>  <em>*</em></label>

                <div class="form-input">

                    <input  type="text" value="<?= $rollingHelper->rollingMin ?>" id="rolling-minutes"/>
                </div>

            </div>
        </form>
    </section>
</div>

<script type="text/javascript">
    $('#interval-startTime-datestring,.datepicker').datepicker({
        dateFormat: "yy-mm-dd"
    });
    $('#interval-endTime-datestring').datepicker({
        dateFormat: "yy-mm-dd"
    });
    $('.timepick').timeslider({showValue: true, clickable: true});

    window.onload = function () {


        $('.datepicker').datepicker();

        init();

    }


    function reload() {


        window.location = '';
    }
    function init() {

        $("#cumulative-dialog").dialog({
            title: 'Cumulative ',
            autoOpen: false,
            height: 250,
            width: 450,
            modal: true,
            open: function (event, ui) {

            },
            buttons: {
                "Save": function () {


                    $(this).dialog("close");
                    submitCumulativeForm();
                },
                Cancel: function () {
                    $(this).dialog("close");
                }
            },
            close: function () {

            }
        });

        $("#interval-dialog").dialog({

            title: ' Interval',
            autoOpen: false,
            height: 350,
            width: 450,
            modal: true,
            open: function (event, ui) {

            },
            buttons: {
                "Save": function () {

                    $(this).dialog("close");
                    submitIntervalForm();
                },
                Cancel: function () {
                    $(this).dialog("close");
                }
            },
            close: function () {

            }
        });


        $("#rolling-dialog").dialog({

            title: 'Rolling Minutes',
            autoOpen: false,
            height: 250,
            width: 450,
            modal: true,
            open: function (event, ui) {

            },
            buttons: {
                "Save": function () {

                    $(this).dialog("close");
                    submitRollingForm();
                },
                Cancel: function () {
                    $(this).dialog("close");
                }
            },
            close: function () {

            }
        });

    }

    function openCumulativeForm() {


        $('#cumulative-dialog').dialog('open');

    }

    function submitCumulativeForm() {

        var dateString = $("#cumulativeStartTime-datestring").val()
        var timeString = $('#cumulativeStartTime-timestring').val();
        var cumulativeStartTime = dateString+" "+timeString;
    //    var cumulativeStartTime = $("#cumulativeStartTime").val();
        $.ajax({
            url: '<?= Yii::app()->createAbsoluteUrl('monitor/submitCumulativeForm') ?>',
            data: {cumulative_start_time: cumulativeStartTime},
            success: function () {
                reload()
            }
        })

    }

    function openIntervalForm() {

        $('#interval-dialog').dialog('open');
    }

    function submitIntervalForm() {

        debugger;
        $("#interval-endTime").val($("#interval-endTime-datestring").val()+" "+ $("#interval-endTime-timestring").val());

        $("#interval-startTime").val($("#interval-startTime-datestring").val()+" "+ $("#interval-startTime-timestring").val());
        
        var interval_end_time = $("#interval-endTime").val();
        var interval_start_time = $("#interval-startTime").val();

        $.ajax({
            url: '<?= Yii::app()->createAbsoluteUrl('monitor/submitIntervalForm') ?>',
            data: {interval_start_time: interval_start_time, interval_end_time: interval_end_time},
            success: function () {
                reload()
            }
        })
    }
    function openRollingForm() {
        $('#rolling-dialog').dialog('open');

    }

    function submitRollingForm() {

        var rollingMin = $("#rolling-minutes").val();
        $.ajax({
            url: '<?= Yii::app()->createAbsoluteUrl('monitor/submitRollingForm') ?>',
            data: {rollingMin: rollingMin},
            success: function () {
                reload()
            }
        })

    }

</script>