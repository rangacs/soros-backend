<?php
/* @var $this CalibrationLogController */
/* @var $model CalibrationLog */




if (isset($_REQUEST['sort'])) {

    if ($_REQUEST['sort'] == 'ASC') {

        $sortUrl = Yii::app()->createAbsoluteUrl('calibrationLog/index', array('sort' => 'DESC'));
    } else {

        $sortUrl = Yii::app()->createAbsoluteUrl('calibrationLog/index', array('sort' => 'ASC'));
    }
} else {


    $sortUrl = Yii::app()->createAbsoluteUrl('calibrationLog/index', array('sort' => 'ASC'));
}



$this->breadcrumbs = array(
    'Calibration Logs' => array('index'),
    'Manage',
);

$this->menu = array(
    array('label' => 'List CalibrationLog', 'url' => array('index')),
    array('label' => 'Create CalibrationLog', 'url' => array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#calibration-log-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");


$autoCalFlag = Yii::app()->db->createCommand("select * from rm_settings where varKey ='AUTO_CALIB'")->queryRow();
$autoCalFlag['varValue'];

if (isset($autoCalFlag['varValue'])) {
    $val = (int) $autoCalFlag['varValue'];
    $autoFlag = $val === 1 ? 'checked' : '';
}
?>

<style>
    .auto-calib span{
        display: none;
    }

    .auto-calib:hover span{
        display: table;
    }
</style>

<div class="row"> 
    <div class="col-md-12"> 
        <!--Portlet Start-->
        <div class="portlet light" >

            <div  class="portlet-title">
                <div class="caption">
                    <span><?php echo Yii::t('app', 'Calibration Log'); ?>  
                    </span>
                </div>
                <div class="tool float-right auto-calib" style="height:30px;width: 150px;">

                    <span> <input type="checkbox" 
                                  class='make-switch' 
                                  data-on-color="success" 
                                  data-off-color = "danger" 
                                  data-size = "mini" 
                                  value="AUTO_CALIB"
                                  accept=""<?php echo $autoFlag ?>
                                  > 
                    </span>

                </div>

            </div>
            <div class="portlet-body">

                <table class="table table-bordered table-striped table-blue" id="log-table">

                    <thead>
                        <tr>

                            <th><a href=' <?php echo $sortUrl ?>'> Updated</a></th>

                            <th>Run Type </th>
                            <th>SiO2 </th>

                            <th> Al2O3</th>

                            <th> Fe2O3</th>
                            <th> CaO</th>
                        </tr>
                    </thead>
                    <tbody>	

<?php
$dataModels = $dataProvider->getData();
$this->widget('zii.widgets.CListView', array(
    'dataProvider' => $dataProvider,
    'template' => '{items}',
    'viewData' => array('dataModels' => $dataModels,
    ),
    'itemView' => '_view',
));
?>
                    </tbody>

                </table>




<?php
$this->widget('CLinkPager', array(
    'pages' => $dataProvider->getPagination(),
    'firstPageLabel' => 'First',
    'lastPageLabel' => 'Last',
    'prevPageLabel' => '<<  ',
    'nextPageLabel' => ' >>',
    'htmlOptions' => array('class' => ' pagination pull-right', 'id' => 'paginationB'),
    'hiddenPageCssClass' => 'hide',
    'internalPageCssClass' => 'pagenum',
    'header' => '',
));
?>
            </div>
        </div>
        <!--Portlet end-->
    </div>

</div>

<script type="text/javascript">

    var selectCount = 0, maxSelection;
    $(document).ready(function () {



        var table = $('#log-table').DataTable({
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


        $('input[type="checkbox"]').on('switchChange.bootstrapSwitch', (e) => {
            var element = e.target;
            var value;
            if (element.checked) {
                value = 1;
            } else {

                value = 0;
            }

            $.ajax({
                url: "<?php echo Yii::app()->createAbsoluteUrl('calibrationLog/setStatus') ?>",
                method: 'post',
                data: {key: element.value, value: value},
                success: function () {

                    swal('Success', '"' + element.value + '"' + ' Flag Updated ', 'success');
                },
                error: function () {

                    swal('Error', 'Err Couldn\'t update   ' + element.value, 'error');
                }
            });
            //console.log(element);

        });

    });
</script>