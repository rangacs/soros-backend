<?php
/* @var $this RfidCalMapController */
/* @var $dataProvider CActiveDataProvider */


$this->menu = array(
    array('label' => 'Create RfidCalMap', 'url' => array('create')),
    array('label' => 'Manage RfidCalMap', 'url' => array('admin')),
);

$modelData = RfidCalMap::model()->findAll();
$firstModel = $modelData[0];
$calibMap = CHtml::listData(
                $modelData, 'wcl_item_code', 'wcl_item_namev'
);

$selectedCal = isset($_REQUEST['id']) ? $_REQUEST['id'] : $firstModel->wcl_item_code;
?>

<style type="text/css">
    .dropdownListCss select {
        background-color: #447ba2;
        color: white;
        padding: 12px;
        width: 250px;
        border: none;
        font-size: 20px;
        box-shadow: 0 5px 25px rgba(0, 0, 0, 0.6);
        -webkit-appearance: button;
        appearance: button;
        outline: none;
    }
</style>


<?php
/* @var $this TruckInfoController */
/* @var $model TruckInfo */
/* @var $this TruckInfoController */
/* @var $dataProvider CActiveDataProvider */
?>


<section class="main-section grid_8">
    <nav class="">

        <?php
        $baseUrl = Yii::app()->basePath;

        $menuFile = $baseUrl . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "themes" . DIRECTORY_SEPARATOR . "tutorialzine1" . DIRECTORY_SEPARATOR . "views" . DIRECTORY_SEPARATOR . "pageDefaults" . DIRECTORY_SEPARATOR . "authLeftMenu.php";
        include_once($menuFile);
        ?>
    </nav>
    <div class="main-content">


        <div class="grid_6 leading" style="min-height:250px !important;">

            <section class="container_6 clearfix">

                <!-- Tabs inside Portlet -->
                <div class="grid_6 leading">
                    <header class="ui-widget-header ui-corner-top form padding-5">

                        Calibration Settings Map

                    </header>

                    <section id="section_rawMix" class="ui-widget-content ui-corner-bottom" style="min-height:600px;">

                        <section id="portlet-set-point"  class=" ui-tabs-panel ui-widget-content ui-corner-bottom">


                            <h1 style="float:left" id="selected-profile-name">Calib map</h1>
                            <span style="padding:15px;font:14px solid;color:blue; float: right" class="dropdownListCss"> 
                                <?php
                                echo CHtml::dropDownList('Calib[item_code]', $selectedCal, $calibMap, array('empty' => 'Select Option'));
                                ?>
                            </span>

                            <div class="clearfix"></div>

                            <div id="form-section" style="padding:20px">

                            </div>



                        </section>

                </div>
            </section>

        </div>

        <div class="clear"><br/></div>

    </div>
</section>



<script type="text/javascript">

    window.onload = function () {

        var selected = '<?php echo $selectedCal ?>'

        if (selected.length > 0) {

        
            getForm(selected)

        }


        function getForm(id) {

         update_url = '<?php echo Yii::app()->createAbsoluteUrl("rfidCalTolSettings/update") ?>/' + id;

         var selected_text = $( "#Calib_item_code option:selected" ).text();
         $("#selected-profile-name").text(selected_text)
            $.ajax({
                url: update_url,
                success: function (response) {
                    $("#form-section").html(response);
                }
            })


        }

        $("#Calib_item_code").live('change', function () {

            //  alert('su
            var item_code = $(this).val();
            getForm(item_code)
        })
    }


</script>
