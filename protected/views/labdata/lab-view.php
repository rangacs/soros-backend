<?php
$id = $_REQUEST['lab_hist_id'];

$baseUrl = Yii::app()->baseUrl;

$labDataHistoryDeleteUrl = Yii::app()->createAbsoluteUrl('labdata/deleteRecord');

$viewTemplate = Yii::app()->createAbsoluteUrl('lab-data/view-template');

$activeTemplate = LabTemplate::model()->find('status = :status', array(':status' => 1));


$cs = Yii::app()->clientScript;


$cs->registerScript('appConfig', "var baseUrl = " . json_encode($baseUrl) . ";"
        . "var lab_data_history_delete = '{$labDataHistoryDeleteUrl}';"
        . "var view_template = '{$viewTemplate}';", CClientScript::POS_BEGIN);



$existingEleList = array('EndTime', 'SiO2', 'Al2O3', 'Fe2O3', 'CaO', 'KH', 'SM', 'IM');



$database = Yii::app()->db->createCommand("SELECT DATABASE()")->queryScalar();

$colquery = "SELECT COUNT(*)  FROM INFORMATION_SCHEMA.COLUMNS
                                                WHERE table_schema = '" . $database . "'
                                                  AND table_name = 'lab_history_data'";

$colInfo = Yii::app()->db->createCommand("SELECT *  FROM INFORMATION_SCHEMA.COLUMNS
                                                WHERE table_schema = '" . $database . "'
                                                  AND table_name = 'lab_history_data'")->query()->readAll();


//$labTempColumns = LabUtility::getColumn($colInfo, 'COLUMN_NAME');

$labTempColumns = array('EndTime', 'SiO2', 'Al2O3', 'Fe2O3', 'CaO', 'KH', 'SM', 'IM');
$colCount = Yii::app()->db->createCommand($colquery)->queryScalar();




$result = Yii::app()->db->createCommand('select * from lab_history_data')->queryAll();
?>
<!--pawan removed-->
<div class="row">
    <div class="col-md-12">


        <div class="portlet light">
            <div class="portlet-title">
                <div class="caption">
                    <span class="caption-subject bold uppercase font-black"><?php echo Yii::t('app', ' Lab Data'); ?></span>
                </div>

                <div class="tools">
                </div>

            </div>
            <div class="portlet-body">


<?php
$cTextLink = Yii::t('app', 'Compare');

echo CHtml::link($cTextLink, array('labdata/historyCompare', 'lab_hist_id' => $_REQUEST['lab_hist_id'], 'rid' => $_REQUEST['lab_hist_id']), array('class' => 'btn btn-theme-color float-right  margin-bottom-20'));
?>
                <div class="clearfix"></div>
                <div class="table-responsive margin-top-20r">


                    <table class="table table-blue">


                        <tr>
<?php
foreach ($labTempColumns as $column){
	if($column == 'EndTime')
		echo "<th>".Yii::t('dash', 'End Time')."</th>";
	else
		echo "<th>$column</th>";
}
?>
                            <th>
                                <?php echo Yii::t('dash', 'Action')?>
                            </th>
                        </tr>

                            <?php
                            $count = Yii::app()->db->createCommand('
                                SELECT COUNT(*) FROM lab_history_data 
                            ')->queryScalar();

                            $dataProvider = new CSqlDataProvider('SELECT * , lab_data_id as id  FROM lab_history_data   order by EndTime DESC', array(
                                'totalItemCount' => $count,
                                /* 'sort'=>array(
                                  'attributes'=>array(
                                  'EndTime' => SORT_DESC
                                  ),
                                  ), */
                                'pagination' => array(
                                    'pageSize' => 50,
                                ),
                            ));





                            $this->widget('zii.widgets.CListView', array(
                                'dataProvider' => $dataProvider,
                                'template' => '{items}',
                                'viewData' => array('displayElement' => $labTempColumns,
                                ),
                                'itemView' => '_item_view',
                            ));
                            ?>
                    </table>
                </div>
            </div>

        </div>


    </div>
</div>


<script type="text/javascript">

    //alert();
    window.onload = function () {

        $(".delete-btn").on('click', function () {

            deleteRecord(this);
        });

    }


    function deleteRecord(ele) {

        var dataTime = $(ele).attr('data-time');



        $.ajax({
            url: lab_data_history_delete,
            type: 'POST',
            data: {EndTime: dataTime, lab_hist_id: '<?= $_REQUEST['lab_hist_id'] ?>'},
            success: function (response) {


                console.log(response)
                if (response.file_deleted == 1) {
                    window.location = '<?php echo Yii::app()->createAbsoluteUrl('/labdata/index'); ?>'
                } else {

                    swal('Sucess ', 'Record deleted.', 'success');
                    window.location = '';
                }
            }

        }); //end of ajax
    }

</script>