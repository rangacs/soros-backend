<?php
/* @var $this TagQueuedController */
/* @var $model TagQueued */

$this->breadcrumbs=array(
	'Tag Queueds'=>array('index'),
	$model->tagID=>array('view','id'=>$model->tagID),
	'Update',
);

$this->menu=array(
	array('label'=>'List TagQueued', 'url'=>array('index')),
	array('label'=>'Create TagQueued', 'url'=>array('create')),
	array('label'=>'View TagQueued', 'url'=>array('view', 'id'=>$model->tagID)),
	array('label'=>'Manage TagQueued', 'url'=>array('admin')),
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
	
       <section class="container_6 clearfix">

            <!-- Tabs inside Portlet -->
            <div class="grid_6 leading" style="min-height:250px !important;">
                <header class="ui-widget-header ui-widget-header-blue ui-corner-top">
                    <h2><?php echo Yii::t('app','Update')?> - <?php echo Yii::t('app','Tag')?>  </h2>

                </header>
                <section id="section_rawMix" class="ui-widget-content ui-corner-bottom" style="min-height:600px;">
                    <div class="grid_5 " style="width: 700px">
                       <?php $this->renderPartial('_form', array('model'=>$model)); ?>

                    </div>
                </section>

                </div>
            </section>	

        </div>

        <div class="clear"><br/></div>
</section>
