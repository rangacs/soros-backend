<?php
/* @var $this TruckInfoController */
/* @var $model TruckInfo */

$this->breadcrumbs = array(
    'Truck Infos' => array('index'),
    'Manage',
);

$this->menu = array(
    array('label' => 'List TruckInfo', 'url' => array('index')),
    array('label' => 'Create TruckInfo', 'url' => array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#truck-info-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>
<?php
/* @var $this TruckInfoController */
/* @var $model TruckInfo */

$this->breadcrumbs = array(
    'Truck Infos' => array('index'),
    'Create',
);

$this->menu = array(
    array('label' => 'List TruckInfo', 'url' => array('index')),
    array('label' => 'Manage TruckInfo', 'url' => array('admin')),
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
		    <header class="ui-widget-header ui-corner-top form padding-5"> 
                        <h3 style="padding-left:10px;"> Manage Trip Information</h3>
                    </header>

                    <section id="section_rawMix" class="ui-widget-content ui-corner-bottom" style="min-height:600px;border:0px !important">

                        <section id="portlet-set-point"  class=" ui-tabs-panel ui-widget-content ui-corner-bottom">

                            <a style="font-weight:bold;" data-icon-primary="ui-icon-circle-plus" href="<?= Yii::app()->createAbsoluteUrl("truckInfo/create")?>" class="pull-right ui-button ui-widget ui-state-highlight ui-corner-all ui-state-focus ui-button-text-icon-primary" role="button">
							<span class="ui-button-icon-primary ui-icon ui-icon-circle-plus"></span>
							<span class="ui-button-text">New </span>
                            </a>

                            <?php echo CHtml::link('Advanced Search', '#', array('class' => 'search-button')); ?>
                            <div class="search-form" style="display:none">
                                <?php
                                $this->renderPartial('_search', array(
                                    'model' => $model,
                                ));
                                ?>
                            </div><!-- search-form -->

                            <?php
							
							$this->widget('zii.widgets.grid.CGridView', array(
								'id'=>'truck-info-grid',
								'dataProvider'=>$dataProvider,
								'filter'=>$model,
								'columns'=>array(
									'w_tripID',
									'w_vehNo',
									'w_plantCode',
									'w_unloaderID',
									'w_matCode',
									'w_matName',
									'w_timestamp',
									/*
									'w_suppCode',
									'w_suppName',
									'w_traCode',
									'w_traName',
									'w_loadCity',
									'w_chQty',
									'w_timestamp',

									array(
										'class'=>'CButtonColumn',
									),
									*/
								),
							));
							?>

                        </section>

                </div>
            </section>

        </div>

        <div class="clear"><br/></div>



    </div>
</section>


