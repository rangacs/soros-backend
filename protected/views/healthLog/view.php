<?php
/* @var $this HealthLogController */
/* @var $model HealthLog */

$this->breadcrumbs=array(
	'Health Logs'=>array('index'),
	$model->wcl_a_id,
);

$this->menu=array(
	array('label'=>'List HealthLog', 'url'=>array('index')),
	array('label'=>'Create HealthLog', 'url'=>array('create')),
	array('label'=>'Update HealthLog', 'url'=>array('update', 'id'=>$model->wcl_a_id)),
	array('label'=>'Delete HealthLog', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->wcl_a_id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage HealthLog', 'url'=>array('admin')),
);
?>

<h1>View HealthLog #<?php echo $model->wcl_a_id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'wcl_a_id',
		'wcl_plantCode',
		'wcl_unloaderId',
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
