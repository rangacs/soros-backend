<?php
/* @var $this AcSettingsController */
/* @var $model AcSettings */

$this->breadcrumbs = array(
    'Ac Settings' => array('index'),
    'Manage',
);

$this->menu = array(
    array('label' => 'List AcSettings', 'url' => array('index')),
    array('label' => 'Create AcSettings', 'url' => array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#ac-settings-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>
<div class="row"> 
    <div class="col-md-12"> 
        <!--Portlet Start-->
        <div class="portlet light" >

            <div  class="portlet-title">
                <div class="caption">
                    <?php echo Yii::t('app', 'Auto Calibration Settings'); ?>    
                </div>

            </div>
            <div class="portlet-body">
                
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'ac-settings-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
//		'ac_id',
		'element_name',
		'min',
		'max',
		'diff',
		'max_offset_change',
		
		'correction_pct',
		'last_updated',
		
		array(
			'class'=>'CButtonColumn',
		),
	),
)); ?>

            </div>
        </div>
        <!--Portlet end-->
    </div>

</div>
