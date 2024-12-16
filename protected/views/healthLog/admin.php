<?php
/* @var $this HealthLogController */
/* @var $model HealthLog */

$this->breadcrumbs=array(
	'Health Logs'=>array('index'),
	'Manage',
);

$this->menu=array(
	array('label'=>'List HealthLog', 'url'=>array('index')),
	array('label'=>'Create HealthLog', 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$('#health-log-grid').yiiGridView('update', {
		data: $(this).serialize()
	});
	return false;
});
");
?>
<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'health-log-grid',
         'dataProvider' => $dataProvider,
	'filter'=>$model,
	'columns'=>array(
		//'wcl_a_id',
		//'wcl_plantCode',
		//'wcl_unloaderId',
		'wcl_healthStatus',
		'wcl_auto_tag_id',
		'wcl_auto_tag_mCode',
		'wcl_ash',
		'wcl_moisture',
		'wcl_sulfur',
		'wcl_gcv',
		'wcl_timestamp',
	),
)); ?>
