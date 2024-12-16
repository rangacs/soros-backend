<?php
/* @var $this TruckInfoController */
/* @var $model TruckInfo */

$this->breadcrumbs=array(
	'Truck Infos'=>array('index'),
	$model->wcl_trip_id=>array('view','id'=>$model->wcl_trip_id),
	'Update',
);

$this->menu=array(
	array('label'=>'List TruckInfo', 'url'=>array('index')),
	array('label'=>'Create TruckInfo', 'url'=>array('create')),
	array('label'=>'View TruckInfo', 'url'=>array('view', 'id'=>$model->wcl_trip_id)),
	array('label'=>'Manage TruckInfo', 'url'=>array('admin')),
);
?>
<?php
/* @var $this TruckInfoController */
/* @var $model TruckInfo */

$this->breadcrumbs=array(
	'Truck Infos'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List TruckInfo', 'url'=>array('index')),
	array('label'=>'Manage TruckInfo', 'url'=>array('admin')),
);
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
                    <header class="ui-widget-header ui-corner-top form">


                    </header>

                    <section id="section_rawMix" class="ui-widget-content ui-corner-bottom" style="min-height:600px;">

                        <section id="portlet-set-point"  class=" ui-tabs-panel ui-widget-content ui-corner-bottom">



<h1>Update TruckInfo <?php echo $model->wcl_trip_id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>
                        </section>

                </div>
            </section>

        </div>

        <div class="clear"><br/></div>



    </div>
</section>


