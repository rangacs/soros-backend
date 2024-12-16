<?php
/* @var $this CalibrationLogMessagesController */
/* @var $model CalibrationLogMessages */

$this->breadcrumbs=array(
	'Calibration Log Messages'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>'List CalibrationLogMessages', 'url'=>array('index')),
	array('label'=>'Manage CalibrationLogMessages', 'url'=>array('admin')),
);
?>

<h1>Create CalibrationLogMessages</h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>