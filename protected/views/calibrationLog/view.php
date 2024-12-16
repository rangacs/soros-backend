<?php
/* @var $this CalibrationLogController */
/* @var $model CalibrationLog */

$this->breadcrumbs=array(
	'Calibration Logs'=>array('index'),
	$model->id,
);

$this->menu=array(
	array('label'=>'List CalibrationLog', 'url'=>array('index')),
	array('label'=>'Create CalibrationLog', 'url'=>array('create')),
	array('label'=>'Update CalibrationLog', 'url'=>array('update', 'id'=>$model->id)),
	array('label'=>'Delete CalibrationLog', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage CalibrationLog', 'url'=>array('admin')),
);
?>

<h1>View CalibrationLog #<?php echo $model->id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'id',
		'SiO2_gain',
		'SiO2_offset',
		'Fe2O3_gain',
		'Fe2O3_offset',
		'Al2O3_gain',
		'Al2O3_offset',
		'CaO_gain',
		'CaO_offset',
		'updated_by',
		'updated',
	),
)); ?>
