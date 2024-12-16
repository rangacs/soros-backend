<?php
/* @var $this CalibrationLogMessagesController */
/* @var $model CalibrationLogMessages */

$this->breadcrumbs=array(
	'Calibration Log Messages'=>array('index'),
	$model->title,
);

$this->menu=array(
	array('label'=>'List CalibrationLogMessages', 'url'=>array('index')),
	array('label'=>'Create CalibrationLogMessages', 'url'=>array('create')),
	array('label'=>'Update CalibrationLogMessages', 'url'=>array('update', 'id'=>$model->cal_msg_id)),
	array('label'=>'Delete CalibrationLogMessages', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->cal_msg_id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>'Manage CalibrationLogMessages', 'url'=>array('admin')),
);
?>

<h1>View CalibrationLogMessages #<?php echo $model->cal_msg_id; ?></h1>

<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'cal_msg_id',
		'auto_calib_id',
		'error_type',
		'title',
		'description',
		'updated_at',
		'updated_by',
	),
)); ?>
