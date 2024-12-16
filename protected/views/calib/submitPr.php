<?php

//use Yii;
$saveFlag = 0;
?>


<div class="row hide">
    <div class="col-md-12">
        <div class="portlet light">


            <table class="table table-blue table-bordered">
                <tr><th >Library</th><th >Configuration</th></tr>
                <tbody>
                    <tr><td><select class="form-control" onchange="scaleDirSelect(this.value)">
                                <option value="default" selected="default">default</option></select></td><td id="scaleParamFileList"><select class="form-control" onchange="scaleFileSelect(this.value)"><option value="/usr/local/sabia-ck/cal_adjust/usrCalLib/default/default.cfg" selected="/usr/local/sabia-ck/cal_adjust/usrCalLib/default/default.cfg">default/default</option></select>
                        </td>
                    </tr>

                </tbody>
            </table>
        </div>
    </div>
</div>
<div class="row"> 
    <div class="col-md-12"> 
        <!--Portlet Start-->
        <div class="portlet light" >

            <div  class="portlet-title">
                <div class="caption">
                    Scale parameters -- Lab File Used  -<span class="font-red"> <?php echo $labHistoryModal->temp_name ?></span>
                </div>

            </div>
            <div class="portlet-body">
<?php if (!empty($scaleList)) { ?>
                    <div class="clearfix"></div>

                    <form id="submitPr">
                        <table class="table table-blue table-bordered margin-top-20">
                            <thead>
                                <tr><th style="width: 150px" rowspan="2">Element Name</th>
                                    <th  colspan="1" > Current</th><th colspan="1">Proposed</th>
    <!--                                 <th style="width: 20px ! IMPORTANT;text-align: center;vertical-align: middle">
                                         Alignment Accuracy
                                         <br>
                                         <div>( Setting at : <?php echo $allignmentAccurecay * 100 ?> %)</div>
                                     </th> -->

                                    <th  style="width: 40px;vertical-align: middle;text-align: center ! important;" rowspan="2"> <input  class=""  checked="checked"name="select-check" type="checkbox"></th></tr>
                                <tr>
                                    <!--<th style="width: 100px"> Gain</th>--> 
                                    <th style="width: 100px">Offset</th> 
                                    <!--<th style="width: 100px"> Gain</th>-->
                                    <th style="width: 100px">Offset</th> 


                                </tr>
                            </thead>
                            <tbody  id="scaleList">

                                <?php
                                $curretValues = $scaleList['current'];
                                $proposedValues = $scaleList['proposed'];

                                $cKey = array_keys($curretValues);
                                $pkeys = array_keys($proposedValues);
                                $scList = array_merge($cKey, $pkeys);
                                $scList = array_unique($scList);
                                foreach ($scList as $key) {
//
//    if (empty($proposedValues[$key]['type']) && empty($curretValues[$key]['type']))
//        //continue;
                                    $scaleitems[] = $key;

                                    $oClass = '';
                                    $overWrite = 'checked';

                                    if (!isset($scaleList['proposed'][$key]['gain']) && !isset($scaleList['proposed'][$key]['offset'])) {

                                        $overWrite = '';

                                        $oClass = 'overwrite';
                                    }
                                    echo '<tr>';

                                    echo '<td>' . $key . '</td>';

                                    if (isset($scaleList['proposed'][$key]['error'])) {

//            echo '<td> <input class="form-control disabled " type="text" disabled="disabled"  name="' . $key . '_current_gain" value="' . $scaleList['current'][$key]['gain'] . '"/></td>';
                                        echo '<td> <input class="form-control readonly" type="text" disabled="disabled" name="' . $key . '_current_offset" value="' . $scaleList['current'][$key]['offset'] . '"/></td>';


                                        echo '<td> <input class="form-control " type="text" name="' . $key . '_proposed_offset" value="' . $scaleList['proposed'][$key]['offset'] . '"/></td>';

//                                        echo '<td colspan="1" >' . $scaleList['proposed'][$key]['error'] . '</td>';
//            echo '<td>' . $scaleList['proposed'][$key]['alignmentPercentage'] * 100 . '%</td>';
                                        echo '<td class="text-center"> <input class="' . $oClass . ' " type="checkbox"  ' . $overWrite . '  name="' . $key . '_overwrite" value="' . $key . '"/></td>';
                                    } else {

                                        $saveFlag++;
//    echo '<td> <input class="form-control disabled " type="text" disabled="disabled"  name="' . $key . '_current_gain" value="' . $scaleList['current'][$key]['gain'] . '"/></td>';
                                        echo '<td> <input class="form-control readonly" type="text" disabled="disabled" name="' . $key . '_current_offset" value="' . $scaleList['current'][$key]['offset'] . '"/></td>';

                                        // echo '<td> <input class="form-control disabled" type="text" disabled="disabled" name="'.$value.'_cal_offset" value=""/></td>';
                                        // echo '<td> <input class="form-control disabled" type="text" disabled="disabled" name="'.$value.'_cal_gain" value=""/></td>';
//    echo '<td> <input class="form-control " type="text" name="' . $key . '_proposed_gain" value="' . $scaleList['proposed'][$key]['gain'] . '"/></td>';

                                        echo '<td> <input class="form-control " type="text" name="' . $key . '_proposed_offset" value="' . $scaleList['proposed'][$key]['offset'] . '"/></td>';


//    echo '<td>' . $scaleList['proposed'][$key]['alignmentPercentage'] * 100 . '%</td>';
                                        echo '<td class="text-center"> <input class="' . $oClass . ' " type="checkbox"  ' . $overWrite . '  name="' . $key . '_overwrite" value="' . $key . '"/></td>';
                                    }

                                    echo '</tr>';
                                }
                                ?>


                            </tbody>
                        </table>



                        <div class="text-center margin-top-20">

    <?php
	
	$cancelLink = Yii::app()->createAbsoluteUrl('/labdata/index');
    echo CHtml::link('Cancel', $cancelLink, ['class' => 'btn btn-default']);




    if ($saveFlag > 0) {

        echo ' <button class="btn blue"> '.Yii::t('app','Save Values').'</button>';
    }
    ?>


                            <button class="btn blue pull-right" id="preview" type="button"> Graph Preview</button>
                        </div>

                        <div class="clearfix">
                        </div>
                    </form>


<?php
} else {

    echo "Error unable to calibrate given data";
}
?></div>
        </div>
        <!--Portlet end-->
    </div>

    <!-- Modal -->
    <div class="modal fade" id="pop-form" tabindex="-1" role="dialog" aria-labelledby="pop-formLabel">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="pop-formLabel"> <?php echo Yii::t('app','Add Parameter'); ?></h4>
                </div>
                <div class="modal-body">

                    <form action="scaleParamPage.php?perform=submitAddScaleParam" method="post"  class="form-horizontal"> 

                        <div class="form-group">
                            <label class="col-md-3 control-label"><?php echo Yii::t('app','Output Parameter'); ?></label>
                            <div class="col-md-9">

                                <input class="form-control" type="text" name="addScaleParamSelection" value="">

                            </div>
                        </div>

                        <div class="form-group">
                            <label class="col-md-3 control-label"><?php echo Yii::t('app','Gain'); ?></label>
                            <div class="col-md-9">
                                <input class="form-control " placeholder="Gain" type="text" name="addScaleParamSelectionGain"> </div>
                        </div>


                        <div class="form-group">
                            <label class="col-md-3 control-label"><?php echo Yii::t('app','Offset'); ?></label>
                            <div class="col-md-9">
                                <input class="form-control " placeholder="Offset" type="text" name="addScaleParamSelectionOffset"> </div>
                        </div>


                    </form>




                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo Yii::t('app','Close'); ?></button>
                    <button type="button" class="btn btn-primary" onclick="saveEntry()"><?php echo Yii::t('app','Save'); ?> </button>
<?php
if ($saveFlag >= 0) {

			$tSave = Yii::t('app','Save'); 
			echo '<button type="button" class="btn btn-primary" onclick="saveEntry()">'.$tSave .'</button>';
}
?>
                </div>
            </div>
        </div>
    </div>




    <div class="modal fade" id="preview-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
        <div class="preview-modal modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title" id="myModalLabel">Graph Preview</h4>
                </div>
                <div class="modal-body">

                    <div class="portlet light waiting" id="graphs-section" >
                        <div class="portlet-title tabbable-line">
                            <div class="caption">
                                <i class="icon-graphp-o font-blue"></i>
                                <span class="caption-subject bold font-blue uppercase"> <?php echo Yii::t('plot', 'Lab VS Analyzer Trend Plot'); ?></span>

                            </div>
                            <div class="tools">
                            </div>
                        </div>
                        <div class="portlet-body ">

                            <div class="row">

                                <?php
                                $gData = $series;

                                $series = [];

                                foreach ($gData as $key => $item) {

                                    // Get the series name

                                    $openingTag = '
                        <!-- BEGIN Portlet PORTLET-->
                        <div class="portlet light" style="min-height:380px;border-top: 2px rgba(0,0,0,.03) solid;">
                        <div class="portlet-body">';
                                    $closeTag = '</div></div>';

                                    echo "<div class ='col-md-6'>$openingTag<div  id='comp_graph_" . $key . "'>$closeTag</div></div>";
                                    $localKeyVal = $key;


                                    $anData = array_values($item['an']);
                                    $labData = array_values($item['lab']);
                                    $offsetData = array_values($item['offset']);
                                    $series[$key][0] = [
                                        'type' => 'spline',
                                        'name' => 'AN_' . $key,
                                        'data' => array_reverse($anData),
                                        'title' => [
                                            'text' => ''
                                        ],
                                        'marker' => [
                                            'lineWidth' => 2,
                                            'lineColor' => '#7EFC7A',
                                            'fillColor' => 'white'
                                        ]
                                    ];

                                    $series[$key][1] = [
                                        'type' => 'spline',
                                        'name' => 'LAB_' . $key,
                                        'color' => '#7EFC7A',
                                        'data' => array_reverse($labData),
                                        'title' => [
                                            'text' => ''
                                        ],
                                        'marker' => [
                                            'lineWidth' => 2,
                                            'lineColor' => '#7EFC7A',
                                            'fillColor' => 'white'
                                        ]
                                    ];


                                    $series[$key][2] = [
                                        'type' => 'spline',
                                        'color' => 'red',
                                        'name' => 'Analysis After Offset ' . $key,
                                        'data' => array_reverse($offsetData),
                                        'title' => [
                                            'text' => ''
                                        ],
                                        'marker' => [
                                            'lineWidth' => 2,
                                            'lineColor' => '#7EFC7A',
                                            'fillColor' => 'white'
                                        ]
                                    ];
                                }



                                $this->registerJs("var CompGraph = " . json_encode($series) . ";", yii\web\View::POS_BEGIN);
                                ?>
                            </div>
                        </div>
                    </div>


                    <!--End of portlet-->
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <!--<button type="button" class="btn btn-primary">Save changes</button>-->
                </div>
            </div>
        </div>
    </div>
</div>




<script type="text/javascript">

    var scaleList = <?php echo json_encode($scaleitems) ?>;


    window.onload = function () {


        $('#preview').on('click', function () {

            $("#preview-modal").modal("show");
            initCompGraph();
        })

        var series = <?php echo json_encode($series) ?>;


        $('input:checkbox[name="select-check"]').on('change', toogleCheckbox)
        $("#submitPr").on('submit', function (e) {

            e.preventDefault();

            console.log('Processing Data ....')

            var formData = serializeObject($("#submitPr"));

//              var formArray = .serializeObject();


            $.ajax({
                url: '<?php echo Yii::app()->createAbsoluteUrl('/calib/saveProposed') ?>',
                data: {formData: formData, scaleList: scaleList},
                success: function () {
                    swal('success', 'New Proposed values saved successfully', 'success');

                }
            });

        });



        $(".overwrite").on('click', function () {


//            alert('Debug');
            var cValue = $(this).val();

            var cGain = $('input[name=' + cValue + '_current_gain]').val();
            var cOffset = $('input[name=' + cValue + '_current_offset]').val()

            $('input[name=' + cValue + '_proposed_gain]').val(cGain);
            $('input[name=' + cValue + '_proposed_offset]').val(cOffset);

        })


    }

    function init() {

        $(".edit").on('click', function () {

            edit(this);
        })

        $(".delete").on('click', function () {

            deleteScale(this)
        })


    }

    function initCompGraph() {


        for (x in CompGraph) {
//            console.log("G ID", graphList[x]['id'], x)

//debugger;

            var temp = CompGraph[x]


//                console.log(temp,'series');

            // Create the chart


            Highcharts.stockChart("comp_graph_" + x, {

                plotOptions: {
                    series: {
                        marker: {
//                            enabled: true
                        }
                    }
                },

                legend: {
                    enabled: true,
                    align: 'center',
//        backgroundColor: '#FCFFC5',
//        borderColor: 'black',
//        borderWidth: 2,
                    layout: 'horizontal',
//        verticalAlign: 'top',
//        y: 100,
//        shadow: true
                },
                rangeSelector: {
                    inputEnabled: false,
                    selected: 4,
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
                    text: 'Lab vs Analysis Results'
                },

                series: CompGraph[x], //CompList.data,
                credits: false,
                exporting: {

                }


            });


        }

    }


    function toogleCheckbox() {

        console.log('check box');

        if ($('input:checkbox[name="select-check"]').prop('checked')) {


            $('input:checkbox').prop("checked", true);

        } else {


            $('input:checkbox').prop("checked", false);
        }


    }

    function edit(ele) {

        var scaleEle = $(ele).attr('data-name');

        var temp = scaleList[scaleEle];
//        console.log('magic' , temp);

        $("input[name='addScaleParamSelection']").val(scaleEle);
        $("input[name='addScaleParamSelectionGain']").val(temp.gain);
        $("input[name='addScaleParamSelectionOffset']").val(temp.offset);


        $("#pop-form").modal('show');
    }


    function overwrite() {



    }
    function deleteScale(ele) {


        var scaleName = $(ele).attr('data-name')
        swal({
            title: "Are you sure?",
            text: "want to delete scale element?",
            icon: "warning",
            buttons: true,
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {

//                deleteLabHistory(id)

                console.log("Deleting =>", scaleName);
                delete scaleList[scaleName];
                saveAllEntries()

            } else {
                //swal("Your imaginary file is safe!");
            }
        }); // EOF swal

    }
    function populateDefault() {
        $.ajax({
            url: '<?php echo Yii::app()->createAbsoluteUrl('/labdata/getscale', ['lab_hist_id' => $_REQUEST['lab_hist_id']]) ?>',
            success: function (response) {

                var list = response.data;

                var currentList = list.current;
                var calList = list.cal;
                var propList = list.proposed;

                for (var y in propList) {


                    var value = propList[y];



                    scaleList.push(y);
                    var trS = '<tr>';

                    var tdEle = '<td>' + y + '</td>';

                    var tdDg = '<td> <input class="form-control disabled " type="text" disabled="disabled"  name="' + y + '_current_gain" value=""/></td>';
                    var tdDo = '<td> <input class="form-control readonly" type="text" disabled="disabled" name="' + y + '_current_offset" value=""/></td>';

                    // echo '<td> <input class="form-control disabled" type="text" disabled="disabled" name="'.$value.'_cal_offset" value=""/></td>';
                    // echo '<td> <input class="form-control disabled" type="text" disabled="disabled" name="'.$value.'_cal_gain" value=""/></td>';


                    var tdPg = '<td> <input class="form-control " type="text" name="' + y + '_proposed_gain" value=""/></td>';

                    var tdPo = '<td> <input class="form-control " type="text" name="' + y + '_proposed_offset" value=""/></td>';


                    var tdCB = '<td class="text-center"> <input class="overwrire" type="checkbox" name="' + y + '_overwrite" value="' + y + '"/></td>';

                    var trE = '</tr>';


                    $("#scaleList").append(trS + tdEle + tdDg + tdDo + tdPg + tdPo + tdCB + trE);

                    $('input:checkbox[name="' + y + '_overwrite"]').prop('checked', true);



                    $('input[name="' + y + '_proposed_offset"]').val(value.offset);

                    $('input[name="' + y + '_proposed_gain"]').val(value.gain);
                }// end of for
//                init();

                for (var x in currentList) {

                    var value = currentList[x];


                    $('input[name="' + x + '_current_offset"]').val(value.offset);

                    $('input[name="' + x + '_current_gain"]').val(value.gain);
                }// end of for
                //
                $('input:checkbox[name="select-check"]').prop('checked', true);



            }// End of function





        });
    }


    function saveEntry() {


//    var csrfToken = $('meta[name="csrf-token"]').attr("content");
//        $("#pop-form").modal('close');
        var scale = $("input[name='addScaleParamSelection']").val();
        var gain = $("input[name='addScaleParamSelectionGain']").val();
        var offset = $("input[name='addScaleParamSelectionOffset']").val();

        scaleList[scale].gain = gain;
        scaleList[scale].offset = offset;

        saveAllEntries();

    }
    function saveAllEntries() {

        $.ajax({
            type: 'GET',
            url: '<?php echo Yii::app()->createAbsoluteUrl('/calib/save-entry') ?>',
            data: { scaleList: scaleList, offset: $("input[name='addScaleParamSelectionOffset']").val(), gain: $("input[name='addScaleParamSelectionGain']").val()},
            success: function (response) {

                $("#pop-form").modal('hide');
                loadScaleTable();
            }
        })
    }

    function serializeObject($form) {
        var unindexed_array = $form.serializeArray();
        var indexed_array = {};

        $.map(unindexed_array, function (n, i) {
            indexed_array[n['name']] = n['value'];

        });

        return indexed_array;
    }
</script>
