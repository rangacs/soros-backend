<?php
$cs = Yii::app()->clientScript;
$baseUrl = Yii::app()->baseUrl;
$viewTemplate = Yii::app()->createAbsoluteUrl('labdata/viewTemplate');
$currentDate = date('m/d/Y', time());

//$allowTempUpload = $mysqlTool->getRmSetting('ALLOW_TEMPLATE_UPLOAD');
//$allowDataUpload = $mysqlTool->getRmSetting('ALLOW_LABDATA_UPLOAD');


$query1 = 'select varValue from rm_settings where varKey = "LABLINK_ALLOW_TEMPLATE_UPLOAD"';

$allowTempUpload = Yii::app()->db->createCommand($query1)->queryScalar();

$query2 = 'select varValue from rm_settings where varKey = "LABLINK_ALLOW_LABDATA_UPLOAD"';

$allowDataUpload = Yii::app()->db->createCommand($query2)->queryScalar();


$date = new DateTime(date('Y-m-d H:i:s', time()));
$minutes = $date->format('i');
if ($minutes > 0) {
    $date->modify("+1 hour");
    $date->modify('-' . $minutes . ' minutes');
}

$nextDate = $date->format('m/d/Y');

$nextHour = $date->format('H:00:00');
$labDataHistoryDeleteUrl = Yii::app()->createAbsoluteUrl('LabDataHistory/delete');
$offsetRangeSettings = LabUtility::getOffsetRangeSettings();
$cs->registerScript('appConfig', "var baseUrl = " . json_encode($baseUrl) . ";"
        . "var lab_data_hitory_delete = '{$labDataHistoryDeleteUrl}';"
        . "var view_template = '{$viewTemplate}';", CClientScript::POS_BEGIN);
?>


<div id="alert-info">

</div>
<div class="portlet light waiting "  >
    <div class="portlet-body ">
        <div class="tabbable-custom">
            <ul class="nav nav-tabs" id="ulNavTab">
                <li >
                    <a href="#data-history" data-toggle="tab" ><?php echo Yii::t('app', 'Lab Data History'); ?>  </a>
                </li>
                <?php if ($allowTempUpload == 1) : ?>
                    <li>
                        <a href="#portlet_tab2" data-toggle="tab" ><?php echo Yii::t('app', 'Lab Data Template'); ?>   </a>
                    </li>
                <?php
                endif;
                if ($allowDataUpload == 1):
                    ?>
                    <li class="">
                        <a href="#portlet_tab3" data-toggle="tab" ><?php echo Yii::t('app', 'Lab Data'); ?>    </a>
                    </li>
<?php endif; ?>

                <li class="active">
                    <a href="#data_entry" data-toggle="tab" ><?php echo Yii::t('app', 'Manual Entry'); ?>    </a>
                </li>
            </ul>
            <div class="tab-content" style="min-height: 700px">
                <div class="tab-pane " id="data-history">

                    <div class="table-toolbar">
                        <div class="row">


                            <div class="note note-info">
                                <h4 class="block"><span class="caption-subject bold uppercase"> <?php echo Yii::t('app', 'Lab Data Repository'); ?></span></h4>
                                <p><?php echo Yii::t('app', 'Note! Lab files once deleted will not be able to retrieved from our database.
                                    Please be careful while working with your lab files.') ?>
                                </p>
                            </div>

                            <div class="col-md-12">
<?php if ($allowDataUpload == 1): ?>

                                    <div class="btn-group pull-right">
                                        <button class="btn sbold blue" id="switchTabBut" >
    <?php echo Yii::t('app', 'Add New'); ?>
                                            <i class="fa fa-plus"></i>
                                        </button>
                                    </div>
<?php endif; ?>
                            </div>
                        </div>
                    </div>
                    <table class="table table-blue" >
                        <thead>
                            <tr>

                                <th><?php echo Yii::t('app', 'Lab Sample File Name') ?>  </th>
                                <th> <?php echo Yii::t('app', 'Sample Type ') ?></th>
                                <th> <?php echo Yii::t('app', 'Uploaded By') ?> </th>
                                <th><?php echo Yii::t('app', 'Uploaded Date') ?>  </th>
                                <th><?php echo Yii::t('app', 'Actions') ?> </th>
                            </tr>
                        </thead>
                        <tbody>                           

                            <?php
                            foreach ($historyRecord as $key => $record) {


                                $actionText = Yii::t('app', "Actions");
                                // $userModel = UsergroupsUser::model()->find('id=:ID', array(':ID'=> $record['uploaded_by'])); ////$record['uploaded_by'] TODO
                                //    var_dump($record['lab_hist_id']);
                                // die();
                                $viewLink = CHtml::link('<i class="icon-eye"></i>' . Yii::t('app', 'View') . '', array('labdata/view', 'lab_hist_id' => $record['lab_hist_id']));
                                $compLink = CHtml::link('<i class="icon-link"></i>' . Yii::t('app', 'AN-Compare') . '', array('labdata/historyCompare', 'lab_hist_id' => $record['lab_hist_id'], 'rid' => $record['lab_hist_id']));


                                echo '<tr class="odd gradeX" id="row-' . $record['lab_hist_id'] . '">';
                                echo "<td>" . $record['temp_name'] . "</td>";
                                echo '<td>' . $record['sample_type'] . "</td>";
                                echo '<td><span class="label label-sm label-danger"></span>';
                                echo "<td>" . $record['upload_time'] . "</td>";
                                echo "<td>" .
                                '<div class="btn-group">
                                            <button class="btn btn-sm blue dropdown-toggle" type="button" data-toggle="dropdown" aria-expanded="false">
                                            ' . $actionText . ' 
                                                <i class="fa fa-angle-down"></i>
                                            </button>
                                            <ul class="dropdown-menu" role="menu">
                                                <li>' . $viewLink . '</li>
                                                <li>' . $compLink . '</li>
                                                <li class="divider"> </li>
                                                <li>
                                                    <a href="javascript:;" onclick="deleteConfirm( ' . $record['lab_hist_id'] . ')">
                                                        <i class="icon-trash"></i>' . Yii::t('app', 'Delete') . '</a>
                                                </li>
                                            </ul>
                                        </div>' .
                                "</td>";
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>

                    <?php if ($allowTempUpload == 1) : ?>
                    <div class="tab-pane " id="portlet_tab2">
                        <?php
                        $existingTemplate = '';
                        if ($templateExits) {

                            $existingTemplate = '<button class="btn btn-lg red" onclick="viewTemplate()">
                                                ' . Yii::t('app', 'View Active Template') . ' 
                                                <i class="fa fa-settings" ></i>
                                             </button>';
                        }
                        ?>
                        <div class="tab-pane text-align-center" id="labDataTab">


                            <div class="note note-info text-align-left">
                                <h4 class="block"><span class="caption-subject bold uppercase"> <?php echo Yii::t('app', 'Lab Information Template') ?> </span> </h4>

                                <p><?php echo Yii::t('app', 'Note! Template file creates a base for our system to understand how lab data is being imported.
                                Please be careful while entering data and uploading files.') ?>
                                </p>
                            </div>
                            <div class="row" id='create-template-button-container'>


                                <div class="col-md-6">
                                    <button id="createNewTemplateBut" class="btn btn-lg blue">
    <?php echo Yii::t('app', 'Create New Template') ?> 
                                        <i class="fa fa-plus-circle" ></i>
                                    </button>
                                </div>

                                <div class="col-md-6">

    <?php echo $existingTemplate; ?>

                                </div>
                            </div>

                            <div id="createNewTemplate"  class="row">

                                <form id="lab-data-template" class="form form-inline " action="" method="POST" enctype="multipart/form-data">                        
                                    <div class="form-body">
                                        <div class="form-group col-md-12" >

                                            <label class=" control-label col-md-3 lable-style font-blue-madison">
    <?php echo Yii::t('app', ' Please upload your new lab template file') ?></label>

                                            <div class="col-md-3 text-align-left">
                                                <input type="hidden" id="MAX_FILE_SIZE" name="MAX_FILE_SIZE" value="300000" />
                                                <input type="file" class=""  required="required" id="fileselect" name="fileselect[]" multiple="multiple" />
                                                <span class="help-block"> 
                                                    <a  data-toggle="modal" onclick="viewTemplate()">
    <?php echo Yii::t('app', 'Please refer to our') ?>  <u>" <?php echo Yii::t('app', 'Example Template') ?> "</u>
                                                    </a>

                                                </span>
                                            </div>

                                            <div class='col-md-4 text-align-left'>

                                                <button role="button" type="submit" class="btn blue"> <i class="fa fa-upload"></i><?php echo Yii::t('app', 'Upload Files') ?> </button>
                                                <button type="button" class="btn default" id='template-cancel'> <?php echo Yii::t('app', 'Cancel') ?></button>

                                            </div>




                                        </div>
                                    </div>
                                </form>
                            </div>


                        </div>

                    </div>

                <?php
                endif;
                if ($allowDataUpload == 1) :
                    ?>

                    <div class="tab-pane " id="portlet_tab3">



                        <div class="note note-info text-align-left">
                            <h4 class="block"><span class="caption-subject bold uppercase"> <?php echo Yii::t('app', 'Lab Data File') ?> </span></h4>
                            <p><?php echo Yii::t('app', 'Note! Please refer to your template file to understand how lab data is being imported.
                            Please be careful while entering data and uploading files.') ?>
                            </p>
    <?php $compareUrl = Yii::app()->createAbsoluteUrl('lab-data/compare'); ?>

                        </div>
                        <div class="clearfix"> </div>

    <?php if ($activeTemplate) { ?>
                            <!--                        <div class="row">
                            
                                                        <a class="btn  blue float-right" href="<?php echo $compareUrl ?>" > Compare Data </a>
                            
                                                    </div>-->
                            <form id="lab-data"  action="" method="POST" enctype="multipart/form-data" class="form-inline  has-validation">
                                <div class="form-body">
                                    <div class="form-group  col-md-12">
                                        <label class="col-md-3 control-label lable-style font-blue-madison"><?php echo Yii::t('app', 'Please upload your new Lab data file.') ?></label>
                                        <div class="col-md-3 text-align-left">
                                            <input type="hidden" id="MAX_FILE_SIZE" name="MAX_FILE_SIZE" value="300000" />
                                            <input type="file" id="lab-data" name="labdata[]"  required="required" multiple="multiple" />
                                            <a  data-toggle="modal" data-target="#example-data">
                                                <span class="help-"> <?php echo Yii::t('app', 'Please reference your "Active Lab Data Template".') ?> </span>

                                            </a>

                                        </div>
                                        <div class="col-md-3 text-align-left">
                                            <button role="button" type="submit" class="btn blue"> <i class="fa fa-upload"></i> <?php echo Yii::t('app', 'Upload Files') ?></button>
                                            <!--<button type="button" class="btn default">Cancel</button>-->
                                        </div>

                                        <div class='col-md-3 text-align-left hide'>

                                            <a class="btn blue" href='<?php echo Yii::app()->createAbsoluteUrl('/labdata/dataentry') ?>'> 
                                                <i class="fa fa-plus-circle"></i><?php echo Yii::t('app', 'Add Manually ') ?> </a>

                                        </div>
                                    </div>

                                </div>
                            </form>
                            <div class="clearfix"> </div>

    <?php } else { ?>

                            <div class="row">

                                <div class="col-md-12"> <h2 class="font-red-mint"><?php echo Yii::t('app', 'Please upload template before uploading data') ?>  </h2>  </div>
                            </div>

    <?php } ?>

                    </div>

<?php endif ?>
                <div class="tab-pane  active" id="data_entry">

                    <div class="note note-info text-align-left">
                        <h4 class="block"><span class="caption-subject bold uppercase"> <?php echo Yii::t('app', 'Manual data entry form') ?> </span> </h4>

                        <p><?php echo Yii::t('app', 'Note! You are entering lab data manually, Invalid data may cause abnormal behaviour of the system. Please be extra careful while entering data.') ?>
                        </p>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <form id='data-entry-form' class="form-horizontal has-validation" method="POST" action="<?php echo Yii::app()->createAbsoluteUrl('/labdata/SaveLabValues') ?>">
                                <div class="form-group">
                                    <label for="" class="col-sm-2 control-label"><?php echo Yii::t('app', 'EndTime') ?> </label>

                                    <div class="col-sm-10">
                                        <div class="row">
                                            <div class="col-md-6">

                                                <div class="input-group date">

                                                    <input type="hidden" id="timestamp" name="LabValues[EndTime]" /> 
                                                    <input id="dateOnlyField" type="text" class="form-control"  name="LabValues[DateOnly]" placeholder="EndTime" 
                                                           value="<?php echo $nextDate ?>" required>



                                                </div>

                                            </div>
                                            <div class="col-md-6">
                                                <select  id="timeOnlyField" class="form-control float-left"  name="LabValues[TimeOnly]"  placeholder="TimeOnly"   style="width:80px;">
                                                    <option value="00:00:00">00:00</option>
                                                    <option value="01:00:00">01:00</option>
                                                    <option value="02:00:00">02:00</option>
                                                    <option value="03:00:00">03:00</option>
                                                    <option value="04:00:00">04:00</option>
                                                    <option value="05:00:00">05:00</option>
                                                    <option value="06:00:00">06:00</option>
                                                    <option value="07:00:00">07:00</option>
                                                    <option value="08:00:00">08:00</option>
                                                    <option value="09:00:00">09:00</option>
                                                    <option value="10:00:00">10:00</option>
                                                    <option value="11:00:00">11:00</option>
                                                    <option value="12:00:00">12:00</option>
                                                    <option value="13:00:00">13:00</option>
                                                    <option value="14:00:00">14:00</option>
                                                    <option value="15:00:00">15:00</option>
                                                    <option value="16:00:00">16:00</option>
                                                    <option value="17:00:00">17:00</option>
                                                    <option value="18:00:00">18:00</option>
                                                    <option value="19:00:00">19:00</option>
                                                    <option value="20:00:00">20:00</option>
                                                    <option value="21:00:00">21:00</option>
                                                    <option value="22:00:00">22:00</option>
                                                    <option value="23:00:00">23:00</option>
                                                </select>

                                                <input type="time" name="customtime" style="display: none;width: 120px"id="customtime" class="form-control float-left"/>

                                                <button id='custom-edit' type="button" class="btn btn-defaultn float-right " ><i class="fa fa-pencil" >
                                                    </i></button>
                                            </div>
                                        </div>

                                    </div>


                                </div>

                                <div class="form-group">
                                    <label for="" class="col-sm-2 control-label">SiO2</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" data-min="12.95" data-max="14" id="SiO2" placeholder="SiO2" name="LabValues[SiO2]" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="" class="col-sm-2 control-label">Al2O3</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" data-min="3" data-max="3.7" id="Al2O3" placeholder="Al2O3" name="LabValues[Al2O3]" required>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="" class="col-sm-2 control-label">Fe2O3</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" data-min="2" data-max="2.7" id="Fe2O3" placeholder="Fe2O3" name="LabValues[Fe2O3]" required>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="" class="col-sm-2 control-label">CaO</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" data-min="41.5" data-max="44.5"  id="CaO" placeholder="CaO" name="LabValues[CaO]" required>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="" class="col-sm-2 control-label">KH</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" data-min="0" data-max="3"  id="KH" placeholder="KH" name="LabValues[KH]" required>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="" class="col-sm-2 control-label">SM</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" data-min="0" data-max="3"  id="N" placeholder="SM" name="LabValues[N]" required>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="" class="col-sm-2 control-label">IM</label>
                                    <div class="col-sm-10">
                                        <input type="text" class="form-control" data-min="0" data-max="3"  id="P" placeholder="IM" name="LabValues[P]" required>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <div class="col-sm-offset-2 col-sm-10">
                                        <button type="button"  onclick="entryConfim()" class="btn btn-theme-color">

<?php echo Yii::t('app', 'Save Lab Values'); ?>


                                        </button>

                                        <button type="button"  onclick="populateRandom()" class="btn btn-theme-color hide">

<?php echo Yii::t('app', 'Fill Default	 Values'); ?>


                                        </button>
                                    </div>
                                </div>
                            </form>

                        </div>

                    </div>




                    <!-- Modal -->
                    <div class="modal fade" id="validation-table-modal" tabindex="-1" role="dialog" aria-labelledby="pop-formLabel">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title" id="pop-formLabel"><?php echo Yii::t('app', 'Do you wish to save these records?'); ?></h4>
                                </div>
                                <div class="modal-body">

                                    <table  style="" class="table" id='validation-table'>

                                    </table>
                                    </form>

                                </div>
                                <div class="modal-footer">

                                    <button class="btn btn-blue" onclick="isRecordExits()"> 
<?php echo Yii::t('app', 'Save') ?>  </button>
                                    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo Yii::t('app', 'Cancel'); ?></button>

                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="run-log" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="rawmix-run-log modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><?php echo Yii::t('app', 'Manual entry confirmation') ?></h4>
            </div>
            <div class="modal-body">

                <div class="col-md-2">
                    <select class="form-control filter-lg-msg" > 
                        <option><?php echo Yii::t('app', 'Select  Level') ?></option>


                        <option value="0">Level 0</option>
                        <option value="1">Level 1</option>
                        <option value="2"> Level 2</option>
                        <option value="3"> Level 3</option>
                        <option value="4"> Level 4</option>
                        <option  value="all"> Show All</option>

                    </select>  
                </div>

                <div class="col-md-2">
                    <button class='btn btn-theme-color' onclick="clearLog()"> Clear Log </button>  
                </div>


                <div class="col-md-12">
                    <div class="table-responsive" style="height: 700px;overflow: scroll">
                        <table  class="table table-blue" >
                            <tr> <th> Message Level</th> <th> Message</th><th> Description</th> <th> Timestamp</th></tr>
                            <tbody id="log-messages">

                            </tbody>
                        </table>
                    </div>

                </div>

                <div class="clearfix"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" id="close-modal" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>


<!-- Modal -->
<div class="modal fade " id="view_template" tabindex="-1" role="dialog" >
    <div class="modal-dialog preview-modal" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel"><?php echo Yii::t('app', 'Existing Template') ?></h4>
            </div>
            <div class="modal-body">

                <div class="table-responsive">
                    <div id="view_template_table" title="Enter Lab Data">



                    </div>

                </div>
            </div>
            <div class="modal-footer">
                <button type="button"  class="btn btn-default" data-dismiss="modal"><?php echo Yii::t('app', 'Close') ?></button>
            </div>
        </div>
    </div>
</div>      <!-- Button trigger modal -->


<?php echo $this->renderPartial('stubs/modals') ?>

<script type="text/javascript">



    // Variable to store your files
    var files;

    var offsetRangeSettings = <?php echo json_encode($offsetRangeSettings) ?>;
    var eleOffset;



    window.onload = function () {


        $('#dateOnlyField').datetimepicker({format: 'L'});


        $('.timepick').datetimepicker({format: 'H:i'});

        $("#timeOnlyField").val('<?php echo $nextHour ?>');

        $("#template-cancel").on('click', function () {

            $("#create-template-button-container").show();
            $("#createNewTemplate").hide();
        })
        init();

        $("#switchTabBut").click(function () {
            $('#ulNavTab a[href="#portlet_tab3"]').click();
        });


        $('filter-lg-msg').on('change', filterLogMessages);

        $("#createNewTemplateBut").click(function () {


            $("#create-template-button-container").hide();
            $("#createNewTemplate").show();
        });


        $('#custom-edit').on('click', function () {
            $('#customtime').toggle();
            $('#timeOnlyField').toggle();

        })

        $('#timeOnlyField').on('change', function () {
            $('#customtime').val('');
        })
    }

    function init() {
        $('input[type=file]').on('change', prepareUpload);

        $('#lab-data').on('submit', uploadData);
        $('#lab-data-template').on('submit', uploadTemplate);


    }
// Grab the files and set them to our variable
    function prepareUpload(event)
    {

        //console.log('Debug me');
        files = event.target.files;

    }

    // Catch the form submit and upload the files

    function uploadData(event)
    {

        event.stopPropagation(); // Stop stuff happening
        event.preventDefault(); // Totally stop stuff happening
        // alert('tryy');

        // START A LOADING SPINNER HERE

        // Create a formdata object and add the files
        var data = new FormData();
        $.each(files, function (key, value)
        {
            data.append(key, value);
        });



        var tmpFileName = files[0].name;

        console.log(tmpFileName);

        var fileNameAr = tmpFileName.split(',');


        var tmpFileName = files[0].name;


        var fileNameAr = tmpFileName.split('.');

        var inputField = document.createElement("input");

        $(inputField).addClass('swal-content__input');
        $(inputField).attr('id', 'data-file-name');
        $(inputField).val("Lab_data_" + fileNameAr[0]);


        swal({
            text: 'Please enter data file name.',
            content: inputField,
            buttons: {

                confirm: {
                    text: "Save",
                    value: true,
                    visible: true,
                    className: "",
                    closeModal: true
                },
                cancel: {
                    text: "Cancel",
                    value: 'cancel',
                    visible: true,
                    className: "",
                    closeModal: true,
                },

            },
        }).then(name => {


            if (name == 'cancel') {

                return false;

            }

            var tmpN = $("#data-file-name").val();
            //if empty file name
            if (tmpN.length <= 0)
            {

                swal('Error', 'File name can\'t be empty', 'error');
//                uploadTemplate(event);
                return false;

            }

            data.append('user_file_name', tmpN);


            $.ajax({
                url: '<?php echo Yii::app()->createAbsoluteUrl('labdata/uploadLabData') ?>',
                type: 'POST',
                data: data,
                cache: false,
                dataType: 'json',
                processData: false, // Don't process the files
                contentType: false, // Set content type to false as jQuery will tell the server its a query string request
                success: function (data, textStatus, jqXHR)
                {

                    var rurl = '<?php echo Yii::app()->createAbsoluteUrl('labdata/compare') ?>'


                    swal('Success', 'Data Imported Successfully', 'success');
                    setTimeout(function () {
                        document.location.reload(true);
                    }, 1000);

                },
                error: function (jqXHR, textStatus, errorThrown)
                {


                    swal('Error', "Upload correct data ", 'error');
                    if (jqXHR.status !== 400) {

                        swal('Error', jqXHR.responseJSON.errors, 'error');

                    } else {


                        swal('Error', "Upload correct data ", 'error');


                    }

                }
            });//end of ajax

        });//End of swal 


    }


    function uploadTemplate(event)
    {

        event.stopPropagation(); // Stop stuff happening
        event.preventDefault(); // Totally stop stuff happening

        // START A LOADING SPINNER HERE
//        debugger;
        // Create a formdata object and add the files
        var data = new FormData();
        $.each(files, function (key, value)
        {
            data.append(key, value);
        });



        var tmpFileName = files[0].name;


        var fileNameAr = tmpFileName.split('.');

        var inputField = document.createElement("input");

        $(inputField).addClass('swal-content__input');
        $(inputField).attr('id', 'temp-file-name');


        $(inputField).val("Lab_template_" + fileNameAr[0])

        swal({
            text: 'Please enter lab template name.',
            content: inputField,
            buttons: {

                confirm: {
                    text: "Save",
                    value: true,
                    visible: true,
                    className: "",
                    closeModal: true
                },
                cancel: {
                    text: "Cancel",
                    value: 'cancel',
                    visible: true,
                    className: "",
                    closeModal: true,
                },

            },
        }).then(name => {

//            debugger
            //If cancel button clicked
            if (name === 'cancel') {

                return false;

            }


            var tname = $("#temp-file-name").val();
            //if empty file name
            if (tname.length <= 0)
            {

                swal('Error', 'File name can\'t be empty', 'error');

                return false;

            }

            data.append('user_file_name', tname);


            $.ajax({
                url: '<?php echo Yii::app()->createAbsoluteUrl('labdata/importTemplate') ?>',
                type: 'POST',
                data: data,
                cache: false,
                dataType: 'json',
                processData: false, // Don't process the files
                contentType: false, // Set content type to false as jQuery will tell the server its a query string request
                success: function (odata, textStatus, jqXHR)
                {
                    swal('Success', 'Template Imported Successfully', 'success');
                    clearFileUpload();
                    setTimeout(function () {
                        document.location.reload(true);
                    }, 1000);

                },
                error: function (jqXHR, textStatus, errorThrown)
                {

                    swal('Error', 'Upload correct template', 'error');
                }
            });//end of ajax

        });//End of swal 


    }// End of uploadTemplate()


    function submitForm(event, data)
    {
        // Create a jQuery object from the form
        $form = $(event.target);

        // Serialize the form data
        var formData = $form.serialize();

        // You should sterilise the file names
        $.each(data.files, function (key, value)
        {
            formData = formData + '&filenames[]=' + value;
        });

        $.ajax({
            url: 'submit.php',
            type: 'POST',
            data: formData,
            cache: false,
            dataType: 'json',
            success: function (data, textStatus, jqXHR)
            {
                if (typeof data.error === 'undefined')
                {
                    // Success so call function to process the form
                    console.log('SUCCESS: ' + data.success);
                } else
                {
                    // Handle errors here
                    console.log('ERRORS: ' + data.error);
                }
            },
            error: function (jqXHR, textStatus, errorThrown)
            {
                // Handle errors here
                console.log('ERRORS: ' + textStatus);
            },
            complete: function ()
            {
                // STOP LOADING SPINNER
            }
        });
    }

    function deleteConfirm(id) {
        swal({
            title: "Are you sure?",
            text: "Once deleted, you will not be able to recover this data file!",
            icon: "warning",
            buttons: {

                confirm: {
                    text: "Delete",
                    value: true,
                    visible: true,
                    className: "",
                    closeModal: true
                },
                cancel: {
                    text: "Cancel",
                    value: null,
                    visible: true,
                    className: "",
                    closeModal: true,
                },

            },
            dangerMode: true,
        }).then((willDelete) => {
            if (willDelete) {

                deleteLabHistory(id)

            } else {
                //swal("Your imaginary file is safe!");
            }
        }); // EOF swal

    }


    function setAccessCode(request) {
        request.setRequestHeader("X-Access-Token", 'access_token');
    }



    function deleteLabHistory(id) {
        var param = '?&id=' + id
        $.ajax({
            url: lab_data_hitory_delete + param,
            method: "DELETE",
            beforeSend: setAccessCode,
            success: function (message) {
                swal("Lab data history record deleted ", {
                    icon: "success",
                });

                $("#row-" + id).remove();
            },
            error: function (response, status) {


            }
        });
    }


    function viewTemplate(id) {
        $.ajax({
            url: view_template,
            beforeSend: setAccessCode,
            success: function (message) {

                $("#view_template").modal('show');
                $('#view_template_table').html(message)
            },
            error: function (response, status) {


            }
        });

    }

    function fileUploadPrompt() {

        swal({
            text: 'Search for a movie. e.g. "La La Land".',
            content: "input",
            button: {
                text: "Search!",
                closeModal: false,
            },
        }).then(name => {
            if (!name)
                throw null;


        })
                .then(results => {
                    return results.json();
                });

    }
    function clearFileUpload() {

        App.alert({
            container: '.alert-info',
            place: 'top',
            type: 'success',
            message: 'File Uploaded Successfully',
            close: true,
            reset: true,
//                    focus: $("#alert_focus").is(":checked"),
            closeInSeconds: 5,
            icon: 'check'
        });



        $("#create-template-button-container").show();
        $("#createNewTemplate").hide();

    }



    function entryConfim(id) {

        var entries = $("#data-entry-form input:text");



        $('#validation-table').html('');
        entries.each(function () {

            var ele = $(this).attr('placeholder');

            if (ele == "Al2O3" || ele == "Fe2O3" || ele == "SiO2" || ele == "CaO") {

                var min = offsetRangeSettings[ele]['min'];
                var max = offsetRangeSettings[ele]['max'];

            } else {

                var min = parseFloat($(this).attr('data-min'));
                var max = parseFloat($(this).attr('data-max'));
            }

            var textValue = $(this).val();


            var value = textValue.length == 0 ? 0 : parseFloat(textValue);
            var cellStyle = '';
            var data = '-';

            if (ele == "EndTime") {

                var timeString = $("#customtime").val();

                if (timeString.length > 0) {
                    var timeString = $("#customtime").val() + ":00";

                } else {
                    var timeString = $("#timeOnlyField").val();

                }
                var dateString = $('#dateOnlyField').val();
                var endTime = dateString + ' ' + timeString;

                $('#timestamp').val(timeString);
                data = endTime;

            } else if (ele == "TimeOnly")
            {
                data = "";
            } else if (value > min && value < max) {
                cellStyle = '';
                data = value;
            } else {
                data = value + ' ?';
                cellStyle = 'font-red';
            }

            if (ele != "TimeOnly") {
                var row = '<tr><td>' + ele + '</td><td class="' + cellStyle + '"> ' + data + '</td></tr>';
                //console.log(row);
                $('#validation-table').append(row);
            }
        });




        $('#validation-table-modal').modal('show');
//	alert($('#timestamp').val());

    }


    function isRecordExits() {


        //DataEntryExits
        var timeString = $("#timestamp").val();
        var dateString = $('#dateOnlyField').val();
        var endTime = dateString + ' ' + timeString;


        $.ajax({
            'url': '<?php echo Yii::app()->createAbsoluteUrl('/labdata/DataEntryExits') ?>',
            type: 'post',
            data: {'EndTime': endTime},
            success: function (res) {
                saveValues();
            },
            error: function () {

                var titile = '<h5> Do you want to over write existing record?</h5>';
                swal({
                    text: 'Record already exits ',
                    content: titile,
                    buttons: {

                        confirm: {
                            text: "Overwrite",
                            value: true,
                            visible: true,
                            className: "",
                            closeModal: true
                        },
                        cancel: {
                            text: "Cancel",
                            value: 'cancel',
                            visible: true,
                            className: "",
                            closeModal: true,
                        },

                    },
                }).then(name => {


                    if (name == 'cancel') {

                        //$('#validation-table-modal').modal('close');
                        return false;

                    }
                    saveValues();
                });//End of swal 


            }

        });// End of ajax




    }
    function saveValues() {

        var endTime =  $('#timestamp').val();

        var formData = $("#data-entry-form").serialize();

        $.ajax({
            'url': '<?php echo Yii::app()->createAbsoluteUrl('/labdata/SaveLabValues') ?>',
            type: 'post',
            data: {'formData': formData},
            success: function (res) {
                swal('Success', 'Lab record saved', 'success');

                $()

                if ('1' === '1') {


                    window.location = '<?php echo Yii::app()->createAbsoluteUrl('/labdata') ?>'
                    // buildLogMessages(res); 
                } else {


                    // window.location = '<?php echo Yii::app()->createAbsoluteUrl('/labdata') ?>' 
                }
            },
            error: function (res) {


                swal('Success', 'Lab record saved', 'success');
                //window.location = '<?php echo Yii::app()->createAbsoluteUrl('/labdata') ?>'


            }

        });

    }
    function clearLog() {


        $('#log-messages').html('');
        $.ajax({
            'url': '<?php echo Yii::app()->createAbsoluteUrl('/labdata/clearlog') ?>',

            success: function (res) {
                swal('Success', 'Old logs cleared', 'success');

            },
            error: function (res) {


                swal('Success', 'Lab records saved', 'success');
                // window.location = '<?php echo Yii::app()->createAbsoluteUrl('/labdata') ?>'


            }

        });


    }




    function populateRandom() {

        var entries = $("#data-entry-form input:text");

        entries.each(function () {

            var ele = $(this).attr('placeholder');
            if (ele === 'EndTime') {
                return;
            }
            var min = parseFloat($(this).attr('data-min'));
            var max = parseFloat($(this).attr('data-max'));

            var diff = max - min;


            var rValue = getRandom(0, diff);
            var nValue = rValue + min;

            $(this).val(nValue.toPrecision(4));

        });
    }


    function getRandom(min, max) {

        min = Math.ceil(min);
        max = Math.floor(max);
        var decimal = Math.floor(Math.random() * (max - min + 1)) + min
        return decimal / 100;
    }

    function buildLogMessages(logMessages) {

        var rows = '';
        for (index in logMessages) {

            var message = logMessages[index];

            var className = '';
            if (message.error_type === 1) {
                className = 'danger';
            } else if (message.error_type === 2) {
                className = 'warning';
            } else if (message.error_type === 3) {
                className = 'active';
            } else if (message.error_type === 4) {
                className = 'info';
            }

            var span = '<span class=" uppercase alert-view label label-sm label-' + className + ' ">' + className + '</span>'
            rows += "<tr class='" + className + "'><td>" + span + "</td><td>" + message.title + "</td><td>" + message.description + "</td><td>" + message.updated_at + "</td></tr>"
        }

        $("#log-messages").html(rows);
        $('#run-log').modal('show');

    }


    function filterLogMessages(level) {


        level = parseInt(level);
        $("#log-messages tr").hide();

        if (level === 1) {


            $("#log-messages tr.danger").show();

        } else if (level === 2) {



            $("#log-messages tr.danger").show();
            $("#log-messages tr.warning").show();
        } else if (level === 3) {


            $("#log-messages tr.active").show();

            $("#log-messages tr.danger").show();
            $("#log-messages tr.warning").show();
        } else if (level === 4) {


            $("#log-messages tr.info").show();

            $("#log-messages tr.active").show();

            $("#log-messages tr.danger").show();
            $("#log-messages tr.warning").show();
        } else {
            $("#log-messages tr").show();

        }

    }



</script>

