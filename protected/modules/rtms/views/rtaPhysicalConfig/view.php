<?php
/* @var $this RtaPhysicalConfigController */
/* @var $model RtaPhysicalConfig */

$this->breadcrumbs=array(
	'Rta Physical Configs'=>array('index'),
	$model->rta_ID_physical,
);

$this->menu=array(
	array('label'=>'List RtaPhysicalConfig', 'url'=>array('index')),
	array('label'=>'Create RtaPhysicalConfig', 'url'=>array('create')),
	array('label'=>'Update RtaPhysicalConfig', 'url'=>array('update', 'id'=>$model->rta_ID_physical)),
	array('label'=>'Delete RtaPhysicalConfig', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->rta_ID_physical),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage RtaPhysicalConfig', 'url'=>array('admin')),
);
?>

<h1>View RtaPhysicalConfig #<?php echo $model->rta_ID_physical; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'rta_ID_physical',
		'rtaMasterID',
		'IPaddress',
		'goodDataSecondsWeight_physicalCfg',
		'massflowWeight_physicalCfg',
		'analysis_timespan',
		'averaging_subinterval_secs',
		'detectorID',
	),
)); ?>
