<style>
    .DTTT_button_print{

        display: none ! important;
    }
</style>
<section class="main-section grid_8">
    <nav class="">

        <?php
        $baseUrl = Yii::app()->basePath;

        $menuFile = $baseUrl . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "themes" . DIRECTORY_SEPARATOR . "tutorialzine1" . DIRECTORY_SEPARATOR . "views" . DIRECTORY_SEPARATOR . "pageDefaults" . DIRECTORY_SEPARATOR . "authLeftMenu.php";
        include_once($menuFile);
//        $tripInfo = TagQueuedHelper::getTripInfo($tagObject->tagID);

//        $activeCalibrationFile = WclRfidCalMap::findActiveCalibFile($tripInfo["w_matCode"]);
        ?>
    </nav>
    <div class="main-content">
        <section class="container_6 clearfix">
            <div class="grid_6 leading" style="min-height:250px !important;">

                <header class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-widget-header-blue ui-corner-top">
                    <h2 ><?php echo $tagObject->tagName ?>       
                        <a class="font-red underline" style="color:red;"></a></h2>
                </header>
                <section id="create" class="ui-tabs-panel ui-widget-content ui-corner-bottom">

                    <?php
                    $exAr = array("w_trId", "w_matName", "w_plantCode", "w_traName", "w_traCode", "w_suppName");
//                    TagHelper:: DumpTable($tripInfo, $exAr);
//                    echo "<br/>";
                    $elements = HeliosUtility::getDisplayElements();

                    //$elements = array('LocalendTime','SiO2','Al2O3', 'Fe2O3','CaO', 'TPH');
                    TagHelper::renderTagtable($tagObject, $elements);
                    ?>
                </section>
            </div>
            <div class="clear"><br/></div>
        </section>
    </div>
</section>


<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl . '/js/jquery.min_new.js' ?>" ></script>
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl . '/js/jquery.dataTables.min.js' ?>" ></script>
<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl . '/js/dataTables.tableTools.min.js' ?>" ></script>

<script type="text/javascript">


    $("#tagview").dataTable({
        "sPaginationType": "full_numbers",
        "bLengthChange": true,
        "bFilter": false,
        "bSort": false,
        "bInfo": true,
        "bAutoWidth": true,
        "bDestroy": true,
        "iDisplayLength": 25,
        dom: "T<'clear'>lftip"
    });

    function editChQat(cqt) {

        setTimeout(function(){ $(".swal-content__input").val(cqt); }, 500);
        swal({

            text: 'Enter challan quantity',
            content: "input",
            attributes: {
                placeholder: "Enter challan quantity",
                type: "number",
            },
            button: {
                text: "Submit",
                closeModal: false,
            },
        })
                .then(name => {
                    if (!name)
                        throw null;

                    return fetch(`<?php echo Yii::app()->createAbsoluteUrl('TruckInfo/updateChlQty') ?>?ChaQty=${name}&tripId=<?php echo $tripInfo['w_tripID'] ?>`);
                })
                .then(results => {
                    return results;
                })
                .then(json => {
                    const movie = json
                    //debugger
                    if (movie === 'Error') {
                        return swal("Unable to update data");
                    }

                    location.reload();

                })
                .catch(err => {
                    if (err) {
                        swal("Error", "Unable to update data", "error");
                    } else {
                        swal.stopLoading();
                        swal.close();
                    }
                });

    }


</script>