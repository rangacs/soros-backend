<?php
/* @var $this CalibrationLogController */
/* @var $model CalibrationLog */

$this->breadcrumbs=array(
	'Calibration Logs'=>array('index'),
	$model->id=>array('view','id'=>$model->id),
	'Update',
);

$this->menu=array(
	array('label'=>'List CalibrationLog', 'url'=>array('index')),
	array('label'=>'Create CalibrationLog', 'url'=>array('create')),
	array('label'=>'View CalibrationLog', 'url'=>array('view', 'id'=>$model->id)),
	array('label'=>'Manage CalibrationLog', 'url'=>array('admin')),
);
?>

<h1>Update CalibrationLog <?php echo $model->id; ?></h1>

<?php $this->renderPartial('_form', array('model'=>$model)); ?>