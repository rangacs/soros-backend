<?php
/* @var $this CalibrationLogController */
/* @var $dataProvider CActiveDataProvider */

$this->breadcrumbs=array(
	'Calibration Logs',
);

$this->menu=array(
	array('label'=>'Create CalibrationLog', 'url'=>array('create')),
	array('label'=>'Manage CalibrationLog', 'url'=>array('admin')),
);
?>

<h1>Calibration Logs</h1>

<?php $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
