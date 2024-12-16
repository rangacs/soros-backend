<?php
/* @var $this CalibrationLogController */
/* @var $model CalibrationLog */

$this->breadcrumbs=array(
	'Calibration Logs'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List CalibrationLog', 'url'=>array('index')),
	array('label'=>'Manage CalibrationLog', 'url'=>array('admin')),
);
?>

<h1>Create CalibrationLog</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>