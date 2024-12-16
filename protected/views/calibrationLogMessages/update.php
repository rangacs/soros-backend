<?php
/* @var $this CalibrationLogMessagesController */
/* @var $model CalibrationLogMessages */

$this->breadcrumbs=array(
	'Calibration Log Messages'=>array('index'),
	$model->title=>array('view','id'=>$model->cal_msg_id),
	'Update',
);

$this->menu=array(
	array('label'=>'List CalibrationLogMessages', 'url'=>array('index')),
	array('label'=>'Create CalibrationLogMessages', 'url'=>array('create')),
	array('label'=>'View CalibrationLogMessages', 'url'=>array('view', 'id'=>$model->cal_msg_id)),
	array('label'=>'Manage CalibrationLogMessages', 'url'=>array('admin')),
);
?>

<h1>Update CalibrationLogMessages <?php echo $model->cal_msg_id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>