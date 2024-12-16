<?php
/* @var $this RtaAveragedConfigController */
/* @var $model RtaAveragedConfig */

$this->breadcrumbs=array(
	'Rta Averaged Configs'=>array('index'),
	$model->rta_ID_averaged,
);

$this->menu=array(
	array('label'=>'List RtaAveragedConfig', 'url'=>array('index')),
	array('label'=>'Create RtaAveragedConfig', 'url'=>array('create')),
	array('label'=>'Update RtaAveragedConfig', 'url'=>array('update', 'id'=>$model->rta_ID_averaged)),
	array('label'=>'Delete RtaAveragedConfig', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->rta_ID_averaged),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage RtaAveragedConfig', 'url'=>array('admin')),
);
?>

<h1>View RtaAveragedConfig #<?php echo $model->rta_ID_averaged; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'rta_ID_averaged',
		'rtaMasterID',
		'groupAveragingID',
		'massflowWeight_averagedCfg',
		'goodDataSecondsWeight_averagedCfg',
	),
)); ?>
