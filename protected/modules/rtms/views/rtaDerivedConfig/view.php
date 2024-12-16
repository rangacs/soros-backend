<?php
/* @var $this RtaDerivedConfigController */
/* @var $model RtaDerivedConfig */

$this->breadcrumbs=array(
	'Rta Derived Configs'=>array('index'),
	$model->rta_ID_derived,
);

$this->menu=array(
	array('label'=>'List RtaDerivedConfig', 'url'=>array('index')),
	array('label'=>'Create RtaDerivedConfig', 'url'=>array('create')),
	array('label'=>'Update RtaDerivedConfig', 'url'=>array('update', 'id'=>$model->rta_ID_derived)),
	array('label'=>'Delete RtaDerivedConfig', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->rta_ID_derived),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage RtaDerivedConfig', 'url'=>array('admin')),
);
?>

<h1>View RtaDerivedConfig #<?php echo $model->rta_ID_derived; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'rta_ID_derived',
		'rtaMasterID',
		'data_source_rtaMasterID',
		'filter_type',
		'moving_avg_filter_sample_timespan',
		'kalman_filter_sample_timespan',
		'massflowWeight_derivedCfg',
		'goodDataSecondsWeight_derivedCfg',
		'percentageGoodDataRequired',
		'kalman_gain_Q',
		'kalman_gain_R',
		'source_decay_comp',
		'source_decay_ref_date',
	),
)); ?>
